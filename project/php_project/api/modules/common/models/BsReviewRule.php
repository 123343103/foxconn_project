<?php

namespace app\modules\common\models;

use app\models\Common;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * 业务审批流程模型
 * F3858995
 * 2016.10.27
 *
 * @property integer $review_rule_id
 * @property string $business_code
 * @property string $business_type_code
 * @property string $review_desc
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsReviewRule extends Common
{
    const STATUS_DELETE=0;
    const STATUS_DEFAULT=10;
    const STATUS_EDITABLE=20; //可编辑

    /**
     * @inheritdoc
     */
    public static function tableName()
    {

        return 'bs_review_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_code', 'review_desc','business_status'], 'safe'],
            [['create_by', 'update_by','create_at', 'update_at'], 'safe'],
            [['business_code', 'review_desc'], 'safe'],
            [['org'], 'integer'],
            [['business_type_id'], 'safe'],
            [['business_status'],'default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_rule_id' => '主键ID',
            'business_code' => '业务ID',
            'business_type_id' => '业务类别代码',
            'review_desc' => '审批流程说明',
            'org' => '部门',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => '最后更新人',
            'update_at' => '最后更新时间',
        ];
    }

    /**
     * 获取菜单树
     * @param int $pid
     * @return string
     */
    public static function getTree($pid = 0)
    {
        static $str = "";
        $tree = self::find()->andWhere(['pid' => $pid])->all();
        $selected=false;
        foreach ($tree as $key => $val) {
            $childs = self::find()->where(['pid' => $val['review_rule_id']])->one();
            if(!empty($val['review_url'])) {
                $selected=true;
            }
            $str .= "
               {  
                id :\"". $val['review_rule_id'] . "\",
                text :\"". $val['review_desc'] . "\",
                selectable :\"". $selected . "\",
            ";
            if ($childs) {
                $str .= "
                            nodes:[";
                self::getTree($val['review_rule_id']);
                $str .= "
                            ]},";
            } else {
                $str .= "
                    },
                ";
            }
        }
        return $str;
    }
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAll(){
        return self::find()->joinWith(['business','businessType','lastUpdate'])->asArray()->all();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getOne($id){
        return self::find()->where(["review_rule_id"=>$id])->joinWith(['business','businessType','lastUpdate','conditions'])->asArray()->one();
    }

    /**
     * 根据单据类型获取审核规则
     * @param $businessTypeId
     * @param $orgId
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getRule($businessTypeId,$orgId=null){
        if (!empty($orgId)) {
            return self::find()->where(['and',["bs_review_rule.business_type_id"=>$businessTypeId],['=','org',$orgId]])->joinWith(['business','businessType','lastUpdate','conditions'])->asArray()->one();
        } else {
            return self::find()->where(['and',["bs_review_rule.business_type_id"=>$businessTypeId],['is','org',null]])->joinWith(['business','businessType','lastUpdate','conditions'])->asArray()->one();
        }
    }

    /**
     * 
     * @param $type
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getOneByType($type){
        return self::find()->where(["business_type_id"=>$type])->one();
    }
    /**
     * 关联业务逻辑
     * @return \yii\db\ActiveQuery
     */
    public function getBusiness(){
        return $this->hasOne(BsBusiness::className(),['business_code'=>"business_code"]);
    }

    /**
     * 关联业务类型
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessType(){
        return $this->hasOne(BsBusinessType::className(),['business_type_id'=>"business_type_id"]);

    }

    /**
     * 关联审批条件
     * @return \yii\db\ActiveQuery
     */
    public function getConditions(){
        return $this->hasMany(BsReviewColumn::className(),['business_code'=>"business_code"] );
    }

    
    /**
     * 关联最后更新人
     * @return \yii\db\ActiveQuery
     */
    public function getLastUpdate(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>"update_by"] );
    }
    
    public function getReviewChildren(){
        return $this->hasMany(BsReviewRuleChild::className(),['review_rule_id'=>"review_rule_id"] )->orderBy('rule_child_index');
    }

    // 获取部门规则
    public function getOrgRules($code)
    {
        $query=(new Query())->select(['*'])
            ->from(['rule' => self::tableName()])
            ->leftJoin(HrOrganization::tableName()." ho","ho.organization_id=rule.org")
            ->where(['and',['=','business_code',$code],['is not','org',null]]);
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
        ]);
        return $dataProvider;
    }
}
