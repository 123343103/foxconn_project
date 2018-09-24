<?php
/**
 * User: F1677929
 * Date: 2017/7/21
 */
namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_reception".
 *
 * @property string $rece_id
 * @property integer $rece_type
 * @property string $h_id
 * @property string $l_id
 * @property string $cust_id
 * @property string $rece_sname
 * @property string $rece_position
 * @property string $rece_tel
 * @property string $rece_mobile
 * @property string $rece_main
 * @property string $rece_description
 * @property string $rece_remark
 */
class CrmReception extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_reception';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rece_type', 'h_id', 'l_id', 'cust_id'], 'integer'],
            [['rece_sname', 'rece_position', 'rece_tel', 'rece_mobile'], 'string', 'max' => 20],
            [['rece_main'], 'string', 'max' => 4],
            [['rece_description'], 'string', 'max' => 120],
            [['rece_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rece_id' => 'ID',
            'rece_type' => '1拜訪接待2談判接待',
            'h_id' => '關聯履歷表ID',
            'l_id' => '關聯履歷子表ID',
            'cust_id' => '關聯客戶ID',
            'rece_sname' => '關聯???員表',
            'rece_position' => 'Rece Position',
            'rece_tel' => 'Rece Tel',
            'rece_mobile' => 'Rece Mobile',
            'rece_main' => 'Y/N',
            'rece_description' => 'Rece Description',
            'rece_remark' => 'Rece Remark',
        ];
    }
}