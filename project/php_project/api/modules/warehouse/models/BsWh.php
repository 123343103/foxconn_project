<?php

namespace app\modules\warehouse\models;

use app\modules\common\models\BsPubdata;
use app\modules\system\models\RUserWhDt;
use Yii;
use app\models\Common;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "bs_wh".
 *
 * @property string $wh_code
 * @property string $wh_name
 * * @property string $people
 * * @property string $company
 * @property integer $DISTRICT_ID
 * @property string $wh_addr
 * @property string $wh_state
 * @property string $wh_type
 * @property string $wh_lev
 * @property string $wh_attr
 * @property string $wh_YN
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $NWER
 * @property string $NW_DATE
 * @property string $wh_YNw
 * @property BsPart[] $bsParts
 * @property BsPrice[] $bsPrices
 * @property WhAdm[] $whAdms
 */
class BsWh extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.bs_wh';
    }
    /*
     * 获取一条仓库信息
     */
    public static function getBsWhInfoOne($id)
    {
        return self::find()->where(['wh_id' => $id])->one();
    }
    /*
     * 根据仓库名称获取仓库信息
     */
    public static function getBsWhInfoByName($name)
    {
        return self::find()
            ->select([self::tableName().".wh_code",
                      self::tableName().".wh_name",
                     ])
            ->where([
                self::tableName().'.wh_name' => $name
            ])->asArray()->one();
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wh_code', 'wh_name', 'people','company','wh_state', 'wh_type', 'wh_lev', 'wh_yn', 'yn_deliv','wh_nature'], 'required'],
            [['district_id','wh_id'], 'integer'],
            [['opp_date', 'nw_date'], 'safe'],
            [['wh_code', 'opper', 'nwer','company'], 'string', 'max' => 30],
            [['wh_name', 'wh_attr', 'remarks','people'], 'string', 'max' => 200],
            [['wh_addr', 'opp_ip'], 'string', 'max' => 20],
//            [['wh_type', 'wh_lev', 'wh_yn','wh_nature'], 'int', 'max' => 20],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wh_code' => '倉庫代碼',
            'wh_name' => '倉庫名稱',
            'people' =>'法人，0.请选择1.鸿富锦精密工业(深圳)有限公司2.富泰华工业(深圳)有限公司',
            'company' =>'创业公司，0.请选择...1.公司一2.公司二3.公司三',
            'district_id' => '倉庫地址代碼.存儲省市區最小的代碼(erp.bs_district)',
            'wh_addr' => '倉庫詳情地址',
            'wh_state' => '倉庫狀態.Y：有效',
            'wh_type' => '倉庫類別，0.其它倉,1.普通倉庫2.恒溫恒濕倉庫3.危化品倉庫4.貴重物品倉庫',
            'wh_lev' => '倉庫級別,0.其它，1.一級，2.二級，3.三級',
            'wh_attr' => '倉庫屬性.N.外租Y.自有',
            'wh_yn' => '是否报废仓：Y.是，N.否',
            'yn_deliv' => '是否自提点：Y.是，N.否',
            'remarks' => '備注',
            'opper' => '操作人',
            'opp_date' => '操作時間',
            'nwer' => '創建人',
            'nw_date' => '創建時間',
            'wh_nature' => 'Y.保稅,N非保稅',
            'opp_ip' => 'IP地址',
            'wh_id' => '倉庫PKID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsParts()
    {
        return $this->hasMany(BsPart::className(), ['wh_code' => 'wh_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsPrices()
    {
        return $this->hasMany(BsPrice::className(), ['wh_code' => 'wh_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhAdms()
    {
        return $this->hasOne(WhAdm::className(), ['wh_code' => 'wh_code']);
    }


    /*
     * 下拉列表数据
     */
//    public static function options($default=[]){
//        //仓库类别
//        $list["wh_type"]=[
//            '0'=>'其它仓库',
//            '1'=>'普通仓库',
//            '2'=>'恒温恒湿仓库',
//            '3'=>'危化品仓库',
//            '4'=>'贵重物品仓库'
//        ];
//        //状态
//        $list["wh_state"]=[
//            "Y"=>"有效",
//            "N"=>"无效"
//        ];
//        //仓库级别
//        $list["wh_lev"]=[
//            "0"=>"其它",
//            "1"=>"一级",
//            "2"=>"二级",
//            "3"=>"三级"
//        ];
//        //仓库属性
//        $list["wh_attr"]=[
//            "N"=>"外租",
//            "Y"=>"自有"
//        ];
//        //创业公司
//        $list["company"]=[
//            "0"=>"请选择",
//            "1"=>"公司一",
//            "2"=>"公司二 ",
//            "3"=>"公司三"
//        ];
//        //法人
//        $list["people"]=[
//            "0"=>"请选择",
//            "1"=>"鸿富锦精密工业(深圳)有限公司",
//            "2"=>"富太华工业(深圳)有限公司"
//        ];
//        $list["wh_YN"]=[
//            "Y"=>"是",
//            "N"=>"否"
//        ];
//        $list["wh_YNw"]=[
//            "Y"=>"是",
//            "N"=>"否"
//            ];
//        if(!empty($default)){
//            foreach($list as &$item){
//                $item=array_merge($default,$item);
//            }
//        }
//        return $list;
//    }

    public function getWhCode($wh_id)
    {
        return self::find()->select('wh_code')->where(['wh_id'=>$wh_id])->one();
    }
    //获取仓库名称
    public function getWhName()
    {
        $list = self::find()->select("wh_name")->groupBy("wh_name")->asArray()->all();
        return isset($list) ? $list:[];
    }

    public static function getWarehouseAll($select='wh_id,wh_code,wh_name'){
        return self::find()->select($select)->where(['wh_state'=>'Y'])->asArray()->all();
    }
    //根據倉庫名稱獲取倉庫代碼
    public static function getWareHouse($code)
    {
        $map = $code;
        $child = self::find()->where(['wh_code'=>$map])->select('wh_addr')->one();
        return $child;
    }
    //獲取倉庫名和id
    public static function getWhid($select='wh_id,wh_name'){
        return self::find()->select($select)->asArray()->all();
    }

    public static function getTree($wh_id,$user_id)
    {
        $whdt = RUserWhDt::find()->andWhere(['user_id' => $user_id])->all();
        if($wh_id==0) {
            $tree = self::find()->andWhere(['wh_state' => 'Y'])->all();
        }
        else
        {
            $tree = self::find()->andWhere(['wh_state' => 'Y','wh_id'=>$wh_id])->all();
        }
        $i = 0;
        static $str = "";
        $str = $str . "[";
        foreach ($tree as $key => $val) {
            if ($i == 0) {
                $i++;
            } else {

                $str = $str . ",";
            }
            $childs = BsPart::find()->where(['wh_code' => $val['wh_code']])->one();

            $str .= "
               {  
                
                \"id\" :\"" . $val['wh_id'] . "\",
                \"text\" :\"" . $val['wh_name'] . "<div style='display:none' class='part_id'></div><div style='display:none' class='wh_id'>" . $val['wh_id'] . "</div><div style='display:none' class='level'>1</div>\",
                \"level\":\"" . $val['wh_id'] . "\",
                \"value\" :\"" . $val['wh_id'] . "\"";

            //判断checkbox有没有选中根据id在r_catg表中查询数据

            foreach ($whdt as $key1 => $val1) {
                if ($val1['wh_pkid'] == $val['wh_id']&!$childs) {
                    $str .= " ,\"checked\" :true";
                }
            }

            if ($childs) {
                $str .= "
                           , \"children\":";
                $str.=self::getTrees($val['wh_code'], $user_id,$val['wh_id'],null);
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

    public static function getTrees($wh_code, $user_id,$wh_id,$part_name)
    {
        $whdt = RUserWhDt::find()->andWhere(['user_id' => $user_id])->all();
        if($part_name==null)
        {
            $tree = BsPart::find()->andWhere(['wh_code' => $wh_code, 'YN' => 1])->all();
        }
        else {
            $tree = BsPart::find()->andWhere(['wh_code' => $wh_code, 'YN' => 1,'part_name'=>$part_name])->all();
        }
        $i = 0;
        $str = "";
        $str = $str . "[";
        foreach ($tree as $key => $val) {
            if ($i == 0) {
                $i++;
            } else {

                $str = $str . ",";
            }
            //加1103只是为了区分id，没有其他的意思
            $str .= "
               {  
                
                \"id\" :\"1103" . $val['part_id'] . "\",
                \"text\" :\"" . $val['part_name'] . "<div style='display:none' class='part_id'>" . $val['part_id'] . "</div><div style='display:none' class='wh_id'>" . $wh_id . "</div><div style='display:none' class='level'>2</div>\",
                \"level\":\"" . $val['part_id'] . "\",
                \"value\" :\"" . $val['part_id'] . "\"";

            //判断checkbox有没有选中根据id在r_catg表中查询数据

            foreach ($whdt as $key1 => $val1) {
                if ($val1['part_id'] == $val['part_id']) {
                    $str .= " ,\"checked\" :true";
                }
            }

                $str .= "}";
        }
        $str .= "]";
        return $str;
    }

    public static function  getList(){
        $list = static::find()->select("wh_id,wh_name")->where(['wh_state'=>'Y'])->asArray()->all();
        return isset($list) ? $list : [];
    }

    //仓库属性

    public static function getWhAttr(){
        return BsPubdata::find()->select("bsp_svalue")->indexBy("bsp_id")->where(['bsp_stype'=>'CKSX'])->asArray()->column();
    }

    //根据仓库id获取仓库编号和名称
    public static function getBsWhcn($wh_id)
    {
        return self::find()->select('wh_code,wh_name')->where(['wh_id'=>$wh_id])->one();
    }

    //根据仓库id获取仓库编号和名称
    public static function getBsWhname($wh_code)
    {
        return self::find()->select('wh_name')->where(['wh_code'=>$wh_code])->one();
    }
}
