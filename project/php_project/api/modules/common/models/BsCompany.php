<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_company".
 * 系统公司模型
 * F3858995
 * 2016.11.3
 * @property integer $company_id
 * @property string $company_code
 * @property string $company_name
 * @property integer $parent_id
 * @property integer $company_status
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 * @property string $comp_shortname
 * @property string $comp_createdate
 * @property string $comp_mainaddress
 * @property string $comp_cman
 * @property string $comp_cman2
 * @property string $comp_tel
 * @property string $comp_orgcode
 * @property string $comp_regcode
 * @property string $comp_bustype
 * @property string $comp_type
 * @property string $comp_regcount
 * @property string $comp_count
 * @property string $comp_regauthority
 * @property string $comp_disid
 * @property string $comp_addrdss
 * @property string $comp_busscope
 */
class BsCompany extends Common
{
    /**
     * @inheritdoc
     */
    const STATUS_DEL = 0;//删除
    const STATUS_DEFAULT  = 10; //默认

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
            [['parent_id', 'company_status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at', 'comp_createdate'], 'safe'],
            [['comp_regcount', 'comp_count'], 'number'],
            [['company_code', 'company_name'], 'string', 'max' => 255],
            [['comp_shortname', 'comp_mainaddress', 'comp_cman', 'comp_cman2', 'comp_tel', 'comp_orgcode', 'comp_regcode', 'comp_bustype', 'comp_type', 'comp_regauthority', 'comp_disid', 'comp_addrdss', 'comp_busscope'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'company_code' => 'Company Code',
            'company_name' => 'Company Name',
            'parent_id' => 'Parent ID',
            'company_status' => 'Company Status',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
            'comp_shortname' => 'Comp Shortname',
            'comp_createdate' => 'Comp Createdate',
            'comp_mainaddress' => 'Comp Mainaddress',
            'comp_cman' => 'Comp Cman',
            'comp_cman2' => 'Comp Cman2',
            'comp_tel' => 'Comp Tel',
            'comp_orgcode' => 'Comp Orgcode',
            'comp_regcode' => 'Comp Regcode',
            'comp_bustype' => 'Comp Bustype',
            'comp_type' => 'Comp Type',
            'comp_regcount' => 'Comp Regcount',
            'comp_count' => 'Comp Count',
            'comp_regauthority' => 'Comp Regauthority',
            'comp_disid' => 'Comp Disid',
            'comp_addrdss' => 'Comp Addrdss',
            'comp_busscope' => 'Comp Busscope',
        ];
    }
    /**
     * 获取ID数组
     * @param $id
     * @return array
     */
    public static function getIdsArr($id){
        static $ids;
        $ids[] = (int)$id;
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
    public static function getCompanyTree($pid = 0)
    {
        static $str = "";
        $tree=self::find()->where(['parent_id' => $pid])->andWhere(['!=','company_status',self::STATUS_DEL])->select('company_id,company_code,company_name,parent_id')->all();
        foreach ($tree as $key => $val){
            $company_id=$val['company_id'];
            $parent_id=$val['parent_id'];

            $view="<a class='' onclick='companyView($company_id);'><i class='icon-search icon-l' title='查看'></i></a>";
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
                .$view." ".$edit." ".$add." ".$del
                ."</span>"."\",";
            $child=self::find()->where(['parent_id'=>$val['company_id']])->andWhere(['company_status'=>10])->one();
            if ($child) {
                $str .= "
                            nodes:[";
                self::getCompanyTree($val['company_id']);
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
