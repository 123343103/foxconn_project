<?php

namespace app\modules\warehouse\models;

use app\modules\common\models\BsPubdata;
use Yii;

/**
 * This is the model class for table "ic_inv_costh".
 *
 * @property integer $invch_id
 * @property integer $invh_id
 * @property string $audit_status
 * @property integer $organization_id
 * @property integer $create_by
 * @property string $create_at
 * @property integer $comfid
 * @property string $comfdate
 */
class IcInvCosth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_ADD = 2;    //待提交
    const STATUS_WAIT = 0; //审核中
    const STATUS_PENDING = 1;//审核完成
    const STATUS_PREPARE = -1; //驳回
    const VERIFY_CODE = 'whprice'; //审核business_code
    public static function tableName()
    {
        return 'wms.ic_inv_costh';
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
            [['invh_id', 'organization_id', 'create_by', 'comfid'], 'integer'],
            [['create_at', 'comfdate'], 'safe'],
            [['audit_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invch_id' => 'Invch ID',
            'invh_id' => 'Invh ID',
            'audit_status' => 'Audit Status',
            'organization_id' => 'Organization ID',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'comfid' => 'Comfid',
            'comfdate' => 'Comfdate',
        ];
    }

    //获取状态
    public static function getStatus()
    {
        return [
            self::STATUS_ADD=>'待提交',
            self::STATUS_WAIT=>'审核中',
            self::STATUS_PENDING=>'审核完成',
            self::STATUS_PREPARE=>'驳回'
        ];
    }

    public static function getOutType(){
        return BsPubdata::find()->select("bsp_svalue")->indexBy("bsp_id")->where(['bsp_stype'=>'QTCKTYPE'])->asArray()->column();
    }
}
