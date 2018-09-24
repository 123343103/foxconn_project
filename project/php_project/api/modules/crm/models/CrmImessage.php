<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "crm_imessage".
 *
 * @property integer $imesg_id
 * @property string $table_name
 * @property integer $cust_id
 * @property string $imesg_type
 * @property string $imesg_sentman
 * @property string $imesg_sentdate
 * @property string $imesg_notes
 * @property integer $imesg_receiver
 * @property string $imesg_btime
 * @property string $imesg_etime
 * @property string $imesg_status
 * @property string $imesg_remark
 */
class CrmImessage extends Common
{
    const STATUS_DEL = '0';     //删除
    const STATUS_DEFAULT = '1';     //激活
    const STATUS_NONE = '2';     //关闭
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_imessage';
    }

    public function behaviors(){
        return [
            [
                "class"=>\yii\behaviors\AttributeBehavior::className(),
                'attributes'=>[
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'imesg_sentdate'
                ],
                'value' => new \yii\db\Expression('NOW()')
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'imesg_receiver'], 'integer'],
            [['imesg_sentdate', 'imesg_btime', 'imesg_etime'], 'safe'],
            [['imesg_sentman'], 'string', 'max' => 20],
            [['imesg_type'], 'string', 'max' => 4],
            [['imesg_notes'], 'string', 'max' => 400],
            [['imesg_status'], 'string', 'max' => 2],
            [['imesg_status'], 'default', 'value' => self::STATUS_DEFAULT],
            [['imesg_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'imesg_id' => 'Imesg ID',
            'table_name' => 'Table Name',
            'cust_id' => 'Cust ID',
            'imesg_type' => 'Imesg Type',
            'imesg_sentman' => 'Imesg Sentman',
            'imesg_sentdate' => 'Imesg Sentdate',
            'imesg_notes' => 'Imesg Notes',
            'imesg_receiver' => 'Imesg Receiver',
            'imesg_btime' => 'Imesg Btime',
            'imesg_etime' => 'Imesg Etime',
            'imesg_status' => 'Imesg Status',
            'imesg_remark' => 'Imesg Remark',
        ];
    }

    public function getCreator(){
        return $this->hasOne(HrStaff::className(),["staff_id"=>"imesg_sentman"])->select("staff_name");
    }

    public static function getIMessages($condition){
        return self::find()->where($condition)->andWhere(['<>','imesg_status',self::STATUS_DEL])->orderBy('imesg_id DESC')->all();
    }

    public function getReceiver(){
        return $this->hasOne(HrStaff::className(),["staff_id"=>"imesg_receiver"])->select("staff_name,staff_code");
    }
    /*厂商信息*/
    public function getCustomerInfo(){
        return $this->hasOne(CrmCustomerInfo::className(),['cust_id'=>'cust_id'])->select('cust_sname');
    }
}
