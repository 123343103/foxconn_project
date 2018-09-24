<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_inv_warner".
 *
 * @property string $LIW_PKID
 * @property string $staff_code
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 *
 * @property LInvWarnerH[] $lInvWarnerHs
 */
class LInvWarner extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_inv_warner';
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
            [['LIW_PKID', 'staff_code'], 'required'],
            [['LIW_PKID'], 'integer'],
            [['OPP_DATE'], 'safe'],
            [['staff_code', 'OPPER'], 'string', 'max' => 30],
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
            'OPPER' => 'Opper',
            'OPP_DATE' => 'Opp  Date',
            'OPP_IP' => 'Opp  Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLInvWarnerHs()
    {
        return $this->hasMany(LInvWarnerH::className(), ['LIW_PKID' => 'LIW_PKID']);
    }
}
