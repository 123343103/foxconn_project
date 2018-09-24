<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\app\controllers\ProductLibraryController;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCategoryUnit;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\BsMaterial;
use app\modules\ptdt\models\CategoryAttr;
use app\modules\ptdt\models\FpBsCategory;
use Yii;

/**
 * This is the model class for table "inv_changel".
 *
 * @property integer $chl_id
 * @property integer $chh_id
 * @property integer $pdt_id
 * @property string $chl_num
 * @property integer $st_id
 * @property string $chl_bach
 * @property string $chl_barcode
 * @property integer $BRAND_ID
 * @property integer $pdt_id2
 * @property integer $BRAND_ID2
 * @property string $chh_remark
 */
class InvChangel extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_changel';
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
            [['chl_id', 'mode','chh_id', 'st_id', 'st_id2', 'wh_id', 'wh_id2', 'BRAND_ID', 'pdt_id2', 'BRAND_ID2'], 'integer'],
            [['chl_num','before_num1', 'before_num2','deal_price'], 'number'],
            [['chl_bach','chl_bach2', 'chl_barcode'], 'string', 'max' => 30],
            [['chh_remark'], 'string', 'max' => 200],
            [['pdt_no','part_no2'], 'string', 'max' => 20],
        ];
    }

    public function getInvChangeInfo($id)
    {
        return InvChangel::find()->where(['chh_id' => $id])->all();
    }

    /**
     * @return $this
     * 关联仓库表获取仓库名
     */
    public function getWh()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => 'wh_id'])->select('wh_name');
    }
    /**
     * @return $this
     * 关联仓库表获取仓库名
     */
    public function getWhsecond()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => 'wh_id2'])->select('wh_name');
    }

    /**
     * @return $this
     * 出库储位信息
     */
    public function getStore()
    {
        return $this->hasOne(BsSt::className(), ['st_id' => 'st_id'])->select('st_id,st_code');
    }
    /**
     * @return $this
     * 出库储位信息
     */
    public function getStore2()
    {
        return $this->hasOne(BsSt::className(), ['st_id' => 'st_id2'])->select('st_id,st_code');
    }
    /**
     * @return $this
     * 入库库储位信息
     */
//    public function getStore2(){
//        return $this->hasOne(BsSt::className(),['st_id'=> 'st_id2'])->select('st_id,st_code');
//    }
    /*
     * 关联商品表
     */
    public function getMaterial()
    {
        return $this->hasOne(BsMaterial::className(), ['part_no' => 'pdt_no']);
    }

    /*
     * 关联商品类型
     */
    public function getProType()
    {
        return $this->hasOne(BsCategory::className(), ['catg_no' => "category_no"])->via('material')->select('catg_name');
    }

    /*
     *关联商品规格
     */
    public function getAttr()
    {
        return $this->hasOne(CategoryAttr::className(), ['CATEGORY_ATTR_ID' => 'tp_spec'])->via('product')->select('ATTR_NAME');
    }

    /*
     * 关联商品单位
     */
    public function getUnit()
    {
        return $this->hasOne(BsCategoryUnit::className(), ['id' => 'unit'])->via('product')->select('unit_name');
    }

    /*
     * 关联库存数量
     */
//    public function getInvtNum()
//    {
//        return $this->hasOne(LBsInvt::className(), ['pdt_id' => 'pdt_id'])->via('product');
//    }

    public function getInvChangeLInfo()
    {
        return $this->hasMany(InvChangel::className(), ['chh_id' => 'chh_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chl_id' => 'Chl ID',
            'chh_id' => 'Chh ID',
            'pdt_no' => 'Pdt NO',
            'chl_num' => 'Chl Num',
            'st_id' => 'St ID',
            'st_id2' => 'St ID2',
            'wh_id' => 'wh ID',
            'wh_id2' => 'wh ID2',
            'chl_bach' => 'Chl Bach',
            'chl_barcode' => 'Chl Barcode',
            'BRAND_ID' => 'Brand  ID',
            'pdt_id2' => 'Pdt Id2',
            'BRAND_ID2' => 'Brand  Id2',
            'chh_remark' => 'Chh Remark',
            'part_no2'=>'PART_NO2',
            'before_num1' => '異動前料號庫存',
            'before_num2' => '異動前料號2總庫存',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\warehouse\models\search\InvChangelSearch the active query used by this AR class.
     */

}
