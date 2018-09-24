<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "wms_param".
 *
 * @property string $para_id
 * @property string $para_code
 * @property string $para_name
 * @property string $para_type
 * @property string $YN
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 */
class WmsParam extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms_param';
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
            [['para_code', 'para_name', 'para_type', 'YN'], 'required'],
            [['OPP_DATE'], 'safe'],
            [['para_code'], 'string', 'max' => 8],
            [['para_name'], 'string', 'max' => 100],
            [['para_type'], 'string', 'max' => 5],
            [['YN'], 'string', 'max' => 1],
            [['remarks'], 'string', 'max' => 200],
            [['OPPER'], 'string', 'max' => 30],
            [['OPP_IP'], 'string', 'max' => 20],
            [['para_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'para_id' => 'pKID',
            'para_code' => '參數代碼',
            'para_name' => '參數名稱',
            'para_type' => '參數分類',
            'YN' => 'Y有效',
            'remarks' => '備注',
            'OPPER' => '操作人',
            'OPP_DATE' => '操作時間',
            'OPP_IP' => '操作IP',
        ];
    }

    public function getParaCode()
    {
        return $this->hasMany(BsLogCmp::className(), ['log_mode' => 'para_code']);
    }
    public function getParaType()
    {
        return $this->hasMany(BsLogCmp::className(), ['log_type' => 'para_code']);
    }
    //获取运输方式
    public static function getData()
    {
        $list = self::find()->select("para_code,para_name")->where(["para_type"=>"SHIP","YN"=>"Y"])->groupBy("para_code")->asArray()->all();
        return isset($list) ? $list:[];
    }
    //获取公司类型
    public static function getDatas()
    {
        $list = self::find()->select("para_code,para_name")->where(["para_type"=>"CMPTP","YN"=>"Y"])->groupBy("para_code")->asArray()->all();
        return isset($list) ? $list:[];
    }
}
