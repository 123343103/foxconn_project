<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "bs_st".
 *
 * @property string $st_id
 * @property string $part_code
 * @property string $st_code
 * @property string $rack_code
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $NWER
 * @property string $NW_DATE
 * @property string $YN
 * @property string $opp_ip
 *
 * @property BsPart $partCode
 */
class BsSt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_st';
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
            [['st_id', 'st_code','wh_id'], 'required'],
            [['OPP_DATE', 'NW_DATE'], 'safe'],
            [['st_id', 'part_code', 'OPPER', 'NWER'], 'string', 'max' => 30],
            [['st_code', 'rack_code'], 'string', 'max' => 10],
            [['remarks'], 'string', 'max' => 200],
            [['YN'], 'string', 'max' => 1],
            [['opp_ip'], 'string', 'max' => 20],
            [['part_code'], 'exist', 'skipOnError' => true, 'targetClass' => BsPart::className(), 'targetAttribute' => ['part_code' => 'part_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'st_id' => '儲位ID',
            'part_code' => '分區代碼，外鍵',
            'st_code' => '儲位碼',
            'rack_code' => '貨架碼',
            'remarks' => '備注',
            'OPPER' => '操作人',
            'OPP_DATE' => '操作時間',
            'NWER' => '創建人',
            'NW_DATE' => '創建時間',
            'YN' => '是否有效。Y：有效',
            'opp_ip' => 'IP地址',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartCode()
    {
        return $this->hasOne(BsPart::className(), ['part_code' => 'part_code']);
    }

    //获取所有数据
    public function getAll()
    {
        $list=self::find()->all();
        return isset($list) ? $list:[];
    }

    //获取仓储码
    public static function getSetCode()
    {
        return self::find()->select('st_id,part_code,st_code,rack_code')->where(['YN'=>'Y'])->asArray()->all();
    }

    //获取储位码
    public static function getBsStcns($st_id)
    {
        return self::find()->select('st_code')->where(['st_id'=>$st_id])->one();
    }
}
