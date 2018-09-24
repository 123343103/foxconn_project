<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_veh".
 *
 * @property integer $veh_id
 * @property string $log_code
 * @property string $veh_number
 * @property string $veh_type
 * @property string $veh_brand
 * @property string $person_charge
 * @property string $person_phone
 * @property string $veh_contacts
 * @property string $contacts_phone
 * @property string $veh_color
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $veh_ip
 *
 * @property BsLogCmp $logCode
 */
class BsVeh extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_veh';
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
            [['log_code'], 'required'],
            [['OPP_DATE'], 'safe'],
            [['log_code'], 'string', 'max' => 10],
            [['veh_number', 'veh_brand', 'person_charge', 'person_phone', 'veh_contacts', 'contacts_phone', 'veh_color', 'OPPER'], 'string', 'max' => 50],
            [['veh_type'], 'string', 'max' => 100],
            [['veh_ip'], 'string', 'max' => 255],
            [['log_code'], 'exist', 'skipOnError' => true, 'targetClass' => BsLogCmp::className(), 'targetAttribute' => ['log_code' => 'log_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'veh_id' => '车辆id（自增）',
            'log_code' => '物流公司承運代碼',
            'veh_number' => '车牌号',
            'veh_type' => '车辆类型',
            'veh_brand' => '车辆品牌',
            'person_charge' => '负责人',
            'person_phone' => '负责人电话',
            'veh_contacts' => '联系人',
            'contacts_phone' => '联系人电话',
            'veh_color' => '车辆颜色',
            'OPPER' => '操作人',
            'OPP_DATE' => '操作时间',
            'veh_ip' => '操作人ip地址',
            'veh_status'=>'是否删除（0：否，1：是）',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogCode()
    {
        return $this->hasOne(BsLogCmp::className(), ['log_code' => 'log_code']);
    }

    //根据id查询数据
    public static function getBsVehById($id)
    {
        $data=BsVeh::find()->where(['veh_id'=>$id,'veh_status'=>0])->one();
        return $data;
    }

}
