<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "crm_act_imessage".
 *
 * @property integer $obj_id
 * @property integer $imesg_id
 * @property string $imesg_type
 * @property string $imesg_sentman
 * @property string $imesg_sentdate
 * @property string $imesg_subject
 * @property string $imesg_notes
 * @property string $imesg_status
 * @property string $imesg_remark
 */
class CrmActImessage extends Common
{
    const TYPE_MESSAGE = '1';   //手机信息
    const TYPE_EMAIL = '2';   //邮件信息
    const STATUS_DEL = '0';     //删除邮件
    const STATUS_DEFAULT = '10';     //默认
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_act_imessage';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'imesg_id'], 'integer'],
//            [['imesg_id'], 'required'],
            [['imesg_sentdate'], 'safe'],
            [['imesg_type'], 'string', 'max' => 4],
            [['imesg_sentman'], 'string', 'max' => 20],
            [['imesg_subject', 'imesg_remark'], 'string', 'max' => 200],
            [['imesg_notes'], 'string', 'max' => 400],
            [['imesg_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cust_id' => 'Obj ID',
            'imesg_id' => 'Imesg ID',
            'imesg_type' => 'Imesg Type',
            'imesg_sentman' => 'Imesg Sentman',
            'imesg_sentdate' => 'Imesg Sentdate',
            'imesg_subject' => 'Imesg Subject',
            'imesg_notes' => 'Imesg Notes',
            'imesg_status' => 'Imesg Status',
            'imesg_remark' => 'Imesg Remark',
        ];
    }

    public function getCreator(){
        return $this->hasOne(HrStaff::className(),["staff_id"=>"imesg_sentman"])->select("staff_name");
    }

    public static function getActImessages($condition){
        return self::find()->where($condition)->orderBy('imesg_id DESC')->all();
    }
}
