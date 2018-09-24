<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_inv_warner_h".
 *
 * @property string $LIW_PKID
 * @property string $inv_warn_PKID
 *
 * @property LInvWarner $lIWPK
 * @property LInvWarn $invWarnPK
 */
class LInvWarnerH extends Common
{

    const STAFF_STATUS_DEFAULT = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_inv_warner_h';
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
            [['LIW_PKID', 'inv_warn_PKID'], 'integer'],
            [['LIW_PKID'], 'exist', 'skipOnError' => true, 'targetClass' => LInvWarner::className(), 'targetAttribute' => ['LIW_PKID' => 'LIW_PKID']],
            [['inv_warn_PKID'], 'exist', 'skipOnError' => true, 'targetClass' => LInvWarn::className(), 'targetAttribute' => ['inv_warn_PKID' => 'inv_warn_PKID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LIW_PKID' => 'Liw  Pkid',
            'inv_warn_PKID' => 'Inv Warn  Pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLIWPK()
    {
        return $this->hasOne(LInvWarner::className(), ['LIW_PKID' => 'LIW_PKID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvWarnPK()
    {
        return $this->hasOne(LInvWarn::className(), ['inv_warn_PKID' => 'inv_warn_PKID']);
    }
}
