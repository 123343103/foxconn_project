<?php

/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/13
 * Time: 下午 01:48
 */
namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\common\models\BsCategoryUnit;
use app\modules\common\models\BsProduct;
use Yii;

//class BsInvWarn extends Common
//{
//    public static function tableName()
//    {
//        return 'bs_inv_warn';
//    }
//
//
//    public function getFullname()
//    {
//        return $this->wh_name;
//    }
//
//    public static function getDb()
//    {
//        return Yii::$app->get('db2');
//    }
//
//
//    public function rules()
//    {
//        return [
//            [['inv_id'], 'required'],
//            [['up_nums', 'down_nums', 'save_num'], 'number'],
//            [['OPP_DATE'], 'safe'],
//            [['inv_id', 'OPP_IP'], 'string', 'max' => 20],
//            [['wh_code', 'so_nbr', 'OPPER'], 'string', 'max' => 30],
//            [['part_no'], 'string', 'max' => 50],
//            [['remarks'], 'string', 'max' => 200],
//        ];
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function attributeLabels()
//    {
//        return [
//            'inv_id' => 'Inv ID',
//            'wh_code' => 'Wh Code',
//            'part_no' => 'Part No',
//            'up_nums' => 'Up Nums',
//            'down_nums' => 'Down Nums',
//            'save_num' => 'Save Num',
//            'so_nbr' => 'So Nbr',
//            'remarks' => 'Remarks',
//            'OPPER' => 'Opper',
//            'OPP_DATE' => 'Opp  Date',
//            'OPP_IP' => 'Opp  Ip',
//        ];
//    }
//    //查詢所有的倉庫名稱
//    public static function getBsWhInfoOne($id)
//    {
//        return self::find()->where(['wh_code' => $id])->one();
//    }
//
////    /*
////      *
//////      */
//    public function getBsProduct()
//    {
//        return $this->hasOne(BsProduct::className(), ['pdt_no' => 'part_no'])->where(['status'=>1,'is_valid'=>1]);
//    }
//
//    public function getBsWh()
//    {
//        return $this->hasOne(BsWh::className(), ['wh_code' => 'wh_code']);
//    }
//
//    public function getBsInvt()
//    {
//        return $this->hasOne(BsInvt::className(),['invt_code' => 'wh_code','part_no' => 'part_no']);
//
//    }
//
////    public function getBsCategoryUnit()
////    {
////        return $this->hasOne(BsProduct::className(), ['pdt_no' => 'part_no'])->where(['status'=>1,'is_valid'=>1])->leftJoin(BsCategoryUnit::tableName(), BsCategoryUnit::tableName(). ".id=" . BsProduct::className() . ".unit");
////    }
////->leftJoin(BsCategoryUnit::tableName(), BsCategoryUnit::tableName(). ".id=" . BsProduct::className() . ".unit")
//
//    public static function getBsInvWarnOne($part_no,$wh_code)
//    {
//        return self::find()->where(['wh_code' => $wh_code,'part_no'=>$part_no])->one();
//    }
//
//
//
//}


class BsInvWarn extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_inv_warn';
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
            [['biw_h_pkid', 'st_id'], 'integer'],
            [['up_nums', 'down_nums', 'save_num'], 'number'],
            [['part_no'], 'string', 'max' => 50],
            [['remarks'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inv_warn_PKID' => 'Inv Warn  Pkid',
            'biw_h_pkid' => 'Biw H Pkid',
            'st_id' => 'St ID',
            'part_no' => 'Part No',
            'up_nums' => 'Up Nums',
            'down_nums' => 'Down Nums',
            'save_num' => 'Save Num',
            'remarks' => 'Remarks',
        ];
    }
    public static function getBsInvWarnOne($biw_h_pkid,$part_no)
    {
        return self::find()->where(['biw_h_pkid' => $biw_h_pkid,'part_no'=>$part_no])->one();
    }


}
