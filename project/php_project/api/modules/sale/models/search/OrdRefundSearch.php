<?php

namespace app\modules\sale\models\search;

use app\classes\Trans;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\OrdRefund;
use yii\db\Query;

/**
 * OrdRefundSearch represents the model behind the search form about `app\modules\sale\models\OrdRefund`.
 */
class OrdRefundSearch extends OrdRefund
{
    public $order_status;
    public $cust_sname;
    public $manager;
    public $ord_type;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_status', 'cust_sname', 'manager','refund_no','ord_no', 'manger', 'ord_type', 'rfnd_status','tax_fee'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = (new Query())->select([
            'refund.refund_id',                     //退款单id
            'refund.refund_no',                     //退款单号
            'refund.ord_no',                     //关联订单号
            'refund.rfnd_status',                     //退款状态ID
            'refund.tax_fee bill_oamount',                     //退款总金额
            '(CASE refund.rfnd_status WHEN '.OrdRefund::STATUS_CANCLE_REFUND.' THEN "已取消" WHEN '.OrdRefund::STATUS_IN_REVIEW.' THEN "审核中" WHEN '.OrdRefund::STATUS_PASS_REVIEW.' THEN "审核完成" WHEN '.OrdRefund::STATUS_REJECT_REVIEW.' THEN "驳回" WHEN '.OrdRefund::STATUS_REFUND.' THEN "已退款" ELSE "待提交" END) AS status_name',                     //退款状态
            'date_format(info.ord_date,"%Y-%m-%d") as ord_date',                     //购买日期
            'ordstatus.os_name',                    //订单状态
            'bbt.business_type_desc business_type',                    //订单类型
            'cbci.cust_sname',                  //客户名称
            'info.cust_contacts',                  //联系人
            'info.cust_tel2',                  //联系电话
            'hr_1.staff_name',                  //负责人
            'info.req_tax_amount bill_tax_amount',                  //订单总金额
            'bp_1.bsp_svalue currency',             //币别
            '(CASE bp_1.bsp_svalue WHEN "RMB" THEN "￥" WHEN "USD" THEN "$" WHEN "UKD" THEN "£" WHEN "EUR" THEN "€"  ELSE "其他" END) AS currency_mark',
        ])->from('oms.ord_refund refund')
            ->leftJoin('oms.ord_info info','info.ord_no = refund.ord_no')
            ->leftJoin('erp.hr_staff hr_1','hr_1.staff_id = refund.manger')
            ->leftJoin('oms.ord_status ordstatus','ordstatus.os_id = info.os_id')
            ->leftJoin('erp.bs_business_type bbt','bbt.business_type_id = info.ord_type')
            ->leftJoin('erp.crm_bs_customer_info cbci','cbci.cust_code = info.cust_code')
            ->leftJoin('erp.bs_pubdata bp_1','bp_1.bsp_id = info.cur_id')
            ->orderBy('info.ord_date desc')
        ;

        // add conditions that should always apply here

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
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rfnd_status' => $this->rfnd_status,
            'info.os_id' => $this->order_status,
            'info.ord_type' => $this->ord_type,
        ]);
        $trans = new Trans();
        $query->andFilterWhere(['like', 'refund_no', trim($this->refund_no)])
            ->andFilterWhere(['like', 'refund.ord_no', trim($this->ord_no)])
            ->andFilterWhere([
                'or',
                ['like','cbci.cust_sname',$trans->c2t(trim($this->cust_sname))],
                ['like','cbci.cust_sname',$trans->t2c(trim($this->cust_sname))]
            ])
            ->andFilterWhere([
                'or',
                ['like','hr_1.staff_name',$trans->c2t(trim($this->manager))],
                ['like','hr_1.staff_name',$trans->t2c(trim($this->manager))]
            ])
        ;

        return $dataProvider;
    }
}
