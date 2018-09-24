<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "ord_log".
 *
 * @property string $ord_id
 * @property string $os_id
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 */
class OrdLog extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_log';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ord_id'], 'required'],
            [['ord_id', 'os_id'], 'integer'],
            [['opp_date'], 'safe'],
            [['opper'], 'string', 'max' => 30],
            [['opp_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ord_id' => 'Ord ID',
            'os_id' => 'Os ID',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }
}
