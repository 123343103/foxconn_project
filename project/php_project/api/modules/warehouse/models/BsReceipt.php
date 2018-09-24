<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "bs_receipt".
 *
 * @property string $rcp_id
 * @property string $rcp_no
 * @property string $rcp_name
 * @property string $contact
 * @property string $contact_tel
 * @property string $contact_email
 * @property string $rcp_status
 * @property integer $district_id
 * @property string $addr
 * @property string $remarks
 * @property string $creator
 * @property string $creat_date
 * @property string $operator
 * @property string $operate_date
 * @property string $operate_ip
 */
class BsReceipt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_receipt';
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
            [['rcp_no', 'rcp_name', 'contact', 'contact_tel', 'contact_email', 'rcp_status', 'district_id', 'addr', 'creator', 'creat_date', 'operate_ip'], 'required'],
            [['district_id', 'creator', 'operator'], 'integer'],
            [['creat_date', 'operate_date'], 'safe'],
            [['rcp_no', 'rcp_name', 'contact', 'contact_tel'], 'string', 'max' => 30],
            [['contact_email', 'addr'], 'string', 'max' => 100],
            [['rcp_status'], 'string', 'max' => 1],
            [['remarks'], 'string', 'max' => 200],
            [['operate_ip'], 'string', 'max' => 20],
            ['rcp_name', 'unique', 'message'=>'已存在']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rcp_id' => '收貨中心ID',
            'rcp_no' => '收貨中心編碼',
            'rcp_name' => '收貨中心名稱',
            'contact' => '聯繫人',
            'contact_tel' => '聯繫方式',
            'contact_email' => '郵箱',
            'rcp_status' => '收貨中心狀態（Y：啟用；N:禁用）',
            'district_id' => '地址ID（存最後一級ID）',
            'addr' => '詳細地址',
            'remarks' => '備註',
            'creator' => '創建人',
            'creat_date' => '創建時間',
            'operator' => '操作人',
            'operate_date' => '操作時間',
            'operate_ip' => '操作IP',
        ];
    }
}
