<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\system\models\RUserCtgDt;
use Yii;

/**
 * This is the model class for table "bs_category".
 *
 * @property string $catg_id
 * @property string $catg_no
 * @property string $catg_name
 * @property integer $catg_level
 * @property string $p_catg_id
 * @property integer $orderby
 * @property integer $isvalid
 * @property integer $yn_machine
 * @property string $crt_date
 * @property string $crter
 * @property string $crt_ip
 * @property string $opp_date
 * @property string $opper
 * @property string $opp_ip
 *
 * @property BsCatgAttr[] $bsCatgAttrs
 * @property BsProduct[] $bsProducts
 */
class BsCategory extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdt.bs_category';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catg_no', 'catg_name', 'catg_level', 'p_catg_id', 'isvalid'], 'required'],
            [['catg_level', 'p_catg_id', 'orderby', 'isvalid', 'yn_machine', 'crter', 'opper'], 'integer'],
            [['crt_date', 'opp_date'], 'safe'],
            [['catg_no'], 'string', 'max' => 20],
            [['catg_name'], 'string', 'max' => 100],
            [['crt_ip', 'opp_ip'], 'string', 'max' => 16],
            [['catg_no'], 'unique'],
            [['catg_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'catg_id' => 'Catg ID',
            'catg_no' => 'Catg No',
            'catg_name' => 'Catg Name',
            'catg_level' => 'Catg Level',
            'p_catg_id' => 'P Catg ID',
            'orderby' => 'Orderby',
            'isvalid' => 'Isvalid',
            'yn_machine' => 'Yn Machine',
            'crt_date' => 'Crt Date',
            'crter' => 'Crter',
            'crt_ip' => 'Crt Ip',
            'opp_date' => 'Opp Date',
            'opper' => 'Opper',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsCatgAttrs()
    {
        return $this->hasMany(BsCatgAttr::className(), ['catg_id' => 'catg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsProducts()
    {
        return $this->hasMany(BsProduct::className(), ['catg_id' => 'catg_id']);
    }

    /**
     * 獲取一階分類
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLevelOne($where = [], $select = 'catg_id,catg_name,catg_no')
    {
        return static::find()->where($where)->select($select)->orderBy('orderby')->andWhere(['catg_level' => 1, 'isvalid' => 1])->all();
    }

    /**
     * 獲取子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getChildrenByParentId($id, $select = 'catg_id,catg_name,p_catg_id,catg_no')
    {
        return static::find()->select($select)->asArray(true)->where(['p_catg_id' => $id])->all();
    }

    /*查找所有类别名称*/
    public static function getCatenameAll($select = 'catg_id,catg_name,catg_no')
    {
        return self::find()->select($select)->where(['catg_level' => 1])->asArray()->all();
    }

    //获取上级类别名称
    public static function getPcatgname($catg_level, $select = 'catg_id,catg_name,catg_level,catg_no,p_catg_id', $asArray = true)
    {
        return static::find()->select($select)->asArray($asArray)->orderBy('catg_id')->where(['catg_level' => $catg_level])->all();
    }

    //获取最大的排序编号
    public static function getOrderbyno($p_catg_id, $select = 'max(orderby)orderby')
    {
        return static::find()->select($select)->where(['p_catg_id' => $p_catg_id])->all();
    }

    public function getParent()
    {
        return $this->hasOne(BsCategory::className(), ["catg_id" => "p_catg_id"]);
    }

//    //判断类别编码是否唯一
//    public static function getCheckCatgNoOne($no){
//        return self::find()->where(['catg_no' => $no])->one();
//    }
    //类别关联
    public static function getTree($pid = 0, $catgid)
    {
        $Rcatg = RCatg::find()->andWhere(['catg_id' => $catgid])->all();
        $tree = self::find()->andWhere(['p_catg_id' => $pid])->all();
        $selected = false;
        $i = 0;
        $check = 0;
        static $str = "";
        $str = $str . "[";
        foreach ($tree as $key => $val) {
            if ($i == 0) {
                $i++;
            } else {

                $str = $str . ",";
            }
            $childs = self::find()->where(['p_catg_id' => $val['catg_id']])->one();

            $str .= "
               {  
                
                \"id\" :\"" . $val['catg_level'] . "\",
                \"text\" :\"" . $val['catg_name'] . "<div style='display:none' class='catgid'>" . $val['catg_id'] . "</div><div style='display:none' class='level'>" . $val['catg_level'] . "</div>\",
                \"level\":\"" . $val['catg_level'] . "\",
                \"value\" :\"" . $val['catg_id'] . "\"";

            //判断checkbox有没有选中根据id在r_catg表中查询数据

            foreach ($Rcatg as $key1 => $val1) {
                if ($val1['catg_r_id'] == $val['catg_id']) {
                    $str .= " ,\"checked\" :true";
                }
            }
//            if ($val['catg_id'] == "43418775" || $val['catg_id'] == "43418771") {
//                $str .= " ,\"checked\" :true";
//            }

            if ($childs && $val['catg_level'] != 3) {
                $str .= "
                           , \"children\":";
                self::getTree($val['catg_id'], $catgid);
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

    //商品权限设置(查询)
    public static function getTrees($pid = 0, $user)
    {
        $RUserCtg = RUserCtgDt::find()->andWhere(['user_id' => $user])->all();
        $tree = self::find()->andWhere(['p_catg_id' => $pid])->all();
        $i = 0;
        static $str = "";
        $str = $str . "[";
        if(empty($tree)) {
            $tree = self::find()->andWhere(['catg_id' => $pid])->all();
            foreach ($tree as $key => $val) {
                if ($i == 0) {
                    $i++;
                } else {

                    $str = $str . ",";
                }
                $childs = self::find()->where(['p_catg_id' => $val['catg_id']])->one();

                $str .= "
               {  
                
                \"id\" :\"" . $val['catg_level'] . "\",
                \"text\" :\"" . $val['catg_name'] . "<div style='display:none' class='catgid'>" . $val['catg_id'] . "</div><div style='display:none' class='level'>" . $val['catg_level'] . "</div>\",
                \"level\":\"" . $val['catg_level'] . "\",
                \"value\" :\"" . $val['catg_id'] . "\"";

                //判断checkbox有没有选中根据id在r_catg表中查询数据

                if (!empty($RUserCtg)) {
                    foreach ($RUserCtg as $key1 => $val1) {
                        if ($val1['ctg_pkid'] == $val['catg_id']) {
                            $str .= " ,\"checked\" :true";
                        }
                    }
                }
//            if ($val['catg_id'] == "43418775" || $val['catg_id'] == "43418771") {
//                $str .= " ,\"checked\" :true";
//            }

                if ($childs&&$val['catg_level']<3) {
                    $str .= "
                           , \"children\":";
                    self::getTrees($val['catg_id'], $user);
                    $str .= "
                            }";
                } else {
                    $str .= "
                }
                ";
                }
            }
        }
        else
        {
            foreach ($tree as $key => $val) {
                if ($i == 0) {
                    $i++;
                } else {

                    $str = $str . ",";
                }
                $childs = self::find()->where(['p_catg_id' => $val['catg_id']])->one();

                $str .= "
               {  
                
                \"id\" :\"" . $val['catg_level'] . "\",
                \"text\" :\"" . $val['catg_name'] . "<div style='display:none' class='catgid'>" . $val['catg_id'] . "</div><div style='display:none' class='level'>" . $val['catg_level'] . "</div>\",
                \"level\":\"" . $val['catg_level'] . "\",
                \"value\" :\"" . $val['catg_id'] . "\"";

                //判断checkbox有没有选中根据id在r_catg表中查询数据

                if (!empty($RUserCtg)) {
                    foreach ($RUserCtg as $key1 => $val1) {
                        if ($val1['ctg_pkid'] == $val['catg_id']) {
                            $str .= " ,\"checked\" :true";
                        }
                    }
                }
//            if ($val['catg_id'] == "43418775" || $val['catg_id'] == "43418771") {
//                $str .= " ,\"checked\" :true";
//            }

                if ($childs&&$val['catg_level']<3) {
                    $str .= "
                           , \"children\":";
                    self::getTrees($val['catg_id'], $user);
                    $str .= "
                            }";
                } else {
                    $str .= "
                }
                ";
                }
            }
        }
        $str .= "]";
        return $str;
    }

    // 导入获取类别ID
    public static function getExcelData($type)
    {
        $list = static::find()->select('catg_id')->where(['catg_name' => $type])->one();
        return $list;
    }


}
