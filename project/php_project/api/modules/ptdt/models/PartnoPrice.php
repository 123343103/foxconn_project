<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/25
 * Time: 上午 10:49
 */

namespace app\modules\ptdt\models;


use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsProduct;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\show\BsProductShow;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\FpPas;
use app\modules\ptdt\models\FpBsCategory;
use app\modules\ptdt\models\PdSupplier;
use yii\db\BaseActiveRecord;
//商品定价模型
class PartnoPrice extends Common
{
    public static function tableName()
    {
        return 'partno_price';
    }

    public function behaviors()
    {
        return [

            "formCode" => [
                "class"=>FormCodeBehavior::className(),
                "codeField"=>'price_no',
                "formName"=>self::tableName(),
                'model'=>$this
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['creatdate']  //插入
                ]
            ],
        ];
    }

    /**
     * 获取料号价格信息
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPricesByPartNO($id, $where = [], $asArray = true)
    {
        return static::find()->asArray($asArray)->orderBy('part_no')->where($where)->andWhere(['part_no' => $id])->all();
    }

    /**
     * 关联料号
     * @return \yii\db\ActiveQuery
     */

    //定价产品
    public function getProduct()
    {
        return $this->hasOne(BsProductShow::className(), ['pdt_no' => 'part_no']);
    }

    //定价产品供应商信息
    public function getPdSupplier(){
        return $this->hasOne(PdSupplier::className(), ['supplier_code' => 'supplier_code']);
    }

    //定价产品类型
    public function getProductType()
    {
        return $this->hasOne(FpBsCategory::className(), ['category_id' => "category_id"]);
    }

    //定价核价信息
    public function getPas(){
        return $this->hasMany(FpPas::className(),['part_no'=>'part_no']);
    }

    //定价创建人信息
    public function getCreator(){
        return $this->hasOne(HrStaff::className(),['staff_code'=>'creatby']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_no'], 'required'],
            [['price_no', 'status', 'pdt_manager', 'price_type', 'price_from', 'iskz', 'isproxy', 'isonlinesell', 'risk_level', 'market_price', 'valid_date'
                , 'istitle', 'archrival', 'pdt_level', 'isto_xs', 'so_nbr','usefor', 'packagespc', 'isrelation', 'p_flag', 'supplier_code', 'expiration_date'
                , 'delivery_address', 'payment_terms', 'trading_terms', 'min_price', 'ws_upper_price', 'ws_lower_price', 'gross_profit', 'gross_profit_margin'
                , 'pre_tax_profit', 'pre_tax_profit_rate', 'after_tax_profit', 'after_tax_profit_margin', 'num_area', 'upper_limit_profit', 'lower_limit_profit'
                , 'upper_limit_profit_margin', 'lower_limit_profit_margin',  'pre_ws_lower_price', 'price_fd', 'creatby', 'remark', 'verifydate', 'pasid'
                , 'creatdate', 'min_order', 'currency', 'limit_day', 'pasquno','pre_verifydate', 'supplier_name_shot'
                , 'isvalid', 'no_xs_cause', 'tariff', 'customer_type','buy_price'], 'safe']
        ];
    }

}