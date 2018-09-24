<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bs_bank_info".
 *
 * @property string $SYS_CDE
 * @property string $FIN_CDE
 * @property string $FIN_SUBAREA_CDE
 * @property string $BNK_CDE
 * @property string $SYS_CN_DESC
 * @property string $FIN_NME
 * @property string $FIN_SUBAREA
 * @property string $CORP_DESC
 * @property string $BNK_NME
 * @property string $BRANCH_NME
 * @property string $ACCOUNTS
 * @property string $CUR_CDE
 * @property string $TXNAMT
 * @property string $TRDATE
 * @property string $TRANSID
 * @property string $OPPACCNO
 * @property string $OPPNAME
 * @property string $INTERINFO
 * @property string $POSTSCRIPT
 * @property string $IN_DATE
 */
class BsBankInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_bank_info';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TXNAMT', 'TRANSID'], 'required'],
            [['TXNAMT'], 'number'],
            [['IN_DATE'], 'safe'],
            [['SYS_CDE', 'SYS_CN_DESC', 'FIN_NME', 'FIN_SUBAREA', 'ACCOUNTS', 'TRANSID'], 'string', 'max' => 50],
            [['FIN_CDE'], 'string', 'max' => 20],
            [['FIN_SUBAREA_CDE', 'BNK_CDE', 'TRDATE'], 'string', 'max' => 10],
            [['CORP_DESC', 'BNK_NME', 'BRANCH_NME', 'OPPACCNO', 'OPPNAME'], 'string', 'max' => 100],
            [['CUR_CDE'], 'string', 'max' => 5],
            [['INTERINFO'], 'string', 'max' => 1000],
            [['POSTSCRIPT'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SYS_CDE' => '體系代碼',
            'FIN_CDE' => '財務區代碼',
            'FIN_SUBAREA_CDE' => '財務分區代碼',
            'BNK_CDE' => '銀行代碼',
            'SYS_CN_DESC' => '體系',
            'FIN_NME' => '財務區名稱',
            'FIN_SUBAREA' => '財務分區名稱',
            'CORP_DESC' => '法人',
            'BNK_NME' => '銀行',
            'BRANCH_NME' => '分行',
            'ACCOUNTS' => '銀行賬戶',
            'CUR_CDE' => '幣別',
            'TXNAMT' => '金額',
            'TRDATE' => '發生時間',
            'TRANSID' => '交易流水號',
            'OPPACCNO' => '對方賬戶',
            'OPPNAME' => '對方賬戶名稱',
            'INTERINFO' => '摘要',
            'POSTSCRIPT' => '附言',
            'IN_DATE' => '導入時間:用於跟蹤應用服務的執行情況。保留第一次執行時間',
        ];
    }
}
