<?php

namespace app\modules\hr\models;

use Yii;
use app\modules\hr\models\Staff;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "organization".
 *
 * @property integer $organization_id
 * @property string $organization_code
 * @property string $organization_name
 * @property integer $organization_pid
 * @property string $organization_description
 * @property string $organization_leader
 * @property integer $is_abandoned
 * @property string $create_date
 * @property string $creator
 * @property string $organization_level
 */
class HrOrganization extends \yii\db\ActiveRecord
{

    //商品開發處 部門代碼
    const PD_DEPARTMENT_CODE = 'THA30008';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organization_code','organization_name'], 'required'],
            [['organization_description', 'organization_level'], 'string'],
            [['create_date','organization_pid'], 'safe'],
            [['organization_code'], 'string', 'max' => 50],
            [['organization_name', 'creator'], 'string', 'max' => 255],
            [['organization_leader'], 'string', 'max' => 20],
            [['organization_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'organization_id' => '部门 ID',
            'organization_code' => '部门代码',
            'organization_name' => '部门名称',
            'organization_pid' => '上级部门',
            'organization_description' => '部门描述',
            'organization_leader' => '部门主管',
            'is_abandoned' => '是否弃用',
            'create_date' => 'Create Date',
            'creator' => 'Creator',
            'organization_level' => '部门级别',
            'organization_state' => '部门狀態',
        ];
    }


    /*
     * 组织机构菜单树
     *
     */
    public function getOrgTree($pid = 0)
    {
        static $str = "";
        $tree=self::find()->andWhere(['organization_pid' => $pid])->andWhere(['organization_state'=> '10'])
            ->select('organization_id,organization_name,organization_code,organization_pid,organization_level')->all();
        foreach ($tree as $key => $val){
//            $del_url = \Yii::$app->urlManager->createUrl(['goods/type/delete', 'id' => $val['type_id']]);
//            $add_url = \Yii::$app->urlManager->createUrl(['goods/type/create','level'=>$val['type_level']]);
//            $upd_url = \Yii::$app->urlManager->createUrl(['goods/type/update', 'id' => $val['type_id']]);
            $organization_id=$val['organization_id'];
            $organization_pid=$val['organization_pid'];

                $add="<a class=''onclick='orgBelow($organization_id);' ><i class='icon-sitemap icon-l' title='新增下級'></i></a>";
                $edit="<a class='' onclick='orgUpd($organization_id);' ><i class='icon-edit  icon-l' title='編輯'></i></a>";
                $del="<a class='' onclick='orgDel($organization_id);' ><i class='icon-trash  icon-l' title='刪除'></i></a>";
            if($organization_pid == 0 ){
                $del=$edit='';
//                $search="<a class='' onclick='orgSearch($organization_id);' ><i class='icon-search icon-l' title='搜索'></i></a>";
            }
            $str .= "
               {  
                text :\""
                .$val['organization_name']
                ."<span style='float:right;'>"
                .$edit." ".$add." ".$del
                ."</span>"."\",";
            $childs=$this::find()->where(['organization_pid'=>$val['organization_id']])->select('organization_id,organization_name,organization_code,organization_pid,organization_level')->asarray()->one();
            if ($childs) {
                $str .= "
                            nodes:[";
                $this->getOrgTree($val['organization_id']);
                $str .="
                            ]},";
            }else{
                $str .= "
                    },
                ";
            }
        }
        return $str;
    }
    public static function getPDvlpOption($code){
        $parent = self::findOne(['organization_code'=>$code]);
        $child = self::find()->asArray()->where(['organization_pid'=>$parent->organization_id])->select('organization_code,organization_name')->all();
        foreach ($child as $key=>$val){
            $newList[$val['organization_code']]=$val['organization_name'];
        }
        return $newList;
    }

    public function getStaff()
    {
        //同样第一个参数指定关联的子表模型类名
        return $this->hasMany(HrStaff::className(), ['organization_code' => 'organization_code']);
    }
}
