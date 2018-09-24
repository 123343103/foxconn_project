<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_media_count_child".
 *
 * @property integer $medic_id
 * @property integer $medicc_id
 * @property integer $cmt_id
 * @property string $medicc_porjects
 * @property string $medicc_srdate
 * @property string $medicc_srtype
 * @property string $medicc_srcycle
 * @property string $medicc_srcost
 * @property integer $medicc_currid
 * @property string $medicc_expectqty
 * @property string $medicc_clickrate
 * @property string $medicc_uv
 * @property string $medicc_realqty
 * @property string $medicc_realclickrate
 * @property string $medicc_realuv
 * @property string $medicc_avgclickrate
 * @property integer $medicc_400tel
 * @property integer $medicc_tq
 * @property integer $medicc_rememqty
 * @property string $medicc_cpa
 * @property string $medicc_remark
 *
 * @property CrmMediaCount $medic
 */
class CrmMediaCountChild extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_media_count_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medic_id', 'medicc_id', 'cmt_id', 'medicc_currid', 'medicc_400tel', 'medicc_tq', 'medicc_rememqty'], 'integer'],
            [['medicc_srcost', 'medicc_expectqty', 'medicc_clickrate', 'medicc_uv', 'medicc_realqty', 'medicc_realclickrate', 'medicc_realuv', 'medicc_avgclickrate', 'medicc_cpa'], 'number'],
            [['medicc_porjects', 'medicc_srdate', 'medicc_srtype', 'medicc_srcycle', 'medicc_remark'], 'string', 'max' => 200],
            [['medic_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrmMediaCount::className(), 'targetAttribute' => ['medic_id' => 'medic_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medic_id' => 'Medic ID',
            'medicc_id' => 'Medicc ID',
            'cmt_id' => 'Cmt ID',
            'medicc_porjects' => 'Medicc Porjects',
            'medicc_srdate' => 'Medicc Srdate',
            'medicc_srtype' => 'Medicc Srtype',
            'medicc_srcycle' => 'Medicc Srcycle',
            'medicc_srcost' => 'Medicc Srcost',
            'medicc_currid' => 'Medicc Currid',
            'medicc_expectqty' => 'Medicc Expectqty',
            'medicc_clickrate' => 'Medicc Clickrate',
            'medicc_uv' => 'Medicc Uv',
            'medicc_realqty' => 'Medicc Realqty',
            'medicc_realclickrate' => 'Medicc Realclickrate',
            'medicc_realuv' => 'Medicc Realuv',
            'medicc_avgclickrate' => 'Medicc Avgclickrate',
            'medicc_400tel' => 'Medicc 400tel',
            'medicc_tq' => 'Medicc Tq',
            'medicc_rememqty' => 'Medicc Rememqty',
            'medicc_cpa' => 'Medicc Cpa',
            'medicc_remark' => 'Medicc Remark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedic()
    {
        return $this->hasOne(CrmMediaCount::className(), ['medic_id' => 'medic_id']);
    }
}
