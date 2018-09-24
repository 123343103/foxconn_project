<?php

namespace app\modules\sale\models;

use app\models\Common;
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
 *
 * @property RBankOrder[] $rBankOrders
 */
class BsBankInfo extends Common
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
            'SYS_CDE' => 'Sys  Cde',
            'FIN_CDE' => 'Fin  Cde',
            'FIN_SUBAREA_CDE' => 'Fin  Subarea  Cde',
            'BNK_CDE' => 'Bnk  Cde',
            'SYS_CN_DESC' => 'Sys  Cn  Desc',
            'FIN_NME' => 'Fin  Nme',
            'FIN_SUBAREA' => 'Fin  Subarea',
            'CORP_DESC' => 'Corp  Desc',
            'BNK_NME' => 'Bnk  Nme',
            'BRANCH_NME' => 'Branch  Nme',
            'ACCOUNTS' => 'Accounts',
            'CUR_CDE' => 'Cur  Cde',
            'TXNAMT' => 'Txnamt',
            'TRDATE' => 'Trdate',
            'TRANSID' => 'Transid',
            'OPPACCNO' => 'Oppaccno',
            'OPPNAME' => 'Oppname',
            'INTERINFO' => 'Interinfo',
            'POSTSCRIPT' => 'Postscript',
            'IN_DATE' => 'In  Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRBankOrders()
    {
        return $this->hasOne(RBankOrder::className(), ['TRANSID' => 'TRANSID']);
    }
}
