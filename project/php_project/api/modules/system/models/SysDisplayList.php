<?php

namespace app\modules\system\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sys_display".
 *
 * @property string $ddi_sid
 * @property string $fuc_webid
 * @property string $ddi_code
 * @property string $ddi_sname
 * @property string $ddi_surl
 * @property string $ddi_status
 * @property string $creator
 * @property string $cdate
 * @property string $editor
 * @property string $edate
 *
 * @property SysDisplayRule[] $sysDisplayRules
 */
class SysDisplayList extends Common
{
    const STATUS_DEFAULT = 10;           //默认

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_display_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fuc_webid'], 'integer'],
            [['ddi_code'], 'required'],
            [['cdate', 'ddi_pid', 'edate', 'creator', 'editor', 'ddi_status'], 'safe'],
            [['ddi_code', 'ddi_sname'], 'string', 'max' => 30],
            [['ddi_surl'], 'string', 'max' => 200],
            [['ddi_status'], 'default', 'value' => self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ddi_sid' => 'Ddi Sid',
            'fuc_webid' => 'Fuc Webid',
            'ddi_code' => 'Ddi Code',
            'ddi_sname' => 'Ddi Sname',
            'ddi_surl' => 'Ddi Surl',
            'ddi_status' => 'Ddi Col Status',
            'creator' => 'Creator',
            'cdate' => 'Cdate',
            'editor' => 'Editor',
            'edate' => 'Edate',
        ];
    }

    public static function getTree($pid = 0)
    {
        static $str = "";
        $tree = self::find()->andWhere(['ddi_pid' => $pid])->all();
        $selected=false;
        foreach ($tree as $key => $val) {
            $childs = self::find()->where(['ddi_pid' => $val['ddi_sid']])->one();
            if(!empty($val['ddi_surl'])) {
                $selected=true;
            }
            $str .= "
               {  
                text :\"". $val['ddi_sname'] . "\",
                id :\"". $val['ddi_sid'] . "\",
                selectable :\"". $selected . "\",
            ";
            if ($childs) {
                $str .= "
                            nodes:[";
                self::getTree($val['ddi_sid']);
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

    //获取动态列
    public static function getColumns($url,$user,$type)
    {
            $display = ['field_display'=>SysDisplayList::STATUS_DEFAULT];
        if(!empty($type)){
            $display=[];
        }else{}

        $mainModel=SysDisplayList::find()->where(['ddi_surl'=>$url])->select('ddi_sid')->one();
        if(empty($mainModel)){
            return '';
        }
        $rules=AuthAssignment::find()->where(['user_id'=>$user])->all();
        foreach ($rules as $key=>$rule){
            $data=SysDisplayRule::find()->where(['ddi_sid'=>$mainModel->ddi_sid])->andWhere(['rule_code'=>$rule])->andWhere($display)->orderBy(['field_index'=>SORT_ASC])->select('field_field,field_title,field_width')->all();
            if(!empty($data)){
                $childModel[$key]=$data;
            }
        }
        if(!empty($childModel)){
            //将三维数组转化为二维数组
            foreach($childModel as $val)
            {
                foreach ($val as $key=>$value){
                    $newArray[] = $value;
                }
            }
//                $as = array_unique($newArray[0]);
            $childModel= self::super_unique($newArray);
        }else{
            $childModel=SysDisplayListChild::find()->where(['ddi_sid'=>$mainModel->ddi_sid])->andWhere($display)->orderBy(['field_index'=>SORT_ASC])->select('field_field,field_title,field_width')->all();
        }

        return $childModel;
//        $columns='';
//        foreach($childModel as $val){
//            $columns.="{field:'".$val->field_field."',title:'".$val->field_title."',width:".$val->field_width."},";
//        }
//        return $columns;
    }

    //多维数组去重
    public static function super_unique($array, $recursion = false){
        // 序列化数组元素,去除重复
        $result = array_map('unserialize', array_unique(array_map('serialize', $array)));
        // 递归调用
        if ($recursion) {
            foreach ($result as $key => $value) {
                if (is_array($value)) {
                    $result[ $key ] = super_unique($value);
                }
            }
        }
        return $result;
    }

}
