<?php

namespace app\modules\warehouse\models;

use app\behaviors\FormCodeBehavior;
use app\modules\common\Common;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "wms.pdt_Inventory".
 *
 * @property string $ivt_id
 * @property string $ivt_code
 * @property string $legal_code
 * @property string $stage
 * @property integer $curency_id
 * @property string $wh_code
 * @property string $expiry_date
 * @property integer $first_ivtor
 * @property string $first_date
 * @property integer $re_ivtor
 * @property string $re_date
 * @property string $operate_ip
 * @property string $ivt_status
 * @property string $sbr_no
 * @property string $stt_date
 * @property integer $stter
 */
class PdtInventory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const REQUEST_STATUS_STATUS=5;
    public static function tableName()
    {
        return 'pdt_inventory';
    }

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
            [['ivt_code'], 'required'],
            [['curency_id', 'first_ivtor', 're_ivtor', 'stter'], 'integer'],
            [['expiry_date', 'first_date', 're_date', 'stt_date'], 'safe'],
            [['ivt_code', 'legal_code', 'wh_code'], 'string', 'max' => 30],
            [['stage', 'operate_ip', 'sbr_no'], 'string', 'max' => 20],
            [['ivt_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ivt_id' => 'Ivt ID',
            'ivt_code' => 'Ivt Code',
            'legal_code' => 'Legal Code',
            'stage' => 'Stage',
            'curency_id' => 'Curency ID',
            'wh_code' => 'Wh Code',
            'expiry_date' => 'Expiry Date',
            'first_ivtor' => 'First Ivtor',
            'first_date' => 'First Date',
            're_ivtor' => 'Re Ivtor',
            're_date' => 'Re Date',
            'operate_ip' => 'Operate Ip',
            'ivt_status' => 'Ivt Status',
            'sbr_no' => 'Sbr No',
            'stt_date' => 'Stt Date',
            'stter' => 'Stter',
        ];
    }
    public static function getPdtInventoryInfoOne($id)
    {
        return self::find()->where(['ivt_id' => $id])->one();
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'ivt_code',
                "formName" => self::tableName(),
                "model" => $this,
            ],
//            'timeStamp' => [
//                'class' => TimestampBehavior::className(),    //時間字段自動賦值
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['opp_date'],            //插入
////                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['opp_date']            //更新
//                ]
//            ],
        ];
    }
}
