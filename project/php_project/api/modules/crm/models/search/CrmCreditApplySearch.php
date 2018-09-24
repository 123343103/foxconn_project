<?php

namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use app\modules\crm\models\CrmCreditLimit;
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
 * CrmCreditApplySearch represents the model behind the search form about `app\modules\crm\models\CrmCreditApply`.
 */
class CrmCreditApplySearch extends LCrmCreditApply
{
    public $cust_code;
    public $cust_sname;
    public $company_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_credit_id', 'cust_id', 'credit_people', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['total_amount', 'credit_amount','volume_trade'], 'number'],
            [['credit_date', 'create_at', 'update_at','cust_code','cust_sname','credit_type'], 'safe'],
            [['credit_code', 'currency', 'standby1', 'standby2', 'standby3'], 'string', 'max' => 20],
            [['apply_remark'], 'string', 'max' => 255],
            [['credit_status'], 'string', 'max' => 2],
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
     * 帐信申请列表
     */
    public function search($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'apply.l_credit_id',
            'apply.credit_code', // 申请单号
            'apply.credit_date',
            'apply.total_amount',
            'apply.credit_amount',
            'apply.credit_status',
            'apply.credit_type',
            'apply.file_new',
            'apply.file_old',
            'info.cust_sname',
            'info.cust_code',
            'limit.l_limit_id credit_limit_id',
//            'group_concat(limit.credit_limit) credit_limit',
//            'group_concat(limit.approval_limit) approval_limit',
//            'group_concat(date_format(limit.validity_date,"%Y-%m-%d")) validity_date',
            'bs_1.bnt_sname payment_clause',
            'bp_2.bsp_svalue payment_method',
            'bp_3.bsp_svalue initial_day',
            'bp_4.bsp_svalue pay_day',
            'bt.business_value creditType',
            'bp_6.bsp_svalue currency',
            'hr_1.staff_name credit_people',
//            'hr_2.staff_name manager',
            'cs.csarea_name csarea_name',
            'bc.company_name company_name',
            'type.business_value credit_type1',
            'concat(type.business_value,":",limit.credit_limit) credit_limit',//申请额度
            'limit.approval_limit',
            'date_format(limit.validity_date,"%Y-%m-%d") validity_date',
            "(CASE credit_status WHEN ".LCrmCreditApply::STATUS_PENDING." THEN '待提交' WHEN  ".LCrmCreditApply::STATUS_REVIEW." THEN '审核中' WHEN  ".LCrmCreditApply::STATUS_OVER." THEN '审核完成' WHEN  ".LCrmCreditApply::STATUS_REJECT." THEN '驳回' WHEN ". LCrmCreditApply::STATUS_DELETE . " THEN '已取消' ELSE '其他' END) as status"
        ])->from(LCrmCreditApply::tableName().' apply')
            ->leftJoin(CrmCustomerInfo::tableName().' info','info.cust_id = apply.cust_id')
            ->leftJoin(LCrmCreditLimit::tableName().' limit','limit.l_credit_id = apply.l_credit_id AND limit.limit_status='.LCrmCreditLimit::YES_NEW)
            ->leftJoin(BsSettlement::tableName().' bs_1','bs_1.bnt_code = apply.payment_clause')
            ->leftJoin(BsPubdata::tableName().' bp_2','bp_2.bsp_id = apply.payment_method')
            ->leftJoin(BsPubdata::tableName().' bp_3','bp_3.bsp_id = apply.initial_day')
            ->leftJoin(BsPubdata::tableName().' bp_4','bp_4.bsp_id = apply.pay_day')
            ->leftJoin(BsBusinessType::tableName().' bt','bt.business_type_id = apply.credit_type')
            ->leftJoin(BsPubdata::tableName().' bp_6','bp_6.bsp_id = apply.currency')
            ->leftJoin(HrStaff::tableName().' hr_1','hr_1.staff_id = apply.credit_people')
//            ->leftJoin(CrmCustPersoninch::tableName().' inch','inch.cust_id = info.cust_id')
//            ->leftJoin(HrStaff::tableName().' hr_2','hr_2.staff_id = inch.ccpich_personid')
            ->leftJoin(CrmSalearea::tableName().' cs','cs.csarea_id = info.cust_salearea')
            ->leftJoin(BsCompany::tableName().' bc','bc.company_id = apply.company_id')
            ->leftJoin(BsBusinessType::tableName().' type','type.business_type_id=limit.credit_type')
//            ->where(['!=','credit_status',LCrmCreditApply::STATUS_DELETE])
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
            'apply.credit_status' => $this->credit_status,
            'apply.company_id' => $this->company_id,
            'apply.credit_type' => $this->credit_type,
        ]);

        $query->andFilterWhere(['like', 'credit_code', trim($this->credit_code)])
            ->andFilterWhere(['like', 'info.cust_code', trim($this->cust_code)])
            ->andFilterWhere([
                'or',
                ['like', 'info.cust_sname', trim($this->cust_sname)],
                ['like', 'info.cust_sname', $trans->t2c(trim($this->cust_sname))],
                ['like', 'info.cust_sname', $trans->c2t(trim($this->cust_sname))],
            ]);

//        echo $query->createCommand()->getRawSql();

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 帐信查询列表
     */
    public function searchList($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'apply.credit_id',
            'apply.credit_code',
            'apply.credit_date',
            'apply.total_amount',
            'apply.credit_amount',
//            'apply.allow_amount',
            'apply.credit_status',
            'info.cust_sname',
            'info.cust_code',
            'bs_1.bnt_sname payment_clause',
            'bp_2.bsp_svalue payment_method',
            'bp_3.bsp_svalue initial_day',
            'bp_4.bsp_svalue pay_day',
            'bp_5.bsp_svalue cust_type',
            'bp_6.bsp_svalue currency',
            'hr_1.staff_name credit_people',
            'hr_2.staff_name manager',
//            'cs.csarea_name csarea_name',
            'bc.company_name company_name',
            "(CASE credit_status WHEN ".LCrmCreditApply::STATUS_PENDING." THEN '待提交' WHEN  ".LCrmCreditApply::STATUS_REVIEW." THEN '审核中' WHEN  ".LCrmCreditApply::STATUS_OVER." THEN '审核完成' WHEN  ".LCrmCreditApply::STATUS_REJECT." THEN '驳回' ELSE '其他' END) as status"
        ])->from(LCrmCreditApply::tableName().' apply')
            ->leftJoin(CrmCustomerInfo::tableName().' info','info.cust_id = apply.cust_id')
            ->leftJoin(LCrmCreditLimit::tableName().' limit','limit.credit_id = apply.credit_id')
            ->leftJoin(BsSettlement::tableName().' bs_1','bs_1.bnt_code = limit.payment_clause')
            ->leftJoin(BsPubdata::tableName().' bp_2','bp_2.bsp_id = limit.payment_method')
            ->leftJoin(BsPubdata::tableName().' bp_3','bp_3.bsp_id = limit.initial_day')
            ->leftJoin(BsPubdata::tableName().' bp_4','bp_4.bsp_id = limit.pay_day')
            ->leftJoin(BsPubdata::tableName().' bp_5','bp_5.bsp_id = info.cust_type')
            ->leftJoin(BsPubdata::tableName().' bp_6','bp_6.bsp_id = apply.currency')
            ->leftJoin(HrStaff::tableName().' hr_1','hr_1.staff_id = apply.credit_people')
            ->leftJoin(CrmCustPersoninch::tableName().' inch','inch.cust_id = info.cust_id')
            ->leftJoin(HrStaff::tableName().' hr_2','hr_2.staff_id = inch.ccpich_personid')
//            ->leftJoin(CrmSalearea::tableName().' cs','cs.csarea_id = info.cust_salearea')
            ->leftJoin(BsCompany::tableName().' bc','bc.company_id = apply.company_id')
            ->where(['or',['=','credit_status',LCrmCreditApply::STATUS_OVER],['=','credit_status',CrmCreditApply::STATUS_FREEZE]])
            ->andWhere(['in','apply.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->groupBy(['apply.credit_id'])
            ->orderBy(['apply.create_at'=>SORT_DESC])
        ;
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
            'apply.credit_status' => $this->credit_status,
        ]);

        $query->andFilterWhere(['like', 'credit_code', trim($this->credit_code)])
            ->andFilterWhere(['like', 'info.cust_code', trim($this->cust_code)])
            ->andFilterWhere([
                'or',
                ['like', 'info.cust_sname', trim($this->cust_sname)],
                ['like', 'info.cust_sname', $trans->t2c(trim($this->cust_sname))],
                ['like', 'info.cust_sname', $trans->c2t(trim($this->cust_sname))],
            ])
            ->andFilterWhere([
                'or',
                ['like', 'hr_1.staff_name', trim($this->apply_name)],
                ['like', 'hr_1.staff_name', $trans->t2c(trim($this->apply_name))],
                ['like', 'hr_1.staff_name', $trans->c2t(trim($this->apply_name))],
            ])
            ->andFilterWhere([
                'or',
                ['like', 'hr_2.staff_name', trim($this->manager)],
                ['like', 'hr_2.staff_name', $trans->t2c(trim($this->manager))],
                ['like', 'hr_2.staff_name', $trans->c2t(trim($this->manager))],
            ])
            ->andFilterWhere([
                'or',
                ['like', 'bc.company_name', trim($this->company_name)],
                ['like', 'bc.company_name', $trans->t2c(trim($this->company_name))],
                ['like', 'bc.company_name', $trans->c2t(trim($this->company_name))],
            ])
        ;

//        echo $query->createCommand()->getRawSql();

        return $dataProvider;
    }
}
