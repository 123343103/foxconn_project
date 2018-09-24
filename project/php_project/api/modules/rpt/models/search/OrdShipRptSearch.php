<?php

namespace app\modules\rpt\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmEmployee;
use app\modules\hr\models\HrStaff;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdDt;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * OrdInfoSearch represents the model behind the search form about `app\modules\crm\models\OrdInfo`.
 */
class OrdShipRptSearch extends OrdInfo
{
    public $csarea_name;     //营销区域
    public $cust_department; //销售部门
    public $cust_shortname;  //客户名称
    public $cust_code;       //客户代码
    public $staff_name;      //销售人员
    public $ord_no;          //订单号
    public $start_date;      //开始日期
    public $end_date;        //结束日期

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['csarea_name', 'ord_no', 'cust_department',   'cust_shortname', 'cust_code', 'staff_name', 'start_date', 'end_date',
               ], 'safe'],

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
     * 订单查询明细报表
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = (new Query())
            ->select([
                'a.ord_id',
                //营销区域
                'k.csarea_name caerea',
                //销售部门
                'i.organization_name',
                //销售点
                'l.sts_sname sts_sname',
                //省/直辖市
                'm.district_name',
                //客户代码
                'h.cust_code',
                //客户简称
                'h.cust_shortname',
                // 订单类型
                'c.business_value',
                // 订单状态
                'q.os_name',
                //法人
                'f.company_name',

                // 下单时间
                'b.ord_date',
                // 订单编号
                'b.ord_no',
                //商品类别
                'pdt.func_get_categ(p.catg_id) as category_sname',
                // 料号
                'n.part_no',
                // 商品名称
                'o.pdt_name',
                //商品规格(型号)
                'n.tp_spec',
                //单位
                'd3.bsp_svalue unit',
                // 订货数
                'FORMAT(a.sapl_quantity,2) sapl_quantity',
            ])
            ->from(['a' => 'oms.ord_dt'])
            ->leftJoin('oms.ord_info b', 'b.ord_id=a.ord_id')//訂單pkid
            ->leftJoin('erp.bs_business_type c', 'c.business_type_id=b.ord_type')
            ->leftJoin('erp.bs_pubdata d', 'd.bsp_id=b.cur_id')
            ->leftJoin('erp.bs_payment e', 'e.pac_id=b.pac_id')
            ->leftJoin('erp.bs_pubdata d2', 'd2.bsp_id=b.pay_type')
            ->leftJoin('erp.bs_company f', 'f.company_id=b.corporate')
            ->leftJoin('erp.hr_staff g', 'g.staff_id=b.nwer')
            ->leftJoin('erp.hr_organization i', 'i.organization_code=g.organization_code')
            ->leftJoin('erp.crm_employee j', 'j.staff_code=g.staff_code')
            ->leftJoin('erp.crm_bs_customer_info h', 'h.cust_code=b.cust_code')
            ->leftJoin('erp.crm_bs_salearea k', 'k.csarea_id=j.sale_area')
            ->leftJoin('erp.crm_bs_storesinfo l', 'l.sts_id=j.sts_id')
            ->leftJoin('erp.bs_district m', 'm.district_id=l.district_id')
           ->leftJoin('pdt.bs_partno n', 'n.prt_pkid=a.prt_pkid')
            ->leftJoin('pdt.bs_product o', 'o.pdt_pkid=n.pdt_pkid')
            ->leftJoin('erp.bs_pubdata d3', 'd3.bsp_id=o.unit')
            ->leftJoin('pdt.bs_category p', 'p.catg_id=o.catg_id')
            ->leftJoin('oms.ord_status q', 'q.os_id=b.os_id')

            ->groupBy("a.ord_id")
            ->orderBy(['a.ord_id' => SORT_DESC]);
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

        $query->andFilterWhere([
            'k.csarea_id'=>$this->csarea_name,  //营销区域
            'i.organization_code' => $this->cust_department //销售部门
        ]);

        $trans = new Trans();
        $query->andFilterWhere(['like', 'b.ord_no', trim($this->ord_no)])// 订单编号
        ->andFilterWhere(['like', 'g.staff_code', trim($this->staff_name)])// 销售人员

        ->andFilterWhere([
            'or',
            ['like', 'h.cust_shortname', $trans->c2t(trim($this->cust_shortname))],
            ['like', 'h.cust_shortname', $trans->t2c(trim($this->cust_shortname))],
            ['like', 'h.cust_code', $trans->t2c(trim($this->cust_shortname))],
            ['like', 'h.cust_code', $trans->c2t(trim($this->cust_shortname))]
        ]); //客户简称
        if (!empty($this->start_date)) {
            $query->andFilterWhere(['>=', 'b.ord_date', date('Y-m-d H:i:s', strtotime($this->start_date))]);
        }
        if (!empty($this->end_date)) {
            $query->andFilterWhere(['<=', 'b.ord_date', date('Y-m-d H:i:s', strtotime("+1 day", strtotime($this->end_date)))]);
        }
        return $dataProvider;
    }


}