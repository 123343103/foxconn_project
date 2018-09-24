<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "ord_status".
 *
 * @property integer $os_id
 * @property string $os_name
 * @property integer $sorts
 * @property integer $yn
 * @property integer $opper
 * @property string $opp_date
 * @property string $remarks
 *
 * @property OrdInfo[] $ordInfos
 */
class OrdStatus extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.ord_status';
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
            [['os_id', 'os_name'], 'required'],
            [['os_id', 'sorts', 'yn', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['os_name'], 'string', 'max' => 50],
            [['remarks'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'os_id' => 'Os ID',
            'os_name' => 'Os Name',
            'sorts' => 'Sorts',
            'yn' => 'Yn',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'remarks' => 'Remarks',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdInfos()
    {
        return $this->hasMany(OrdInfo::className(), ['os_id' => 'os_id']);
    }
}
