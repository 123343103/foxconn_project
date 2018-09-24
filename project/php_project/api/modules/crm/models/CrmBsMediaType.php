<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_bs_media_type".
 *
 * @property integer $cmt_id
 * @property string $cmt_code
 * @property string $cmt_type
 * @property string $cmt_intro
 * @property integer $cmt_status
 * @property integer $company_id
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 *
 * @property CrmMediaCount[] $crmMediaCounts
 */
class CrmBsMediaType extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_bs_media_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cmt_status', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['cmt_code', 'cmt_type'], 'string', 'max' => 50],
            [['cmt_intro'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cmt_id' => 'Cmt ID',
            'cmt_code' => 'Cmt Code',
            'cmt_type' => 'Cmt Type',
            'cmt_intro' => 'Cmt Intro',
            'cmt_status' => 'Cmt Status',
            'company_id' => 'Company ID',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrmMediaCounts()
    {
        return $this->hasMany(CrmMediaCount::className(), ['cmt_id' => 'cmt_id']);
    }
}
