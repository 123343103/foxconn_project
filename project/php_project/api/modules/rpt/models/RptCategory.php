<?php

namespace app\modules\rpt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "rpt_category".
 *
 * @property integer $rptc_id
 * @property string $rptc_code
 * @property string $rptc_name
 * @property integer $rptc_sort
 * @property string $rptc_status
 * @property string $rptc_remark
 */
class RptCategory extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rpt_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rptc_sort'], 'integer'],
            [['rptc_code', 'rptc_name'], 'string', 'max' => 20],
            [['rptc_status'], 'string', 'max' => 2],
            [['rptc_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rptc_id' => 'Rptc ID',
            'rptc_code' => 'Rptc Code',
            'rptc_name' => 'Rptc Name',
            'rptc_sort' => 'Rptc Sort',
            'rptc_status' => 'Rptc Status',
            'rptc_remark' => 'Rptc Remark',
        ];
    }

    public function getTemplate()
    {
        return $this->hasMany(RptTemplate::className(),['rptt_cat'=>'rptc_id'])->orderBy('rptt_sort DESC');
    }
//
    public function getRpt()
    {
        return $this->hasMany(RptData::className(),['rptt_id'=>'rptt_id'])->via('template');
    }
}
