<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "crm_sale_quotedprice_h".
 *
 * @property integer $saph_id
 * @property string $bs_id
 * @property integer $corp_id
 * @property string $saph_category
 * @property string $saph_no
 * @property string $saph_date
 * @property integer $cust_id
 * @property integer $pos_id
 * @property integer $applicant
 * @property integer $branch_district
 * @property string $branch_sale_area
 * @property integer $currency
 * @property integer $pay_type
 * @property string $payment_terms
 * @property string $trading_terms
 * @property string $valid_date
 * @property string $description
 * @property string $remark
 * @property integer $checker
 * @property string $check_date
 * @property integer $status
 * @property integer $creator
 * @property string $creatdate
 * @property integer $modified_by
 * @property string $modified_date
 */
class CrmSaleQuotedprice extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_sale_quotedprice_h';
    }


    public function behaviors(){
        return [
            [
                "class"=>\yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute'=>'creatdate',
                'updatedAtAttribute'=>'modified_date',
                'value'=>new \yii\db\Expression('NOW()')
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['bs_id', 'corp_id', 'saph_category', 'saph_no', 'saph_date', 'cust_id', 'pos_id', 'branch_district', 'branch_sale_area', 'currency', 'pay_type', 'payment_terms', 'trading_terms', 'valid_date', 'description', 'remark', 'checker', 'check_date', 'status', 'creator', 'creatdate', 'modified_by', 'modified_date'], 'required'],
            [['corp_id', 'cust_id', 'pos_id', 'branch_district', 'currency', 'pay_type', 'checker', 'status', 'creator', 'modified_by'], 'integer'],
            [['saph_date', 'valid_date', 'check_date', 'creatdate', 'modified_date'], 'safe'],
            [['bs_id', 'saph_no','applicant', 'branch_sale_area'], 'string', 'max' => 20],
            [['saph_category'], 'string', 'max' => 4],
            [['payment_terms', 'trading_terms'], 'string', 'max' => 100],
            [['description', 'remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'saph_id' => 'Saph ID',
            'bs_id' => '業務對象ID',
            'corp_id' => '公司ID',
            'saph_category' => '報價單類別',
            'saph_no' => '報價單編號',
            'saph_date' => '報價單日期',
            'cust_id' => '客戶ID',
            'pos_id' => '銷售点ID',
            'applicant' => '報價人',
            'branch_district' => '分公司所在地區',
            'branch_sale_area' => '分公司所在銷售區域',
            'currency' => '交易幣別',
            'pay_type' => '付款方式',
            'payment_terms' => '付款条件',
            'trading_terms' => '交货条件',
            'valid_date' => '报价有效期',
            'description' => '其他说明',
            'remark' => '备注',
            'checker' => '送审人',
            'check_date' => '送审日期',
            'status' => '状态',
            'creator' => '创建人',
            'creatdate' => '创建日期',
            'modified_by' => '修改人',
            'modified_date' => '修改日期'
        ];
    }


    public static function search($params=""){
        $query=self::find()->joinWith([
            "testcount"=>function($query){
//                return $query;
            return $query->select("cust_id");
        },
            "customer"=>function($query){
//                return $query;
                return $query->select("cust_id");
            }
        ])
        ->select([
            self::tableName().".cust_id",
            self::tableName().".saph_id",
            self::tableName().".applicant",
            self::tableName().".saph_date",
            CrmCustomerInfo::tableName().".cust_sname",
            CrmCustomerInfo::tableName().".cust_contacts",
            CrmCustomerInfo::tableName().".cust_inchargeperson",
            SaleTestcount::tableName().".quotedprice_code",
            SaleTestcount::tableName().".status",
            SaleTestcount::tableName().".cust_risk",
            "if(is_pre_pay is null,if(is_ensure is null,if(is_group_auth is null,if(others_risk is null,'/',others_risk),'集团授信'),'银行/保险公司担保'),'预收款') cust_risk",
        ])->asArray();
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);
        return $dataProvider;
    }


    public function getChild(){
        return $this->hasMany(CrmSaleQuotedpriceChild::className(),["saph_id"=>"saph_id"]);
    }

    public function getTestcount(){
        return $this->hasOne(SaleTestcount::className(),["saph_id"=>"saph_id"]);
    }

    public function getTestcountChild(){
        return $this->hasMany(SaleTestcountChild::className(),["saph_id"=>"saph_id"]);
    }

    public function getCustomer(){
        return $this->hasOne(CrmCustomerInfo::className(),["cust_id"=>"cust_id"]);
    }
}
