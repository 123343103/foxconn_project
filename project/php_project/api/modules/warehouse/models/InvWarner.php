<?php

namespace app\modules\warehouse\models;

//use app\modules\hr\models\HrStaff;
use app\models\Common;
use Yii;

/**
 * This is the model class for table "inv_warner".
 *
 * @property string $LIW_PKID
 * @property string $staff_code
 * @property integer $YN
 * @property integer $so_type
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 */
class InvWarner extends Common
{
    /**
     * @inheritdoc
     */
    const  VALID=1;//有效
    const UNVALID=0;//无效
    const STATUS_ADD = 10;    //待提交
    const STATUS_WAIT = 20; //审核中
    const STATUS_PENDING = 40;//审核完成
    const STATUS_PREPARE = 50; //驳回
    public static function tableName()
    {
        return 'inv_warner';
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
            [['YN', 'so_type'], 'integer'],
            [['OPP_DATE'], 'safe'],
            [['staff_code', 'OPPER'], 'string', 'max' => 30],
            [['remarks'], 'string', 'max' => 255],
            [['OPP_IP'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LIW_PKID' => 'Liw  Pkid',
            'staff_code' => 'Staff Code',
            'YN' => 'Yn',
            'so_type' => 'So Type',
            'remarks' => 'Remarks',
            'OPPER' => 'Opper',
            'OPP_DATE' => 'Opp  Date',
            'OPP_IP' => 'Opp  Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getInv()
//    {
//        return $this->hasOne(BsInvWarn::className(), ['inv_id' => 'inv_id']);
//    }

}
