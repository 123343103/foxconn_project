<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/4
 * Time: 9:13
 */
namespace app\modules\sale\models\search;

use app\classes\Trans;
use app\modules\sale\models\PriceInfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class PriceInfoSearch extends PriceInfo{

    public $cust_sname;
    public $applyno;
    public $pac_id;
    public $start_date;
    public $end_date;

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['saph_code', 'audit_id', 'price_no', 'price_type', 'cust_sname', 'applyno', 'corporate', 'pac_id', 'start_date', 'end_date'], 'safe'],
        ];
    }

    /**
     * 首页搜索
     * @param $params
     *
     */
    public function search($params){
        $query = (new Query())->select([
            'info.price_id',                    //报价单ID
            'info.price_no',                    //报价单号
            'info.saph_code',                   //需求单号
            'info.audit_id',                    //报价单状态
            'info.price_type',                    //报价单类型
//            'ast.audit_name price_status',      //报价单状态(列表)
            "(CASE info.audit_id WHEN " . PriceInfo::STATUS_DEFAULT . " THEN '待提交' WHEN  " . PriceInfo::STATUS_UNDER_REVIEW . " THEN '审核中' WHEN ". PriceInfo::STATUS_REVIEW_OVER ." THEN '审核完成' WHEN ".PriceInfo::STATUS_REVIEW_REJECT." THEN '驳回' WHEN ".PriceInfo::STATUS_CANCLE_OFFER." THEN '取消报价' WHEN ".PriceInfo::STATUS_WAIT_ORRER." THEN '待报价' ELSE '已删除' END) as price_status",
            'info.price_date saph_date',        //下单时间
            'cust.cust_sname',                  //客户名称
            'cust.cust_code',                   //客户代码
            'cp.company_name corporate',        //交易法人
            'bp_1.bsp_svalue origin',           //订单来源
            'bbt.business_value saph_type',     //订单类型
            'bp_2.bsp_svalue invoice',          //发票类型
//            'bpm.pac_sname pac_sname',          //付款方式
            '(CASE WHEN pub4.bsp_svalue is not null THEN pub4.bsp_svalue ELSE bpm.pac_sname END) as pac_sname',
            'hs.staff_name manager',              //销售员
            'ROUND(info.prd_org_amount,2) bill_oamount', //商品总金额
            'ROUND(info.tax_freight,2) bill_freight',       //总运费
            'ROUND(info.req_tax_amount,2) bill_tax_amount', //订单总金额
            '(CASE bp_3.bsp_svalue WHEN "RMB" THEN "￥" WHEN "USD" THEN "$" WHEN "UKD" THEN "£" WHEN "EUR" THEN "€"  ELSE "其他" END) AS currency_mark',
        ])->from(['info'=>'oms.price_info'])
//            ->leftJoin('oms.req_info reqinfo','reqinfo.saph_code = info.saph_code')
            ->leftJoin('erp.crm_bs_customer_info cust','cust.cust_code = info.cust_code')
            ->leftJoin('erp.bs_company cp','cp.company_id = info.corporate')
            ->leftJoin('erp.bs_pubdata bp_1','bp_1.bsp_id = info.origin_hid')
            ->leftJoin('erp.bs_pubdata bp_2','bp_2.bsp_id = info.invoice_type')
            ->leftJoin('erp.bs_business_type bbt','bbt.business_type_id = info.price_type')
            ->leftJoin('erp.bs_payment bpm','bpm.pac_id = info.pac_id')
            ->leftJoin('erp.hr_staff hs','hs.staff_id = info.nwer')
//            ->leftJoin('erp.audit_state ast','ast.audit_id = info.audit_id')
            ->leftJoin('erp.bs_pubdata bp_3','bp_3.bsp_id = info.cur_id')
            ->leftJoin('erp.bs_pubdata pub4', 'pub4.bsp_id=info.pay_type')// 订单来源
            ->orderBy('info.price_id desc')
            ;
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,

            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
//        return $this;
        if ($this->pac_id == 'credit-amount') {
            $query->andFilterWhere(['bpm.pac_code' => $this->pac_id]);// 付款方式
        } else {
            $query->andFilterWhere(['info.pay_type' => $this->pac_id]);// 付款方式
        }
        $trans = new Trans();
        $query->andFilterWhere([
            'info.audit_id' => $this->audit_id,
            'info.price_type' => $this->price_type,
            'info.corporate' => $this->corporate,
        ]);
        $query->andFilterWhere([
            'or',
            ['like', 'cust.cust_sname', $trans->c2t($this->cust_sname)],
            ['like', 'cust.cust_sname', $trans->t2c($this->cust_sname)]
        ])// 客户名称
            ->andFilterWhere(['like', 'info.price_no', $this->price_no,])
            ->andFilterWhere(['like', 'info.saph_code', $this->saph_code,])
            ->andFilterWhere(['like', 'cust.cust_code', $this->applyno,])
        ;
        if(!empty($this->start_date)){
            $query->andFilterWhere(['>=', 'info.price_date', date('Y-m-d H:i:s',strtotime($this->start_date))]);
        }
        if(!empty($this->end_date)){
            $query->andFilterWhere(['<=', 'info.price_date', date('Y-m-d H:i:s',strtotime("+1 day",strtotime($this->end_date)))]);
        }
//        echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}