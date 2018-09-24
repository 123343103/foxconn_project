<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_inv_warn".
 *
 * @property string $inv_warn_PKID
 * @property string $biw_h_pkid
 * @property string $wh_code
 * @property string $part_no
 * @property string $up_nums
 * @property string $down_nums
 * @property string $save_num
 * @property string $remarks
 *
 * @property LInvWarnerH[] $lInvWarnerHs
 */
class LInvWarn extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_inv_warn';
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
            [['biw_h_pkid', 'wh_code'], 'integer'],
            [['up_nums', 'down_nums', 'save_num'], 'number'],
            [['part_no'], 'string', 'max' => 50],
            [['remarks'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'inv_warn_PKID' => 'Inv Warn  Pkid',
            'biw_h_pkid' => 'Biw H Pkid',
            'wh_code' => 'Wh Code',
            'part_no' => 'Part No',
            'up_nums' => 'Up Nums',
            'down_nums' => 'Down Nums',
            'save_num' => 'Save Num',
            'remarks' => 'Remarks',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLInvWarnerHs()
    {
        return $this->hasMany(LInvWarnerH::className(), ['inv_warn_PKID' => 'inv_warn_PKID']);
    }
    public static function getLInvWarnOne($biw_h_pkid,$part_no)
    {
        return self::find()->where(['biw_h_pkid' => $biw_h_pkid,'part_no'=>$part_no])->one();
    }
}
