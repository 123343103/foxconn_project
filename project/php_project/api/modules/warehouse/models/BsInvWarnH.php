<?php

namespace app\modules\warehouse\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "bs_inv_warn_h".
 *
 * @property string $biw_h_pkid
 * @property string $inv_id
 * @property string $wh_id
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 * @property integer $so_type
 * @property integer $YN
 */
class BsInvWarnH extends Common
{
    /**
     * @inheritdoc
     */


    const STATUS_CHECKING=10; //审核中
    const STATUS_FINISH=20; //审核完成
    const STATUS_PREPARE = 30;//駁回

    public static function tableName()
    {
        return 'bs_inv_warn_h';
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
            [['inv_id', 'wh_id'], 'required'],
            [['wh_id', 'so_type', 'YN'], 'integer'],
            [['OPP_DATE'], 'safe'],
            [['inv_id', 'OPPER'], 'string', 'max' => 30],
            [['remarks'], 'string', 'max' => 200],
            [['OPP_IP'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'biw_h_pkid' => 'Biw H Pkid',
            'inv_id' => 'Inv ID',
            'wh_id' => 'Wh ID',
            'remarks' => 'Remarks',
            'OPPER' => 'Opper',
            'OPP_DATE' => 'Opp  Date',
            'OPP_IP' => 'Opp  Ip',
            'so_type' => 'So Type',
            'YN' => 'Yn',
        ];
    }
    //行为
    public function behaviors()
    {
        return [
//            'timeStamp'=>[
//                'class'=>TimestampBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT=>['cdate'],
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE=>['udate']
//                ]
//            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),
                'codeField'=>'inv_id',
                'formName'=>self::tableName(),
                'model'=>$this
            ]
        ];
    }

    public function getBsWh()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => 'wh_id']);
    }
    public function getStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_code' => "OPPER"]);
    }



}
