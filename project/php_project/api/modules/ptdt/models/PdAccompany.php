<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "accompany".
 *
 * @property string $vacc_id
 * @property string $people_type
 * @property string $pvp_planID
 * @property string $staff_code
 */
class PdAccompany extends Common
{
    const RESUME_ACCOMPANY_PERSON = '2';//2,履历陪同人
    const NEGOTIATION_ACCOMPANY_PERSON = '3';//談判陪同人
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_accompany';
    }

    /**
     * 關聯陪同人員信息表
     * @return  array
     */
    public function getStaffAccompany(){
        return $this->hasOne(HrStaff::className(),['staff_code'=>'staff_code']);
    }
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vacc_id', 'h_id','l_id'], 'safe'],
            [['vacc_type','staff_code'], 'safe'],
            ['vacc_type','default','value'=>1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vacc_id' => 'ID',
            'people_type' => '1拜訪計劃陪同人,2談判陪同人',
            'pvp_planID' => '拜訪計劃ID,拜訪履歷ID,談判履歷ID',
            'staff_code' => '關聯人員表',
        ];
    }

}
