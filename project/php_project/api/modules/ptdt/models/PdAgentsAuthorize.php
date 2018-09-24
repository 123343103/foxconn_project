<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use Yii;

/**
 * F3859386
 * 2016.10.22
 * 厂商谈判授权模型
 *
 * @property string $pdaa_id
 * @property string $pdn_id
 * @property string $pdnc_id
 * @property string $pdn_code
 * @property string $pdnc_code
 * @property string $firm_id
 * @property string $pdn_date
 * @property string $pdn_time
 * @property string $pdaa_agents_grade
 * @property string $pdaa_authorize_area
 * @property string $pdaa_sale_area
 * @property string $pdaa_bdate
 * @property string $pdaa_edate
 * @property string $pdaa_settlement
 * @property string $pdaa_delivery_day
 * @property string $pdaa_delivery_way
 * @property string $pdaa_service
 * @property string $pdaa_sample
 * @property string $pdaa_train_description
 * @property string $pdaa_status
 * @property string $pdaa_agents_description
 * @property string $pdaa_remark
 * @property string $negotiate_concluse
 */
class PdAgentsAuthorize extends Common
{

    const STATUS_DEL=0;
    const STATUS_DEFAULT=10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_agents_authorize';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pdn_id', 'pdnc_id', 'firm_id'], 'safe'],
            [['pdn_date', 'pdn_time', 'pdaa_bdate', 'pdaa_edate','pdaa_status'], 'safe'],
            [['pdn_code', 'pdaa_agents_grade', 'pdaa_authorize_area', 'pdaa_sale_area', 'pdaa_settlement', 'pdaa_delivery_way', 'pdaa_service'], 'safe'],
            [['pdaa_delivery_day', 'pdaa_sample', 'pdaa_train_description', 'pdaa_agents_description', 'pdaa_remark'], 'safe'],
            [['pdaa_status'], 'default', 'value' => self::STATUS_DEFAULT],
            [['pdaa_agents_grade','pdaa_authorize_area','pdaa_sale_area','pdaa_settlement','pdaa_delivery_day','pdaa_bdate','pdaa_edate','pdaa_sample','pdaa_train_description','pdaa_delivery_way','pdaa_service'], 'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdaa_id' => '授权项目ID',
            'pdnc_id' => '谈判子表id,关联谈判子表',
            'pdnc_code' => '谈判子表编码,关联谈判子表',
            'pdn_id' => '谈判主表id,关联谈判主表',
            'pdn_code' => '谈???主表编码,关联谈判主表',
            'firm_id' => '厂商id,关联厂商表',
            'pdn_date' => '谈判日期',
            'pdn_time' => '谈判时间',
            'pdaa_agents_grade' => '代理等级',
            'pdaa_authorize_area' => '授权区域范围',
            'pdaa_sale_area' => '销售范围',
            'pdaa_bdate' => '授权开始日期',
            'pdaa_edate' => '授权结束日期',
            'pdaa_settlement' => '结算方式',
            'pdaa_delivery_day' => '交期',
            'pdaa_delivery_way' => '物流配送',
            'pdaa_service' => '售后服务',
            'pdaa_sample' => '样品提供',
            'pdaa_train_description' => '培训方式',
            'pdaa_status' => '10正常0取消',
            'pdaa_agents_description' => '授权描述',
            'pdaa_remark' => '备注',
        ];
    }
    public static function getAuthorizeById($id){
        return static::find()->where(['firm_id'=>$id])->orderBy(['pdn_date'=>SORT_DESC,'pdn_time' => SORT_DESC])->one();
    }
    //代理等級
    public function getAgentsGrade(){
        return $this->getData('pdaa_agents_grade');
    }
    //授權區域範圍
    public function getAuthorizeArea(){
        return $this->getData('pdaa_authorize_area');
    }
    //授權區域範圍
    public function getSaleArea(){
        return $this->getData('pdaa_sale_area');
    }
    //结算方式
    public function getSettlement(){
        return $this->hasOne(BsSettlement::className(),['bnt_id'=>'pdaa_settlement']);
    }
    //物流配送
    public function getDeliveryWay(){
        return $this->getData('pdaa_delivery_way');
    }
    //售后服务
    public function getService(){
        return $this->getData('pdaa_service');
    }

    //关联公共库
    public function getData($type){
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => $type]);
    }

}
