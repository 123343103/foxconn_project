<?php

namespace app\modules\hr\models;

use app\models\Common;
use app\modules\system\models\RPwrDpt;
use app\modules\system\models\RUserDptDt;

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
class HrOrganization extends Common
{

    //商品開發處 部門代碼
    const PD_DEPARTMENT_CODE = 'THA30008';

    const LEVEL_CLASS = '1';    //课级
    const LEVEL_MINISTERIAL = '2';    //部级
    const LEVEL_CENTER = '3';    //中心
    const LEVEL_MANUFACTURING = '4';    //製造處級
    const LEVEL_PRODUCT = '5';    //產品處級

    const STATUS_DELETE = 0;
    const STATUS_DEFAULT = 10;

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
            [['organization_code', 'organization_name'], 'required'],
            [['organization_description', 'organization_level'], 'string'],
            [['create_date', 'organization_pid', 'organization_state'], 'safe'],
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
    public function getOrgTree($pid = 0, $isIcon = null)
    {
        static $str = "";
        $tree = self::find()->andWhere(['organization_pid' => $pid])->andWhere(['organization_state' => self::STATUS_DEFAULT])
            ->select('organization_id,organization_name,organization_code,organization_pid,organization_level')->all();
        foreach ($tree as $key => $val) {
//            $del_url = \Yii::$app->urlManager->createUrl(['goods/type/delete', 'id' => $val['type_id']]);
//            $add_url = \Yii::$app->urlManager->createUrl(['goods/type/create','level'=>$val['type_level']]);
//            $upd_url = \Yii::$app->urlManager->createUrl(['goods/type/update', 'id' => $val['type_id']]);
            $organization_id = $val['organization_id'];
            $organization_pid = $val['organization_pid'];
            $organization_name = $val['organization_name'];
            $organization_code = $val['organization_code'];


//            }else{
            $add = "<a class=''onclick='orgBelow($organization_id);' ><i class='icon-sitemap icon-l' title='新增下級'></i></a>";
            $edit = "<a class='' onclick='orgUpd($organization_id);' ><i class='icon-edit  icon-l' title='編輯'></i></a>";
            $del = "<a class='' onclick='orgDel($organization_id);' ><i class='icon-trash  icon-l' title='刪除'></i></a>";
            if ($isIcon != null) {
                $add = "<a id='organizationName' class='hiden' >$organization_name</a><a id='organization_code' class='hiden' >$organization_code</a>";
                $edit = "";
                $del = "";
            }
//            }

            if ($organization_pid == 0) {
                $del = $edit = '';
//                $search="<a class='' onclick='orgSearch($organization_id);' ><i class='icon-search icon-l' title='搜索'></i></a>";
            }
            $str .= "
               {  
                text :\""
                . $val['organization_name']
                . "<span style='float:right;'>"
                . $edit . " " . $add . " " . $del
                . "</span>" . "\",";
            $childs = $this::find()->where(['organization_pid' => $val['organization_id']])->andWhere(['organization_state' => self::STATUS_DEFAULT])->select('organization_id,organization_name,organization_code,organization_pid,organization_level')->asArray()->one();
            if ($childs) {
                $str .= "
                            nodes:[";
                $this->getOrgTree($val['organization_id'], $isIcon);
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

    public function getTree($pid = 100, $ass_id)
    {
        $rpwrdpt = RPwrDpt::find()->andWhere(['ass_id' => $ass_id])->all();
        $tree = self::find()->andWhere(['organization_pid' => $pid])->andWhere(['organization_state' => 10])
            ->select('organization_id,organization_name,organization_code,organization_pid,organization_level')->all();
        $i = 0;
        static $str = "";
        $str = $str . "[";
        foreach ($tree as $key => $val) {
            if ($i == 0) {
                $i++;
            } else {

                $str = $str . ",";
            }
            $childs = self::find()->where(['organization_pid' => $val['organization_id']])->one();
            $str .= "
               {  
                
                \"id\" :\"" . $val['organization_level'] . "\",
                \"text\" :\"" . $val['organization_name'] . "<div style='display:none' class='catgid'>" . $val['organization_id'] . "</div><div style='display:none' class='level'>" . $val['organization_level'] . "</div>\",
                \"level\":\"" . $val['organization_level'] . "\",
                \"value\" :\"" . $val['organization_id'] . "\"";

            //判断checkbox有没有选中根据id在r_catg表中查询数据

            foreach ($rpwrdpt as $key1 => $val1) {
                if ($val1['org_id'] == $val['organization_id']) {
                    $str .= " ,\"checked\" :true";
                }
            }

            if ($childs) {
                $str .= "
                           , \"children\":";
                self::getTree($val['organization_id'], $ass_id);
                $str .= "
                            }";
            } else {
                $str .= "
                }
                ";
            }
        }
        $str .= "]";
        return $str;
    }

    //数据权限部门树
    public function getTrees($pid = 100,$userid)
    {
//        $rpwrdpt = RPwrDpt::find()->all();
        $ruserdpt=RUserDptDt::find()->andWhere(['user_id'=>$userid])->all();
        $tree = self::find()->andWhere(['organization_pid' => $pid])->andWhere(['organization_state' => 10])
            ->select('organization_id,organization_name,organization_code,organization_pid,organization_level')->all();
        $i = 0;
        static $str = "";
        $str = $str . "[";
        if(!empty($tree)) {
            foreach ($tree as $key => $val) {
                if ($i == 0) {
                    $i++;
                } else {

                    $str = $str . ",";
                }
                $childs = self::find()->where(['organization_pid' => $val['organization_id']])->one();
                $str .= "
               {  
                
                \"id\" :\"" . $val['organization_level'] . "\",
                \"text\" :\"" . $val['organization_name'] . "<div style='display:none' class='catgid'>" . $val['organization_id'] . "</div><div style='display:none' class='level'>" . $val['organization_level'] . "</div>\",
                \"level\":\"" . $val['organization_level'] . "\",
                \"value\" :\"" . $val['organization_id'] . "\"";

                //判断checkbox有没有选中根据id在r_catg表中查询数据

                if (!empty($ruserdpt)) {
                    foreach ($ruserdpt as $key1 => $val1) {
                        if ($val1['dpt_pkid'] == $val['organization_id']) {
                            $str .= " ,\"checked\" :true";
                        }
                    }
                }

                if ($childs) {
                    $str .= "
                           , \"children\":";
                    self::getTrees($val['organization_id'], $userid);
                    $str .= "
                            }";
                } else {
                    $str .= "
                }
                ";
                }
            }
        }
        else{
            $tree = self::find()->andWhere(['organization_id' => $pid])->andWhere(['organization_state' => 10])
                ->select('organization_id,organization_name,organization_code,organization_pid,organization_level')->all();
            foreach ($tree as $key => $val) {
                if ($i == 0) {
                    $i++;
                } else {

                    $str = $str . ",";
                }
                $str .= "
               {  
                
                \"id\" :\"" . $val['organization_level'] . "\",
                \"text\" :\"" . $val['organization_name'] . "<div style='display:none' class='catgid'>" . $val['organization_id'] . "</div><div style='display:none' class='level'>" . $val['organization_level'] . "</div>\",
                \"level\":\"" . $val['organization_level'] . "\",
                \"value\" :\"" . $val['organization_id'] . "\"";

                //判断checkbox有没有选中根据id在r_catg表中查询数据

                if (!empty($ruserdpt)) {
                    foreach ($ruserdpt as $key1 => $val1) {
                        if ($val1['dpt_pkid'] == $val['organization_id']) {
                            $str .= " ,\"checked\" :true";
                        }
                    }
                }
                    $str .= "}";
            }
        }
        $str .= "]";
        return $str;
    }


    public static function getOrgChild($org_id, &$res = [], $data = "", $level = 1)
    {
        if (!$data) {
            $org = HrOrganization::findOne($org_id);
            $res[] = [
                "organization_code" => $org->organization_code,
                "organization_name" => $org->organization_name,
            ];
            $data = HrOrganization::find()->where(["organization_state" => 10])->asArray()->all();
        }
        foreach ($data as $org) {
            if ($org["organization_pid"] == $org_id) {
                $res[] = [
                    "organization_code" => $org["organization_code"],
                    "organization_name" => str_repeat("&nbsp;&nbsp;", $level) . $org["organization_name"],
                    "level" => $level
                ];
                self::getOrgChild($org["organization_id"], $res, $data, $level + 1);
            }
        }
        return $res;
    }

    public static function getPDvlpOption($code)
    {
        $parent = self::findOne(['organization_code' => $code]);
        $child = self::find()->asArray()->where(['organization_pid' => $parent->organization_id])->select('organization_code,organization_name')->all();
        foreach ($child as $key => $val) {
            $newList[$val['organization_code']] = $val['organization_name'];
        }
        return $newList;
    }

    public static function getOrgAll()
    {
        return self::find()->select('organization_id,organization_code,organization_name,organization_pid,organization_level')->where(['organization_state'=>self::STATUS_DEFAULT])->asArray()->all();
    }

    public function getStaff()
    {
        //同样第一个参数指定关联的子表模型类名
        return $this->hasMany(HrStaff::className(), ['organization_code' => 'organization_code']);
    }

    /**
     * 通过代码获取组织机构信息
     * @param $code
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function byCodeOrg($code)
    {
        return self::find()->where(['organization_code' => $code])->asArray()->one();
    }

    public static function getOrgAllLevel($pid)
    {
        $org = self::find()->select('organization_id,organization_code,organization_name,organization_pid,organization_level')->where(['organization_state'=>self::STATUS_DEFAULT])->all();
        $orgList = self::orgChildren($org, $pid);
        return $orgList;
    }

    public static function orgChildren($arr, $id = 0, $lev = 0)
    {
        $children = array();
        foreach ($arr as $v) {
            if ($v['organization_pid'] == $id) {
                $v['organization_level'] = $lev;
                $children[$v['organization_pid']] = $v;
                $children = array_merge($children, self::orgChildren($arr, $v['organization_id'], $lev + 1));
            }
        }
        return $children;
    }

    public static function getOrgDropList()
    {
        return self::find()->select("organization_name")->indexBy("organization_id")->column();
    }
}
