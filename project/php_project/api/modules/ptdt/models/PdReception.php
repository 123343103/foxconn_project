<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "pd_reception".
 *
 * @property string $rece_id
 * @property string $rece_type
 * @property string $h_id
 * @property string $l_id
 * @property string $firm_id
 * @property string $rece_sname
 * @property string $rece_position
 * @property string $rece_tel
 * @property string $rece_mobile
 * @property string $rece_main
 * @property string $rece_description
 * @property string $rece_remark
 */
class PdReception extends Common
{

    const RECE_TYPE_VISIT='1';
    const RECE_TYPE_NEGOTIATION='2';
    const RECE_MAIN_YES=true;
    const RECE_MAIN_NO=false;
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'pd_reception';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['rece_sname','rece_position','rece_mobile'], 'required'],
            [['rece_sname','rece_position','rece_mobile'], 'safe'],
            /*[['rece_id'], 'required'],
            [['rece_id', 'h_id', 'l_id', 'firm_id'], 'integer'],
            [['rece_type', 'rece_main'], 'string', 'max' => 4],
            [['rece_sname', 'rece_position', 'rece_tel', 'rece_mobile'], 'string', 'max' => 20],
            [['rece_description'], 'string', 'max' => 120],
            [['rece_remark'], 'string', 'max' => 200],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rece_id' => 'Rece ID',
            'rece_type' => 'Rece Type',
            'h_id' => 'H ID',
            'l_id' => 'L ID',
            'firm_id' => '厂商ID',
            'rece_sname' => '廠商主談人',
            'rece_position' => '职位',
            'rece_tel' => '接待人电话',
            'rece_mobile' => '聯繫电话',
            'rece_main' => 'Rece Main',
            'rece_description' => 'Rece Description',
            'rece_remark' => 'Rece Remark',
        ];
    }
}
