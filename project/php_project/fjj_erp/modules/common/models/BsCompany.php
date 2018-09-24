<?php

namespace app\modules\common\models;

use Yii;

/**
 * 公司模型
 *
 * @property integer $company_id
 * @property string $company_code
 * @property string $company_name
 * @property integer $parent_id
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsCompany extends \yii\db\ActiveRecord
{

    const STATUS_DEL=0;
    const STATUS_DEFAULT=10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name'], 'required'],
            [['parent_id', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['company_code', 'company_name'], 'string', 'max' => 255],
            [['company_status'], 'default', 'value' => self::STATUS_DEFAULT],
            [['parent_id'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'company_code' => '公司代码',
            'company_name' => '公司名称',
            'parent_id' => '上级',
            'company_status'=>'状态',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
    }

    public static function getIdsArr($id){
        static $ids;
        $ids[] = $id;
        $child = self::find()->where(['parent_id'=>$id])->select('company_id')->all();
        foreach ($child as $key => $val ){
            self::getIdsArr($val->company_id);
        }
        return $ids;
    }


    /*
    * 公司菜单树
    *
    */
    public function getCompanyTree($pid = 0)
    {
        static $str = "";
        $tree=self::find()->where(['parent_id' => $pid])->andWhere(['company_status'=>'10'])->select('company_id,company_code,company_name,parent_id')->all();
        foreach ($tree as $key => $val){
            $company_id=$val['company_id'];
            $parent_id=$val['parent_id'];

            $add="<a class=''onclick='companyBelow($company_id);' ><i class='icon-sitemap icon-l' title='新增下級'></i></a>";
            $edit="<a class='' onclick='companyUpd($company_id);' ><i class='icon-edit  icon-l' title='編輯'></i></a>";
            $del="<a class='' onclick='companyDel($company_id);' ><i class='icon-trash  icon-l' title='刪除'></i></a>";
            if($parent_id == 0 ){
                $del=$edit='';
            }
            $str .= "
               {  
                text :\""
                .$val['company_name']
                ."<span style='float:right;'>"
                .$edit." ".$add." ".$del
                ."</span>"."\",";
            $childs=$this::find()->where(['parent_id'=>$val['parent_id']])->select('company_id,company_code,company_name,parent_id')->asArray()->one();
            if ($childs) {
                $str .= "
                            nodes:[";
                $this->getCompanyTree($val['company_id']);
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
}
