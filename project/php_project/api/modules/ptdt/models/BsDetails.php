<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_details".
 *
 * @property string $pdt_dt_id
 * @property string $pdt_pkid
 * @property string $prt_pkid
 * @property string $details
 */
class BsDetails extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_details';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pdt_pkid'], 'required'],
            [['pdt_pkid', 'prt_pkid'], 'integer'],
            [['details'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdt_dt_id' => 'Pdt Dt ID',
            'pdt_pkid' => 'Pdt Pkid',
            'prt_pkid' => 'Prt Pkid',
            'details' => 'Details',
        ];
    }

//    public function afterSave($insert,$changedAttributes){
//        LDetails::deleteAll(["l_pdt_pkid"=>$this->pdt_pkid]);
//        $logModel=new LDetails();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_pdt_pkid=$this->pdt_pkid;
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        $logModel->yn=0;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("料号详细信息修改失败");
//        }
//        return true;
//    }
}
