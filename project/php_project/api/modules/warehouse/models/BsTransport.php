<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_transport".
 *
 * @property string $tran_id
 * @property string $tran_code
 * @property string $tran_sname
 * @property string $tran_othername
 * @property string $F_NODE
 * @property integer $grade
 * @property string $tran_stauts
 * @property integer $create_by
 * @property string $create_date
 * @property integer $update_by
 * @property string $update_date
 */
class BsTransport extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_transport';
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
            [['tran_id'], 'required'],
            [['tran_id', 'F_NODE', 'grade', 'create_by', 'update_by'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['tran_code'], 'string', 'max' => 20],
            [['tran_sname', 'tran_othername'], 'string', 'max' => 60],
            [['tran_stauts'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tran_id' => 'id',
            'tran_code' => '編碼',
            'tran_sname' => '名稱',
            'tran_othername' => '其它名稱',
            'F_NODE' => '父結點',
            'grade' => '等級',
            'tran_stauts' => '狀態.0無效，1有效',
            'create_by' => '創建人',
            'create_date' => '創建時間',
            'update_by' => '修改人',
            'update_date' => '修改時間',
        ];
    }
}
