<?php

namespace app\modules\common\models;

use app\modules\hr\models\HrStaff;
use Yii;

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
class BsReviewRule extends \yii\db\ActiveRecord
{
    const STATUS_DELETE=0;
    const STATUS_DEFAULT=10;

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
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => '最后更新人',
            'update_at' => '最后更新时间',
        ];
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
}
