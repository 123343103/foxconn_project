<?php

namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use app\modules\crm\models\CrmCreditLimit;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\LCrmCreditApply;
use app\modules\crm\models\LCrmCreditLimit;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsCompany;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCreditApply;
use yii\db\Query;
/**
 * CrmCreditLimitSearch represents the model behind the search form about `app\modules\crm\models\CrmCreditLimit`.
 */
class CrmCreditLimitSearch extends CrmCreditApply
{
    public $cust_code;
    public $cust_sname;
    public $start;
    public $end;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_credit_id', 'cust_id', 'credit_people', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['volume_trade', 'total_amount', 'credit_amount', 'allow_amount', 'used_limit', 'surplus_limit', 'grand_total_limit'], 'number'],
            [['credit_date', 'create_at', 'update_at','credit_code', 'currency', 'credit_type', 'payment_type', 'payment_clause', 'payment_clause_day', 'payment_method', 'initial_day', 'pay_day', 'standby1', 'standby2', 'standby3','apply_remark','credit_status','start','end','cust_code','cust_sname'], 'safe'],
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
     *
     * 帐信额度维护
     */

    public function search($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'apply.credit_id',
            'apply.credit_code', // 申请单号
            'date_format(apply.credit_date,"%Y-%m-%d") credit_date',    //申请时间
            'apply.total_amount',   //申请额度
            'apply.credit_amount',  //批复额度
            'apply.allow_amount',  //可用额度
            'apply.used_limit',  //已使用额度
            'apply.grand_total_limit',  //累积额度
            'apply.credit_status',  //状态
            'apply.apply_remark',  //备注
            'apply.file_new',    //新文件名
            'apply.file_old',    //源文件名
            'apply.credit_type',    //账信类型id
            'info.cust_sname',      //客户名称
            'info.cust_code',       //客户代码
            'limit.limit_id credit_limit_id',
            'bs_1.bnt_sname payment_clause',        //付款条件
            'bp_2.bsp_svalue payment_method',       //付款方式
            'bp_3.bsp_svalue initial_day',          //起算日
            'bp_4.bsp_svalue pay_day',              //付款日
            'bt.business_value creditType',         //账信类型名称
            'bp_6.bsp_svalue currency',             //币别
            'hr_1.staff_name credit_people',        //申请人
            'hr_2.staff_name manager',              //客户经理人
            'cs.csarea_name csarea_name',           //营销区域
            'bc.company_name company_name',         //法人
            'concat(type.business_value,":",limit.credit_limit )credit_limit',//申请额度
            'limit.approval_limit',
            'limit.used_limit',
            'limit.surplus_limit',
            'date_format(limit.validity_date,"%Y-%m-%d") validity_date',

//            "(CASE credit_status WHEN 13 THEN '审核完成' ELSE '其他' END) as status"
        ])->from(CrmCreditApply::tableName().' apply')
            ->leftJoin(CrmCustomerInfo::tableName().' info','info.cust_id = apply.cust_id')
            ->leftJoin(CrmCreditLimit::tableName().' limit','limit.credit_id = apply.credit_id')
            ->leftJoin(BsSettlement::tableName().' bs_1','bs_1.bnt_code = apply.payment_clause')
            ->leftJoin(BsPubdata::tableName().' bp_2','bp_2.bsp_id = apply.payment_method')
            ->leftJoin(BsPubdata::tableName().' bp_3','bp_3.bsp_id = apply.initial_day')
            ->leftJoin(BsPubdata::tableName().' bp_4','bp_4.bsp_id = apply.pay_day')
            ->leftJoin(BsBusinessType::tableName().' bt','bt.business_type_id = apply.credit_type')
            ->leftJoin(BsPubdata::tableName().' bp_6','bp_6.bsp_id = apply.currency')
            ->leftJoin(HrStaff::tableName().' hr_1','hr_1.staff_id = apply.credit_people')
            ->leftJoin(CrmCustPersoninch::tableName().' inch','inch.cust_id = info.cust_id')
            ->leftJoin(HrStaff::tableName().' hr_2','hr_2.staff_id = inch.ccpich_personid')
            ->leftJoin(CrmSalearea::tableName().' cs','cs.csarea_id = info.cust_salearea')
            ->leftJoin(BsCompany::tableName().' bc','bc.company_id = apply.company_id')
            ->leftJoin(BsBusinessType::tableName().' type','type.business_type_id=limit.credit_type')
            ->where(['=','credit_status','13'])
            ->andWhere(['in','apply.company_id',BsCompany::getIdsArr($params['companyId'])])
//            ->groupBy(['apply.l_credit_id'])
            ->orderBy(['apply.create_at'=>SORT_DESC])
        ;
        if(!empty($params['staff_id'])){
            $query->andWhere(['or',['apply.credit_people'=>$params['staff_id']],['is','apply.credit_people',null]]);
        }
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            if(isset($params['export'])){
                $pageSize =false;
            }else{
                $pageSize =10;
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
            'apply.credit_type' => $this->credit_type,
        ]);

        $query->andFilterWhere(['like', 'info.cust_code', trim($this->cust_code)])
            ->andFilterWhere([
                'or',
                ['like', 'info.cust_sname', trim($this->cust_sname)],
                ['like', 'info.cust_sname', $trans->t2c(trim($this->cust_sname))],
                ['like', 'info.cust_sname', $trans->c2t(trim($this->cust_sname))],
            ]);
        if ($this->start && !$this->end) {
            $query->andFilterWhere([">=", "validity_date", date("Y-m-d", strtotime($this->start))]);
        }
        if ($this->end && !$this->start) {
            $query->andFilterWhere(["<=", "validity_date", date("Y-m-d", strtotime($this->end . '+1 day'))]);
        }
        if ($this->start && $this->end) {
            $query->andFilterWhere(["between", "validity_date", date("Y-m-d", strtotime($this->start)), date("Y-m-d", strtotime($this->end . '+1 day'))]);
        }

//        echo $query->createCommand()->getRawSql();

        return $dataProvider;
    }

    public function searchList($params){
        $query = (new Query())->select([
            'limit.credit_limit',           //申请总额度
            'limit.approval_limit',         //批复总额度
            'limit.remark',                 //备注
            'date_format(limit.validity_date,"%Y-%m-%d") validity_date',               //有效期
            'bbt.business_value creditType',               //信用额度类型
            'date_format(apply.credit_date,"%Y-%m-%d") credit_date',
            'hs_1.staff_name creditPeople',
            "(CASE limit.is_approval WHEN ".LCrmCreditLimit::YES_APPROVAL." THEN '是' WHEN  ".LCrmCreditLimit::NO_APPROVAL." THEN '否' ELSE '默认' END) as approval",        //是否授权
        ])->from(LCrmCreditLimit::tableName().' limit')
            ->leftJoin(LCrmCreditApply::tableName().' apply','apply.l_credit_id = limit.l_credit_id')
            ->leftJoin(BsBusinessType::tableName().' bbt','bbt.business_type_id = apply.credit_type')
            ->leftJoin(HrStaff::tableName().' hs_1','hs_1.staff_id = apply.credit_people')
            ->where(['limit.l_credit_id'=>$params['id']]);

        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            if(isset($params['export'])){
                $pageSize =false;
            }else{
                $pageSize =10;
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
            'apply.credit_type' => $this->credit_type,
        ]);

        if ($this->start && !$this->end) {
            $query->andFilterWhere([">=", "apply.credit_date", date("Y-m-d", strtotime($this->start))]);
        }
        if ($this->end && !$this->start) {
            $query->andFilterWhere(["<=", "apply.credit_date", date("Y-m-d", strtotime($this->end . '+1 day'))]);
        }
        if ($this->start && $this->end) {
            $query->andFilterWhere(["between", "apply.credit_date", date("Y-m-d", strtotime($this->start)), date("Y-m-d", strtotime($this->end . '+1 day'))]);
        }
//        echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}
