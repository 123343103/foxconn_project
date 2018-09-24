<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_deliverymethod".
 *
 * @property integer $bdm_id
 * @property string $bdm_code
 * @property string $bdm_sname
 * @property string $bdm_othername
 * @property string $bdm_stauts
 * @property integer $create_by
 * @property string $create_date
 * @property integer $update_by
 * @property string $update_date
 */
class BsDeliverymethod extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_deliverymethod';
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
            [['bdm_id'], 'required'],
            [['bdm_id', 'create_by', 'update_by'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['bdm_code'], 'string', 'max' => 20],
            [['bdm_sname', 'bdm_othername'], 'string', 'max' => 60],
            [['bdm_stauts'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bdm_id' => 'Bdm ID',
            'bdm_code' => 'Bdm Code',
            'bdm_sname' => 'Bdm Sname',
            'bdm_othername' => 'Bdm Othername',
            'bdm_stauts' => 'Bdm Stauts',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
            'update_by' => 'Update By',
            'update_date' => 'Update Date',
        ];
    }
}
