<?php

namespace app\modules\crm\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * This is the model class for table "crm_media_count".
 *
 * @property integer $medic_id
 * @property integer $cmt_id
 * @property string $cmt_type
 * @property string $medic_compname
 * @property string $medic_parner
 * @property string $medic_position
 * @property string $medic_phone
 * @property string $medic_tel
 * @property string $medic_emails
 * @property string $medic_issupilse
 * @property string $medic_times
 * @property string $medic_level
 * @property integer $DISTRICT_ID
 * @property string $medic_adds
 * @property string $medic_qual
 * @property string $cmt_status
 * @property integer $create_by
 * @property integer $create_at
 * @property integer $update_by
 * @property integer $udpate_at
 *
 * @property CrmBsMediaType $cmt
 * @property CrmMediaCountChild[] $crmMediaCountChildren
 */
class CrmMediaCount extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_media_count';
    }

    const serviceLevel=["A","B","C","D"];
    const isSupplier=["1"=>"是","0"=>"否"];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medic_id', 'cmt_id', 'district_id', 'create_by', 'update_by',], 'integer'],
            [['cmt_type'], 'string', 'max' => 6],
            [['medic_code'], 'string', 'max' => 20],
            [['medic_compname', 'medic_parner', 'medic_position', 'medic_phone', 'medic_tel', 'medic_emails', 'medic_issupilse', 'medic_times', 'medic_level', 'medic_qual'], 'string', 'max' => 60],
            [['medic_adds','medic_profile'], 'string', 'max' => 200],
            [['cmt_status'], 'string', 'max' => 2],
            [['cmt_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrmBsMediaType::className(), 'targetAttribute' => ['cmt_id' => 'cmt_id']],
            [['update_at','create_at'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'medic_id' => 'Medic ID',
            'cmt_id' => 'Cmt ID',
            'cmt_type' => 'Cmt Type',
            'medic_compname' => 'Medic Compname',
            'medic_parner' => 'Medic Parner',
            'medic_position' => 'Medic Position',
            'medic_phone' => 'Medic Phone',
            'medic_tel' => 'Medic Tel',
            'medic_emails' => 'Medic Emails',
            'medic_issupilse' => 'Medic Issupilse',
            'medic_times' => 'Medic Times',
            'medic_level' => 'Medic Level',
            'DISTRICT_ID' => 'District  ID',
            'medic_adds' => 'Medic Adds',
            'medic_qual' => 'Medic Qual',
            'cmt_status' => 'Cmt Status',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'udpate_at' => 'Udpate At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmt()
    {
        return $this->hasOne(CrmBsMediaType::className(), ['cmt_id' => 'cmt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrmMediaCountChildren()
    {
        return $this->hasMany(CrmMediaCountChild::className(), ['medic_id' => 'medic_id']);
    }


    public function behaviors(){
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'medic_code',
                "formName" => self::tableName(),
                "model" => $this,
            ],
            'timeStamp'=>[
                "class"=>TimestampBehavior::className()
            ]
        ];
    }

}
