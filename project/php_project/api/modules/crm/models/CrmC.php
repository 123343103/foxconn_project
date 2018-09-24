<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_certf".
 *
 * @property string $crtf_pkid
 * @property string $cust_id
 * @property integer $YN_SPP
 * @property string $SPP_NO
 * @property integer $crtf_type
 * @property string $bs_license
 * @property string $o_license
 * @property string $tx_reg
 * @property string $O_reg
 * @property string $Qlf_certf
 * @property string $O_cerft
 * @property string $marks
 * @property integer $YN
 */
class CrmC extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_certf';
    }
//获取认证信息
    public static function getCertfInfo($id)
    {
        return self::find()->where(['cust_id' => $id])->one();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'yn_spp', 'crtf_type', 'yn'], 'integer'],
            [['spp_no', 'bs_license', 'tx_reg', 'qlf_certf'], 'string', 'max' => 30],
            [['o_license', 'o_reg', 'o_cerft'], 'string', 'max' => 100],
            [['marks'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'crtf_pkid' => '認証PKID',
            'cust_id' => 'crm_bs_customer_info.cust_id',
            'yn_spp' => '1是供應商，0不是供應商',
            'spp_no' => '供應商代碼',
            'crtf_type' => '証件類型(1三証合一，0舊版本)',
            'bs_license' => '營業執照/三証合一新文件名(隨機生成新文件名)',
            'o_license' => '營業執照/三証合一原文件名',
            'tx_reg' => '稅務登錄新文件名(隨機生成新文件名)',
            'o_reg' => '稅務登錄舊文件名',
            'qlf_certf' => '納稅人資格証新文件名(隨機生成新文件名)',
            'o_cerft' => '納稅人資格証舊文件名',
            'marks' => '備注',
            'yn' => '1有效，0為駁回前無效數據',
        ];
    }

//获取认证信息
    public static function getCrmCertfOne($id)
    {
        return self::find()->where(['cust_id' => $id])->one();
    }
}
