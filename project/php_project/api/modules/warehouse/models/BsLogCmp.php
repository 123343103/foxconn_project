<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "bs_log_cmp".
 *
 * @property string $log_cmp_id
 * @property string $log_code
 * @property string $log_cmp_name
 * @property string $log_cmp_EN
 * @property string $log_mode
 * @property string $log_type
 * @property string $log_scope
 * @property string $log_url
 * @property string $log_addr
 * @property string $log_cont
 * @property string $log_cont_pho
 * @property string $log_cont_mail
 * @property string $log_char
 * @property string $log_char_phone
 * @property string $log_char_mail
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 *
 * @property BsVeh[] $bsVehs
 */
class BsLogCmp extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_log_cmp';
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
            [['log_code', 'log_mode', 'log_type', 'log_scope'], 'string', 'max' => 10],
            [['log_cmp_name', 'log_cmp_EN', 'log_url', 'log_cont_mail', 'log_char_mail'], 'string', 'max' => 100],
            [['log_addr'], 'string', 'max' => 250],
            [['log_cont', 'log_char', 'OPP_IP'], 'string', 'max' => 20],
            [['log_cont_pho', 'log_char_phone'], 'string', 'max' => 11],
            [['remarks'], 'string', 'max' => 200],
            [['OPPER'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_cmp_id' => '物流公司ID',
            'log_code' => '承運代碼',
            'log_cmp_name' => '物流公司中文名稱',
            'log_cmp_EN' => '物流公司英文名稱',
            'log_mode' => '運輸方式，來源WMS_PARAM.para_type=&#039;SHIP&#039;',
            'log_type' => '公司類型,WMS_PARAM.para_type=&#039;CMPTP&#039;',
            'log_scope' => '經營范圍',
            'log_url' => '公司網址',
            'log_addr' => '公司地址',
            'log_cont' => '聯系人',
            'log_cont_pho' => '聯系人手機',
            'log_cont_mail' => '聯系人郵箱',
            'log_char' => '負責人',
            'log_char_phone' => '負責人手機',
            'log_char_mail' => '負責人郵箱',
            'remarks' => '備注',
            'OPPER' => '操作人',
            'OPP_DATE' => '操作時間',
            'OPP_IP' => '操作IP',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsVehs()
    {
        return $this->hasMany(BsVeh::className(), ['log_code' => 'log_code']);
    }

    public function getLogMode()
    {
        return $this->hasOne(WmsParam::className(), ['para_code' => 'log_mode']);
    }
    public function getLogType()
    {
        return $this->hasOne(WmsParam::className(), ['para_code' => 'log_type']);
    }
    //获取公司名称
    public static function getData()
    {
        $list = self::find()->select("log_cmp_name,log_code")->andWhere(['log_status'=>0])->groupBy("log_cmp_name")->asArray()->all();
        return isset($list) ? $list:[];
    }

    //获取公司类型
    public static function getDatas()
    {
        $list = self::find()->select("log_type")->andWhere(['log_status'=>0])->groupBy("log_type")->asArray()->all();
        return isset($list) ? $list:[];
    }
}
