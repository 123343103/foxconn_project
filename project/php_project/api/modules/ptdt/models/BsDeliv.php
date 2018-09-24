<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_deliv".
 *
 * @property string $dlv_id
 * @property string $prt_pkid
 * @property integer $item
 * @property integer $country_no
 * @property string $country_name
 * @property integer $province_no
 * @property string $province_name
 * @property integer $city_no
 * @property string $city_name
 */
class BsDeliv extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_deliv';
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
            [['prt_pkid'], 'required'],
            [['prt_pkid', 'item', 'country_no', 'province_no', 'city_no'], 'integer'],
            [['country_name', 'province_name'], 'string', 'max' => 30],
            [['city_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dlv_id' => 'Dlv ID',
            'prt_pkid' => 'Prt Pkid',
            'item' => 'Item',
            'country_no' => 'Country No',
            'country_name' => 'Country Name',
            'province_no' => 'Province No',
            'province_name' => 'Province Name',
            'city_no' => 'City No',
            'city_name' => 'City Name',
        ];
    }

//    public function afterSave($insert, $changedAttributes ){
//        $logModel=new LDeliv();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        $logModel->yn=0;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("收货地日志信息保存失败");
//        }
//        return true;
//    }
    public static  function getDelivCount($id,$provinceno,$cityno)
    {
        return count(self::find()->select('prt_pkid')->where(['prt_pkid'=>$id,'province_no'=>$provinceno,'city_no'=>$cityno])->one());
    }

}
