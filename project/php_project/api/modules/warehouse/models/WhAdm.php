<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "wh_adm".
 *
 * @property string $adm_id
 * @property string $wh_code
 * @property string $emp_no
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $opp_ip
 *
 * @property BsWh $whCode
 */
class WhAdm extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wh_adm';
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
//            [['adm_id'], 'required'],
            [['OPP_DATE'], 'safe'],
            [['adm_id', 'wh_code', 'emp_no', 'OPPER'], 'string', 'max' => 30],
            [['opp_ip','adm_phone'], 'string', 'max' => 20],
            [['adm_email'], 'string', 'max' => 50],
//            [['wh_code'], 'exist', 'skipOnError' => true, 'targetClass' => BsWh::className(), 'targetAttribute' => ['wh_code' => 'wh_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'adm_id' => '倉庫管理員ID',
            'wh_code' => '倉庫代碼',
            'emp_no' => '管理員帳號，來源於erp.hr_staff',
            'OPPER' => '操作人',
            'OPP_DATE' => '操作時間',
            'opp_ip' => 'IP地址',
            'adm_phone'=>'电话',
            'adm_email'=>'邮箱',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhCode()
    {
        return $this->hasOne(BsWh::className(), ['wh_code' => 'wh_code']);
    }
    /*
       * 关联BsWh信息表
       */
    public function getBsWh()
    {
        return $this->hasOne(BsWh::className(), ['wh_code' => 'wh_code']);
    }
    /*
     * 关联HrStaff信息表
     */
    public function getHrStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_code' => 'emp_no']);
    }
    /*
     * 获取一条仓库信息
     */
    public static function getBsWhInfoOne($id)
    {
        return self::find()->where(['wh_code' => $id])->one();
    }
}
