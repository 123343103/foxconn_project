<?php

namespace app\modules\spp\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "supplier_cont".
 *
 * @property string $id
 * @property string $spp_id
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property string $fax
 * @property integer $status
 */
class SupplierCont extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_cont';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('spp');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spp_id'], 'required'],
            [['spp_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['mobile', 'fax'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '联系人id',
            'spp_id' => '供应商id(关联spp.bs_supplier.spp_id)',
            'name' => '联系人姓名',
            'mobile' => '联系人电话',
            'email' => '联系人邮箱',
            'fax' => '联系人传真',
            'status' => '状态',
        ];
    }
}