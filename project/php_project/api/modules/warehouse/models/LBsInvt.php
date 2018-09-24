<?php
//库存表
namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_bs_invt".
 *
 * @property string $L_invt_id
 * @property string $comp_id
 * @property string $invt_code
 * @property string $pdt_id
 * @property string $part_no
 * @property string $invt_num
 * @property string $lock_num
 * @property string $invt_date
 */
class LBsInvt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_bs_invt';
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
            [['comp_id', 'invt_code', 'pdt_id'], 'integer'],
            [['invt_num', 'lock_num'], 'number'],
            [['invt_date'], 'safe'],
            [['part_no'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'L_invt_id' => 'L Invt ID',
            'comp_id' => 'Comp ID',
            'invt_code' => 'Invt Code',
            'pdt_id' => 'Pdt ID',
            'part_no' => 'Part No',
            'invt_num' => 'Invt Num',
            'lock_num' => 'Lock Num',
            'invt_date' => 'Invt Date',
        ];
    }
}
