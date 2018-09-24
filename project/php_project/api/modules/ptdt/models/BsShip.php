<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_ship".
 *
 * @property string $ship_id
 * @property string $prt_pkid
 * @property integer $item
 * @property integer $country_no
 * @property string $country_name
 * @property integer $province_no
 * @property string $province_name
 * @property integer $city_no
 * @property string $city_name
 */
class BsShip extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_ship';
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
            'ship_id' => 'Ship ID',
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
//        $logModel=new LShip();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        $logModel->yn=0;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("发货地日志信息保存失败");
//        }
//        return true;
//    }

    public static function getCountShipinfo($id, $provinceno)
    {
        return self::find()->select('prt_pkid')->where(['prt_pkid' => $id, 'province_no' => $provinceno])->all();
    }
//获取发货地区信息
    public static function getShipInfo($id, $provinceno)
    {
        $countship = self::getCountShipinfo($id, $provinceno);
        $queryParams=[];
        $queryParams[':id'] = $id;
        $sql = "select t.prt_pkid,
t.country_no,
t.province_no,
t.province_name,
t.city_no,
t.city_name 
from bs_ship t where t.prt_pkid=:id";
        if (count($countship) > 0) {
            $queryParams[':province_no'] = $provinceno;
            $sql .= " and t.province_no=:province_no";
        }
        return  self::getDb()->createCommand($sql,$queryParams)->queryAll();
    }
}
