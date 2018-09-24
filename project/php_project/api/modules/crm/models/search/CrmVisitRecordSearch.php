<?php
/**
 * User: F1677929
 * Date: 2017/3/30
 */
namespace app\modules\crm\models\search;
use app\classes\Trans;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmReturnVisitShow;
use app\modules\crm\models\show\CrmVisitPlanShow;
use app\modules\crm\models\show\CrmVisitRecordShow;
use app\modules\hr\models\HrStaff;
use yii\data\ActiveDataProvider;
use Yii;
use yii\db\Query;
//客户拜访记录主表搜索模型
class CrmVisitRecordSearch extends CrmVisitRecord
{
    //搜索属性
    public $sih_code;
    public $customerName;
    public $customerType;
    public $customerManager;
    public $contactPerson;
    public $searchKeyword;
    public $cust_sname;
    public $cust_contacts;
    public $cust_tel2;
    public $cust_businesstype;
    public $member_source;
    public $member_reqflag;
    public $cust_ismember;
    public $member_type;
    public $cust_salearea;

    /*验证规则 -- F1678086  */
    public function rules()
    {
        return [
            [['sih_code','customerName','customerType','customerManager','contactPerson','searchKeyword','cust_sname','cust_tel2','cust_contacts','cust_businesstype','member_source','member_reqflag','cust_ismember','member_type','cust_salearea'], 'safe'],
        ];
    }
    /*验证规则 -- F1678086  */

    //选择客户-郭文聪
//    public function searchCustomerInfo($params)
//    {
//        $query=(new Query())->select([
//            CrmCustomerInfo::tableName().'.cust_id',//客户id
//            CrmCustomerInfo::tableName().'.cust_sname',//客户全称
//            'bp1.bsp_svalue customerType',//客户类型
//            'hs1.staff_name customerManager',//客户经理人
//            'hs1.staff_code customerManagerCode',//客户经理人
//            CrmSalearea::tableName().'.csarea_name',//营销区域
//            CrmCustomerInfo::tableName().'.cust_contacts',//联系人
//            CrmCustomerInfo::tableName().'.cust_tel2',//联系电话
//            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.CrmCustomerInfo::tableName().'.cust_adress) AS customerAddress',//客户地址
//            CrmCustomerInfo::tableName().'.cust_filernumber'
//        ])->from(CrmCustomerInfo::tableName())
//            //客户类型
//            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmCustomerInfo::tableName().'.cust_type')
//            //客户经理人
//            ->leftJoin(CrmCustPersoninch::tableName(),CrmCustPersoninch::tableName().'.cust_id='.CrmCustomerInfo::tableName().'.cust_id')
//            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_id='.CrmCustPersoninch::tableName().'.ccpich_personid')
//            //营销区域
//            ->leftJoin(CrmSalearea::tableName(),CrmSalearea::tableName().'.csarea_id='.CrmCustomerInfo::tableName().'.cust_salearea')
//            //客户地址
//            ->leftJoin(BsDistrict::tableName().' bd1','bd1.district_id='.CrmCustomerInfo::tableName().'.cust_district_2')
//            ->leftJoin(BsDistrict::tableName().' bd2','bd1.district_pid=bd2.district_id')
//            ->leftJoin(BsDistrict::tableName().' bd3','bd2.district_pid=bd3.district_id')
//            ->leftJoin(BsDistrict::tableName().' bd4','bd3.district_pid=bd4.district_id')
//            //客户状态表
//            ->leftJoin(CrmCustomerStatus::tableName(),CrmCustomerStatus::tableName().'.customer_id='.CrmCustomerInfo::tableName().'.cust_id')
//            ->where(['in',CrmCustomerInfo::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
//            ->andWhere([CrmCustomerStatus::tableName().'.sale_status'=>CrmCustomerStatus::STATUS_DEFAULT]);
//        if(!empty($params['staffId'])){
//            $query->andWhere([
//                'or',
//                [
//                    'and',
//                    [CrmCustPersoninch::tableName().'.ccpich_personid'=>$params['staffId']],
//                    [CrmCustPersoninch::tableName().'.ccpich_stype'=>CrmCustPersoninch::PERSONINCH_SALES],
//                    [CrmCustPersoninch::tableName().'.ccpich_status'=>CrmCustPersoninch::STATUS_DEFAULT]
//                ],
//                [
//                    'and',
//                    ['is',CrmCustPersoninch::tableName().'.ccpich_personid',null], // 过滤认领没反写客户表状态的被查出
//                    ['=',CrmCustomerInfo::tableName().'.personinch_status',CrmCustomerInfo::PERSONINCH_NO]
//                ]
//            ]);
//        }
//        $query->orderBy([CrmCustomerInfo::tableName().'.cust_id'=>SORT_DESC]);
////        $query->groupBy(CrmCustomerInfo::tableName().'.cust_id');
//        $dataProvider=new ActiveDataProvider([
//            'query'=>$query,
//            'pagination'=>[
//                'pageSize'=>$params['rows'],
//            ],
//        ]);
//        if(isset($params['keyword'])){
//            //处理简体繁体
//            $trans=new Trans();
//            $query->andFilterWhere([
//                'or',
//                //客户编码
//                ['like',CrmCustomerInfo::tableName().'.cust_filernumber',$params['keyword']],
//                //客户全称
//                ['like',CrmCustomerInfo::tableName().'.cust_sname',$params['keyword']],
//                ['like',CrmCustomerInfo::tableName().'.cust_sname',$trans->t2c($params['keyword'])],
//                ['like',CrmCustomerInfo::tableName().'.cust_sname',$trans->c2t($params['keyword'])],
//                //客户经理人
//                ['like','hs1.staff_name',$params['keyword']],
//                ['like','hs1.staff_name',$trans->t2c($params['keyword'])],
//                ['like','hs1.staff_name',$trans->c2t($params['keyword'])],
//            ]);
//        }
//        return $dataProvider;
//    }

    //选择计划-郭文聪
    public function searchVisitPlan($params)
    {
        $query=(new Query())->select([
            CrmVisitPlan::tableName().'.svp_id',//客户拜访计划ID
            CrmVisitPlan::tableName().'.svp_code',//计划编号
            CrmVisitPlan::tableName().'.svp_content',//计划内容
            CrmVisitPlan::tableName().'.start',//计划开始时间
            CrmVisitPlan::tableName().'.end',//结束时间
            "(CASE ".CrmVisitPlan::tableName().".svp_status WHEN ".CrmVisitPlan::STATUS_DEFAULT." THEN '待实施' WHEN ".CrmVisitPlan::VISIT_PLAN_COMPLETE." THEN '已实施' ELSE '删除' END) AS planStatus",
        ])->from(CrmVisitPlan::tableName())
            ->where([CrmVisitPlan::tableName().'.cust_id'=>$params['customerId']])
            ->andWhere([CrmVisitPlan::tableName().'.svp_status'=>[CrmVisitPlan::STATUS_BUSY,CrmVisitPlan::STATUS_DEFAULT]])
            ->andWhere(['in',CrmVisitPlan::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])]);
        if(!empty($params['staff_id'])){
            $staff = HrStaff::find()->select(['staff_code'])->where(['staff_id'=>$params['staff_id']])->one();
            $query->andWhere([CrmVisitPlan::tableName().'.svp_staff_code'=>$staff['staff_code']]);
        }
        $query->orderBy(['create_at'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows'],
            ],
        ]);
        if(isset($params['keyword'])){
            $trans=new Trans();
            $query->andFilterWhere([
                'or',
                ['like',CrmVisitPlan::tableName().'.svp_code',$params['keyword']],
                ['like',CrmVisitPlan::tableName().'.svp_content',$trans->t2c($params['keyword'])],
                ['like',CrmVisitPlan::tableName().'.svp_content',$params['keyword']],
                ['like',CrmVisitPlan::tableName().'.svp_content',$trans->c2t($params['keyword'])],
                ['like',"DATE_FORMAT(".CrmVisitPlan::tableName().".start,'%Y-%m-%d %H:%i:%s')",$params['keyword']],
                ['like',"DATE_FORMAT(".CrmVisitPlan::tableName().".end,'%Y-%m-%d %H:%i:%s')",$params['keyword']]
            ]);
        }
        return $dataProvider;
    }

    /**
     * 搜索拜访记录
     */
    public function searchRecord($params)
    {
        $trans = new Trans();
        $query = CrmVisitRecordShow::find()->where(['!=','sih_status',self::STATUS_DELETE])
            ->andWhere(['in','crm_visit_info.company_id',BsCompany::getIdsArr($params['companyId'])]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        //默认排序
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('crmCustomer');
        $query->joinWith('customerType');
        $query->joinWith('customerManager')->joinWith('customerManager.manager');
        $query->andFilterWhere(['like','sih_code',trim($this->sih_code)]);
        $query->andFilterWhere(['bs_pubdata.bsp_id' => $this->customerType,]);
        $query->andFilterWhere(['or',['like', "crm_bs_customer_info.cust_sname",trim($this->customerName) ],['like', "crm_bs_customer_info.cust_sname", $trans->t2c(trim($this->customerName)) ],['like', "crm_bs_customer_info.cust_sname", $trans->c2t(trim($this->customerName)) ]]);
        $query->andFilterWhere(['or',['like', "hr_staff.staff_name", trim($this->customerManager)],['like', "hr_staff.staff_name", $trans->t2c(trim($this->customerManager))],['like', "hr_staff.staff_name", $trans->c2t(trim($this->customerManager))]]);
        $query->andFilterWhere(['or',['like', "crm_bs_customer_info.cust_contacts", trim($this->contactPerson)],['like', "crm_bs_customer_info.cust_contacts", $trans->c2t(trim($this->contactPerson))],['like', "crm_bs_customer_info.cust_contacts", $trans->t2c(trim($this->contactPerson))]]);
        return $dataProvider;
    }


    /**
     * 搜索客户
     */
    public function searchCustomer($params)
    {
        $trans = new Trans();
        $query = CrmCustomerInfoShow::find()->where(['!=','cust_status',CrmCustomerInfo::CUSTOMER_INVALID]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        //默认排序
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['or',['like', "crm_bs_customer_info.cust_sname", trim($this->searchKeyword)],['like', "crm_bs_customer_info.cust_sname", $trans->t2c(trim($this->searchKeyword))],['like', "crm_bs_customer_info.cust_sname", $trans->c2t(trim($this->searchKeyword))]]);
        return $dataProvider;
    }

    /**
     * 搜索计划
     */
    public function searchPlan($params)
    {
        $trans = new Trans();
        $query = CrmVisitPlanShow::find();
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        //默认排序
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("crm_visit_plan.create_at desc");
        }
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('crmCustomer');
        $query->orFilterWhere(['like', "svp_code", trim($this->searchKeyword)])
            ->orFilterWhere(['or',['like', "cust_sname", trim($this->searchKeyword)],['like', "cust_sname", $trans->c2t(trim($this->searchKeyword))],['like', "cust_sname", $trans->t2c(trim($this->searchKeyword))]])
            ->orFilterWhere(['like binary', "plan_date", $this->searchKeyword])//搜索日期时要加上binary
//            ->orFilterWhere(['like', "svp_status", $this->searchKeyword])
            ->andFilterWhere(['crm_visit_plan.cust_id'=>$params['id']])
            ->andFilterWhere(['!=','crm_visit_plan.svp_status',CrmVisitPlan::STATUS_DELETE]);
        return $dataProvider;
    }

    /**
     * 搜索回访
     */
    public function searchReturnVisit($params)
    {
        $trans = new Trans();
//        $query = CrmReturnVisitShow::find()->joinWith('memberStatus')->distinct()->where(['!=','type',CrmVisitRecordChild::STATUS_DELETE])->andWhere(['!=','sih_status',CrmVisitRecord::STATUS_DELETE])->andWhere(['=','member_status',CrmCustomerStatus::STATUS_DEFAULT])->orWhere(['!=','investment_status',CrmCustomerStatus::STATUS_DEL])->andWhere(['in','crm_visit_info.company_id',BsCompany::getIdsArr($params['companyId'])]);
        // 转招商的不要显示
        $query = CrmReturnVisitShow::find()->joinWith('memberStatus')->distinct()->where(['!=','type',CrmVisitRecordChild::STATUS_DELETE])->andWhere(['!=','sih_status',CrmVisitRecord::STATUS_DELETE])->andWhere(['=','member_status',CrmCustomerStatus::STATUS_DEFAULT])->andWhere(['in','crm_visit_info.company_id',BsCompany::getIdsArr($params['companyId'])]);
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{

            $pageSize =10;

        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);
        if (!\Yii::$app->request->get('sort')){
            $query->orderBy("crm_bs_customer_info.create_at desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('visitInfoChild');

        $query->andFilterWhere([
            'cust_businesstype' => $this->cust_businesstype,
            'member_type' => $this->member_type,
            'member_source' => $this->member_source,
            'member_reqflag' => $this->member_reqflag,
            'cust_salearea' => $this->cust_salearea,
        ]);
        $query->andFilterWhere(['or',['like', 'cust_sname',trim($this->cust_sname)],['like', 'cust_sname',$trans->t2c(trim($this->cust_sname))],['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))]])
            ->andFilterWhere(['or',['like', 'cust_contacts', trim($this->cust_contacts)],['like', 'cust_contacts', $trans->t2c(trim($this->cust_contacts))],['like', 'cust_contacts', $trans->c2t(trim($this->cust_contacts))]])
            ->andFilterWhere(['like', 'cust_tel2', trim($this->cust_tel2)]);
//        return $query->createCommand()->rawSql;
        return $dataProvider;
    }

    /**
     * 搜索招商客户开发
     */
    public function searchInvestment($params)
    {
        $trans = new Trans();
        $query = CrmReturnVisitShow::find()->joinWith('memberStatus')->distinct()->where(['=','type',CrmVisitRecordChild::TYPE_INVESTMENT])->andWhere(['!=','sih_status',CrmVisitRecord::STATUS_DELETE])->andWhere(['!=','member_status',CrmCustomerStatus::STATUS_DEL])->andWhere(['in','crm_visit_info.company_id',BsCompany::getIdsArr($params['companyId'])]);

        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{

            $pageSize =10;

        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('visitInfoChild');

        $query->andFilterWhere([
            'cust_businesstype' => $this->cust_businesstype,
            'member_type' => $this->member_type,
            'member_source' => $this->member_source,
            'member_reqflag' => $this->member_reqflag,
            'cust_ismember' => $this->cust_ismember,
        ]);
        $query->andFilterWhere(['or',
            ['like', 'cust_sname',trim($this->cust_sname)],
            ['like', 'cust_sname',$trans->t2c(trim($this->cust_sname))],
            ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))],
            ['like', 'cust_contacts', trim($this->cust_contacts)],
            ['like', 'cust_contacts', $trans->t2c(trim($this->cust_contacts))],
            ['like', 'cust_contacts', $trans->c2t(trim($this->cust_contacts))],
            ['like', 'cust_tel2', $this->cust_tel2]
            ]);

        return $dataProvider;
    }
}