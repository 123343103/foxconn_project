<?php

namespace app\modules\warehouse\models;

use app\behaviors\FormCodeBehavior;
use Yii;

/**
 * This is the model class for table "rcp_notice".
 *
 * @property string $rcpnt_id
 * @property string $rcpnt_no
 * @property integer $rcpnt_status
 * @property integer $rcpnt_type
 * @property string $i_whcode
 * @property string $o_whcode
 * @property string $prch_no
 * @property string $leg_id
 * @property string $app_depno
 * @property string $prch_depno
 * @property string $prch_area
 * @property string $rcp_no
 * @property string $factory_id
 * @property string $notifier
 * @property string $inform_date
 * @property string $cancel_date
 * @property string $cancel_reason
 * @property string $remarks
 * @property integer $creator
 * @property string $creat_date
 * @property integer $operator
 * @property string $operate_date
 * @property string $operate_ip
 * @property string $prch_date
 */
class RcpNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rcp_notice';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creator', 'creat_date'], 'required'],
            [['rcpnt_status', 'rcpnt_type', 'leg_id', 'app_depno', 'prch_area', 'creator', 'operator'], 'integer'],
            [['inform_date', 'cancel_date', 'creat_date', 'operate_date', 'prch_date'], 'safe'],
            [['rcpnt_no', 'i_whcode', 'o_whcode', 'prch_depno', 'rcp_no', 'factory_id'], 'string', 'max' => 30],
            [['prch_no'], 'string', 'max' => 150],
            [['notifier', 'operate_ip'], 'string', 'max' => 20],
            [['cancel_reason', 'remarks'], 'string', 'max' => 200],
            [['rcpnt_no'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rcpnt_id' => '收貨通知ID',
            'rcpnt_no' => '收貨通知單號',
            'rcpnt_status' => '單據狀態(1:待收货;2:已收货;3:已取消;)',
            'rcpnt_type' => '單據類型(1:采购;2:调拨;3:移仓;)',
            'i_whcode' => '入仓仓库(关联wms.bs_wh.wh_code)',
            'o_whcode' => '出倉倉庫',
            'prch_no' => '关联prch.bs_prch(可能有多个,用逗号分隔)',
            'leg_id' => '法人erp.bs_company. company_status=10',
            'app_depno' => '申請部門代碼 erp.hr_organization',
            'prch_depno' => '採購部門代碼 erp.hr_organization.organization_code',
            'prch_area' => '採購區域id erp.bs_pubdata.bsp_stype=&#039;&#039; sqqufw&#039;&#039;&#039;',
            'rcp_no' => '收貨中心編碼',
            'factory_id' => '廠商',
            'notifier' => '通知人',
            'inform_date' => '通知日期',
            'cancel_date' => '取消時間',
            'cancel_reason' => '取消原因',
            'remarks' => '備註',
            'creator' => '創建人',
            'creat_date' => '創建時間',
            'operator' => '操作人',
            'operate_date' => '操作時間',
            'operate_ip' => '操作IP',
            'prch_date' => '采购日期',
        ];
    }

    //行为
    public function behaviors()
    {
        return [
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),
                'codeField'=>'rcpnt_no',
                'formName'=>self::tableName(),
                'model'=>$this,
            ]
        ];
    }
}
