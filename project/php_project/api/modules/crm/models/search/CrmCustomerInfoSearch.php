<?php

namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\models\User;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsIndustrytype;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmC;
use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmMchpdtype;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\LCrmCreditApply;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmMemberShow;
use app\modules\crm\models\show\CustomerExportShow;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\BsCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class CrmCustomerInfoSearch extends CrmCustomerInfo
{
    public $startDate;
    public $endDate;
    public $cusMessage;
    public $custManager;
    public $property;
    public $searchKeyword;
    public $manageId;
    public $investment_status;
    public $mchpdtype_status;
    public $sale_status;
    public $mchpdtype_sname;
    public $cust_area;
    public $sts_id;
    public $member_businessarea;

    public function rules()
    {
        return [

            [['cust_sname', 'cusMessage', 'cust_type', 'cust_salearea', 'cust_filernumber', 'create_by', 'startDate', 'endDate', 'personinch_status', 'sale_status', 'cust_ename', 'cust_regdate', 'cust_veriftydate', 'member_regtime', 'member_certification', 'create_at', 'update_at', 'cust_tax_code', 'member_source', 'member_name', 'member_regweb', 'member_type', 'cust_tel2', 'member_reqflag', 'member_visitflag', 'member_compzipcode', 'custManager', 'property', 'cust_position', 'cust_contacts', 'cust_ismember', 'searchKeyword', 'cust_businesstype', 'manageId', 'investment_status', 'mchpdtype_status', 'mchpdtype_sname', 'member_reqitemclass', 'total_investment', 'shareholding_ratio', 'cust_area', 'sts_id', 'member_businessarea','cust_code'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 销售客户列表搜索
     */
//    public function search($params)
//    {
//        $query = CrmCustomerInfoShow::find()->joinWith('status')->andWhere(['is not', 'sale_status', null])->andWhere(['in', 'crm_bs_customer_info.company_id', BsCompany::getIdsArr($params['companyId'])])->orderBy('create_at DESC')->groupBy(['cust_sname', 'cust_id']);
//
//        if (isset($params['rows'])) {
//            $pageSize = $params['rows'];
//        } else {
//            if (isset($params['export'])) {
//                $pageSize = false;
//            } else {
//                $pageSize = 10;
//            }
//        }
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ]
//        ]);
//
//        $this->load($params);
//
//        if (!$this->validate()) {
//            return $dataProvider;
//        }
//
//        $trans = new Trans();
//
//        $query->joinWith("buildStaff");
//        $query->joinWith("manager");
//        $query->joinWith("custApply");
//        $query->andFilterWhere([
//            'cust_type' => $this->cust_type,
//            'cust_salearea' => $this->cust_salearea,
//            'personinch_status' => $this->personinch_status,
//            'sale_status' => $this->sale_status,
//            'ccpich_personid' => $this->custManager
//        ]);
//        $query->andFilterWhere(['or', ['like', 'cust_sname', trim($this->cust_sname)], ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))]])
//            ->andFilterWhere(['or', ['like', 'cust_filernumber', $trans->t2c(trim($this->cust_filernumber))], ['like', 'cust_filernumber', $trans->c2t(trim($this->cust_filernumber))]])
//            ->andFilterWhere(['or', ['like', 'u2.staff_name', trim($this->create_by)], ['like', 'u2.staff_name', $trans->t2c(trim($this->create_by))], ['like', 'u2.staff_name', $trans->c2t(trim($this->create_by))]]);
////            ->andFilterWhere(['or', ['like', 'hr_staff.staff_name', trim($this->custManager)], ['like', 'hr_staff.staff_name', $trans->t2c(trim($this->custManager))], ['like', 'hr_staff.staff_name', $trans->c2t(trim($this->custManager))]]);
//
//        if ($this->startDate && !$this->endDate) {
//            $query->andFilterWhere([">=", "create_at", $this->startDate]);
//        }
//        if ($this->endDate && !$this->startDate) {
//            $query->andFilterWhere(["<=", "create_at", date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
//        }
//        if ($this->endDate && $this->startDate) {
//            $query->andFilterWhere(["between", "create_at", $this->startDate, date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
//        }
//        return $dataProvider;
//    }
    /**
     * @param $params
     * @return ActiveDataProvider
     * 销售客户列表查询优化
     */
    public function search($params)
    {
        $sql = (new Query())->select([
            'info.cust_id',
            'info.cust_sname',              //客户名称
            'info.cust_filernumber',         //档案编号
            'info.cust_code',               //客户代码
            'info.cust_shortname',         //客户简称
            'info.personinch_status',         //认领状态
            'info.cust_tel2',               //手机号码
            'info.cust_email',               //邮箱
            'info.create_at create_aa',               //档案建立时间
            'date_format(info.create_at,"%Y-%m-%d") create_at',               //档案建立时间
            'credit_apply.credit_status credit_apply',      //账信申请状态
            'bp1.bsp_svalue custType',      //客户类型
            'bp2.bsp_svalue custLevel',     //客户等级
            'bp3.bsp_svalue custSource',        //客户来源
            'bp4.bsp_svalue latDemand',         //潜在需求
            'bp5.bsp_svalue businessType',      //经营类型
            'bp6.bsp_svalue regCurrency',       //注册货币
            'bp7.bsp_svalue dealCurrency',      //交易货币
            'bp8.bsp_svalue compvirtue',        //公司属性
            'bd1.district_name area',           //所在地区
            'category.catg_name category_name',         //需求类目
//            "manager(info.cust_id) as manager",    //客户经理人
//            'GROUP_CONCAT(hr_2.staff_name) as manager',

//            "test12(".$params['user_id'].",info.cust_id) as test122",
            'csa.csarea_name saleArea',         //营销区域
            'ccp.ccper_name ccpername',     //联系人
            'ccp.ccper_tel ccpertel',     //联系人电话
            'hr_1.staff_name createName',     //档案建立人
            'hr_1.staff_id hr_1_staff_id',     //档案建立人
            'apply.status apply_status',     //代码申请状态int

            'status.sale_status saleStatus',     //销售状态int
            "(CASE status.sale_status WHEN " . CrmCustomerStatus::STATUS_DEFAULT . " THEN '正常' WHEN  " . CrmCustomerStatus::STATUS_DEL . " THEN '已删除' ELSE '其他' END) as sale_status",//销售状态
            "(CASE info.member_visitflag WHEN " . CrmCustomerInfo::VISITFLAG_YES . " THEN '是' WHEN  " . CrmCustomerInfo::VISITFLAG_NO . " THEN '否' ELSE '其他' END) as visitFlag",//是否回访
            "(CASE info.personinch_status WHEN " . CrmCustomerInfo::PERSONINCH_YES . " THEN '已认领' WHEN  " . CrmCustomerInfo::PERSONINCH_NO . " THEN '未认领' ELSE '其他' END) as status",//认领状态
            "(CASE apply.status WHEN '10' THEN ' ' WHEN '20' THEN '审核中'  WHEN '30' THEN '审核中' WHEN '50' THEN '驳回' WHEN '60' THEN '已取消' ELSE info.cust_code END) as apply_code",//客户代码
        ])->from(CrmCustomerInfo::tableName() . ' info')
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', 'status.customer_id = info.cust_id')
            ->leftJoin(CrmCustomerApply::tableName() . ' apply', 'apply.cust_id = info.cust_id')
            ->leftJoin(LCrmCreditApply::tableName() . ' credit_apply', 'credit_apply.cust_id = info.cust_id')
            ->leftJoin(HrStaff::tableName() . ' hr_1', 'hr_1.staff_id = info.create_by')
            ->leftJoin(CrmCustPersoninch::tableName() . ' inch', 'inch.cust_id=info.cust_id and ccpich_stype=' . CrmCustPersoninch::PERSONINCH_SALES)
            ->leftJoin(HrStaff::tableName() . ' hr_2', 'hr_2.staff_id = inch.ccpich_personid')
            ->leftJoin(BsPubdata::tableName() . ' bp1', 'bp1.bsp_id=info.cust_type')
            ->leftJoin(BsPubdata::tableName() . ' bp2', 'bp2.bsp_id=info.cust_level')
            ->leftJoin(BsPubdata::tableName() . ' bp3', 'bp3.bsp_id=info.member_source')
            ->leftJoin(BsPubdata::tableName() . ' bp4', 'bp4.bsp_id=info.member_reqflag')
            ->leftJoin(BsPubdata::tableName() . ' bp5', 'bp5.bsp_id=info.cust_businesstype')
            ->leftJoin(BsPubdata::tableName() . ' bp6', 'bp6.bsp_id=info.member_regcurr')
            ->leftJoin(BsPubdata::tableName() . ' bp7', 'bp7.bsp_id=info.member_curr')
            ->leftJoin(BsPubdata::tableName() . ' bp8', 'bp8.bsp_id=info.cust_compvirtue')
            ->leftJoin('pdt.bs_category category', 'category.catg_id=info.member_reqitemclass')
            ->leftJoin(BsDistrict::tableName() . ' bd1', 'bd1.district_id=info.cust_area')
            ->leftJoin(CrmSalearea::tableName() . ' csa', 'csa.csarea_id=info.cust_salearea')
            ->leftJoin(CrmCustomerPersion::tableName() . ' ccp', 'ccp.cust_id = info.cust_id AND ccp.ccper_ismain = 1')
            ->where(['in', 'info.company_id', BsCompany::getIdsArr($params['companyId'])])
            ->andWhere(['is not', 'status.sale_status', null])
            ->groupBy('info.cust_id')
            ->orderBy('create_aa DESC');
        if ($params['isSuper'] == 0) {
//            $query
////                ->andWhere(['=',"user_auth(".$params['user_id'].",info.cust_id)",'true'])
//                ->andWhere(['in',"role_auth(".$params['user_id'].",info.cust_id)",['1','2']])
//            ;
            $query = (new Query())
                ->select([
                    'info1.cust_id',
                    'info1.hr_1_staff_id',
                    'info1.cust_sname',
                    'info1.cust_filernumber',
                    'info1.cust_code',
                    'info1.cust_shortname',
                    'info1.personinch_status',
                    'info1.cust_tel2',
                    'info1.cust_email',
                    'info1.create_aa',
                    'info1.create_at',
                    'info1.credit_apply',
                    'info1.custType',
                    'info1.custLevel',
                    'info1.custSource',
                    'info1.latDemand',
                    'info1.businessType',
                    'info1.regCurrency',
                    'info1.dealCurrency',
                    'info1.compvirtue',
                    'info1.area',
//                    'info1.manager',
                    'info1.saleArea',
                    'info1.ccpername',
                    'info1.ccpertel',
                    'info1.createName',
                    'info1.apply_status',
                    'info1.saleStatus',
                    'info1.sale_status',
                    'info1.visitFlag',
                    'info1.status',
                    'info1.apply_code',
                    "manager(info1.cust_id) as manager",
                    "manager_center(info1.cust_id) as manager_sale_center",    //客户经理人销售中心
                    "manager_depart(info1.cust_id) as manager_sale_depart",    //客户经理人销售部
                    'create_center(info1.hr_1_staff_id) as creator_sale_center',     //档案建立人销售中心
                    'create_depart(info1.hr_1_staff_id) as creator_sale_depart',     //档案建立人销售部
                ])
                ->from(['info1' => $sql])
                ->where(['in', "role_auth(" . $params['user_id'] . ",cust_id)", ['1', '2']]);
        } else {
            $query = (new Query())
                ->select([
                    'info1.cust_id',
                    'info1.hr_1_staff_id',
                    'info1.cust_sname',
                    'info1.cust_filernumber',
                    'info1.cust_code',
                    'info1.cust_shortname',
                    'info1.personinch_status',
                    'info1.cust_tel2',
                    'info1.cust_email',
                    'info1.create_aa',
                    'info1.create_at',
                    'info1.credit_apply',
                    'info1.custType',
                    'info1.custLevel',
                    'info1.custSource',
                    'info1.latDemand',
                    'info1.businessType',
                    'info1.regCurrency',
                    'info1.dealCurrency',
                    'info1.compvirtue',
                    'info1.area',
//                    'info1.manager',
                    'info1.saleArea',
                    'info1.ccpername',
                    'info1.ccpertel',
                    'info1.createName',
                    'info1.apply_status',
                    'info1.saleStatus',
                    'info1.sale_status',
                    'info1.visitFlag',
                    'info1.status',
                    'info1.apply_code',
                    "manager(info1.cust_id) as manager",
                    "manager_center(info1.cust_id) as manager_sale_center",    //客户经理人销售中心
                    "manager_depart(info1.cust_id) as manager_sale_depart",    //客户经理人销售部
                    'create_center(info1.hr_1_staff_id) as creator_sale_center',     //档案建立人销售中心
                    'create_depart(info1.hr_1_staff_id) as creator_sale_depart',     //档案建立人销售部
                ])
                ->from(['info1' => $sql]);;
        }


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
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $trans = new Trans();

        $sql->andFilterWhere([
            'cust_type' => $this->cust_type,
            'cust_salearea' => $this->cust_salearea,
            'personinch_status' => $this->personinch_status,
            'sale_status' => $this->sale_status,
            'ccpich_personid' => $this->custManager
        ]);
        $sql->andFilterWhere(['or', ['like', 'cust_sname', $trans->t2c(trim($this->cust_sname))], ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))]])
            ->andFilterWhere([
                'or',
                ['like', 'cust_filernumber', $trans->t2c(trim($this->cust_filernumber))],
                ['like', 'cust_filernumber', $trans->c2t(trim($this->cust_filernumber))],
                ['like', 'cust_code', $trans->t2c(trim($this->cust_filernumber))],
                ['like', 'cust_code', $trans->c2t(trim($this->cust_filernumber))]
            ])
        ;
//        echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    //潜在客户搜索
    public function searchPotentialCustomer($params)
    {
        $trans = new Trans();
        $query = CrmCustomerInfo::find()
            ->select([
                CrmCustomerInfo::tableName() . ".cust_id",
                CrmCustomerInfo::tableName() . ".cust_filernumber",
                CrmCustomerInfo::tableName() . ".cust_sname",
                CrmCustomerInfo::tableName() . ".cust_shortname",
                CrmCustomerInfo::tableName() . ".member_name",
                CrmCustomerInfo::tableName() . ".member_regtime",
                CrmCustomerInfo::tableName() . ".cust_tel1",
                CrmCustomerInfo::tableName() . ".cust_tel2",
                CrmCustomerInfo::tableName() . ".cust_contacts",
                CrmCustomerInfo::tableName() . ".cust_department",
                CrmCustomerInfo::tableName() . ".cust_position",
                CrmCustomerInfo::tableName() . ".cust_email",
                CrmCustomerInfo::tableName() . ".member_businessarea",
                "bp_1.bsp_svalue cust_businesstype", // 经营范围
                "bp_2.bsp_svalue member_reqflag",
                "bp_3.bsp_svalue member_source",
                "bp_4.bsp_svalue cust_function",
                "ctg.catg_name member_reqitemclass",
                "concat_ws(',',c.district_name,b.district_name,a.district_name) district",
                "hr_1.staff_name create_by",
                "if(crm_bs_customer_personinch.ccpich_status=0,'',hr_2.staff_id) allotman_staff_id",
                "if(crm_bs_customer_personinch.ccpich_status=0,'',hr_2.staff_name) allotman"
            ])
            ->leftJoin(CrmCustomerStatus::tableName(), CrmCustomerStatus::tableName() . ".customer_id=" . CrmCustomerInfo::tableName() . ".cust_id")
            ->leftJoin(BsPubdata::tableName() . " bp_1", "bp_1.bsp_id=" . CrmCustomerInfo::tableName() . ".cust_businesstype")// 经营范围
            ->leftJoin(BsPubdata::tableName() . " bp_2", "bp_2.bsp_id=" . CrmCustomerInfo::tableName() . ".member_reqflag")
            ->leftJoin(BsPubdata::tableName() . " bp_3", "bp_3.bsp_id=" . CrmCustomerInfo::tableName() . ".member_source")
            ->leftJoin(BsPubdata::tableName() . " bp_4", "bp_4.bsp_id=" . CrmCustomerInfo::tableName() . ".cust_function")
            ->leftJoin('pdt.bs_category ctg', "ctg.catg_no=" . CrmCustomerInfo::tableName() . ".member_reqitemclass")
            ->leftJoin(BsDistrict::tableName() . " a", "a.district_id" . "=" . self::tableName() . ".cust_district_2")
            ->leftJoin(BsDistrict::tableName() . " b", "a.district_pid=b.district_id")
            ->leftJoin(BsDistrict::tableName() . " c", "b.district_pid=c.district_id and c.district_id!=1")
            ->leftJoin(HrStaff::tableName() . " hr_1", "hr_1.staff_id=" . CrmCustomerInfo::tableName() . ".create_by")
            ->leftJoin(CrmCustPersoninch::tableName(), CrmCustPersoninch::tableName() . ".cust_id=" . CrmCustomerInfo::tableName() . ".cust_id and crm_bs_customer_personinch.ccpich_stype=0")
            ->leftJoin(HrStaff::tableName() . " hr_2", "hr_2.staff_id=" . CrmCustPersoninch::tableName() . ".ccpich_personid")
            ->where([
                CrmCustomerStatus::tableName() . ".potential_status" => 10
            ])
            ->orderBy(self::tableName() . ".cust_id desc");
        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params['rows']) ? $params['rows'] : ""
            ]
        ]);


        $province = empty($params["province"]) ? "" : $params["province"];
        $city = empty($params["city"]) ? "" : $params["city"];
        $district = empty($city) ? $province : $city;

        if ($district) {
            $query->andWhere([
                "or",
                ["a.district_id" => $district],
                ["b.district_id" => $district],
                ["c.district_id" => $district]
            ]);
        }

        if (!empty($params["saleman"])) {
            $data = CrmCustPersoninch::find()->select("cust_id")->where(["ccpich_stype" => 0, "ccpich_status" => 10])->asArray()->column();
            $query->andFilterWhere([
                "in", CrmCustomerInfo::tableName() . ".cust_id", $data
            ])->andFilterWhere([
                "or",
                ["hr_2.staff_id" => $params["saleman"]]
            ]);
        }

        if (!empty($params["is_allot"])) {
            $data = CrmCustPersoninch::find()->select("cust_id")->where(["ccpich_stype" => 0, "ccpich_status" => 10])->asArray()->column();
            if ($params["is_allot"] == "Y") {
                $query->andFilterWhere([
                    "in", CrmCustomerInfo::tableName() . ".cust_id", $data
                ]);
            } else {
                $query->andFilterWhere([
                    "not in", CrmCustomerInfo::tableName() . ".cust_id", $data
                ]);
            }
        }
        if (isset($params["cust_sname"])) {
            $query->andFilterWhere([
                "or",
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $params['cust_sname']],
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $trans->t2c($params['cust_sname'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $trans->c2t($params['cust_sname'])]
            ]);
        }


        if (isset($params["cust_contacts"])) {
            $query->andFilterWhere([
                "or",
                ["like", CrmCustomerInfo::tableName() . ".cust_contacts", $params['cust_contacts']],
                ["like", CrmCustomerInfo::tableName() . ".cust_contacts", $trans->t2c($params['cust_contacts'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_contacts", $trans->c2t($params['cust_contacts'])]
            ]);
        }


        if (isset($params["personinch_status"])) {
            $query->andFilterWhere([
                CrmCustPersoninch::tableName() . ".personinch_status" => $params['personinch_status'],
                "hr_2.staff_id" => isset($params["staff_id"]) ? $params["staff_id"] : ""
            ]);
        }

        $query->andFilterWhere([
            "bp_1.bsp_id" => isset($params["cust_businesstype"]) ? $params["cust_businesstype"] : "",
            "bp_2.bsp_id" => isset($params["member_reqflag"]) ? $params["member_reqflag"] : "",
            "bp_3.bsp_id" => isset($params["member_source"]) ? $params["member_source"] : "",
            CrmCustomerInfo::tableName() . ".member_businessarea" => isset($params["member_businessarea"]) ? $params["member_businessarea"] : "",
            CrmCustomerInfo::tableName() . ".cust_ismember" => isset($params["cust_ismember"]) ? $params["cust_ismember"] : ""
        ]);

        if (isset($params["customers"])) {
            $query->andFilterWhere(["not", ["in", CrmCustomerInfo::tableName() . ".cust_id", explode(",", $params["customers"])]]);
        }
        if (isset($params['keywords'])) {
            $query->andFilterWhere([
                "or",
                [CrmCustomerInfo::tableName() . '.cust_id' => $params['keywords']],
                [CrmCustomerInfo::tableName() . '.cust_id' => $trans->c2t($params['keywords'])],
                [CrmCustomerInfo::tableName() . '.cust_id' => $trans->t2c($params['keywords'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $params['keywords']],
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $trans->t2c($params['keywords'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $trans->c2t($params['keywords'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_shortname", $params['keywords']],
                ["like", CrmCustomerInfo::tableName() . ".cust_shortname", $trans->t2c($params['keywords'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_shortname", $trans->c2t($params['keywords'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_filernumber", $params['keywords']],
                ["like", CrmCustomerInfo::tableName() . ".cust_contacts", $params['keywords']],
                ["like", CrmCustomerInfo::tableName() . ".cust_contacts", $trans->t2c($params['keywords'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_contacts", $trans->c2t($params['keywords'])],
            ]);
        }

        if (isset($params['allot_kwds'])) {
            $query->andFilterWhere([
                "or",
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $params['allot_kwds']],
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $trans->t2c($params['allot_kwds'])],
                ["like", CrmCustomerInfo::tableName() . ".cust_sname", $trans->c2t($params['allot_kwds'])]
            ]);
        }

        if (!empty($params['cust_function'])) {
            $query->andFilterWhere([CrmCustomerInfo::tableName() . '.cust_function' => $params['cust_function']]);
        }
        $query->orderBy([CrmCustomerInfo::tableName() . ".cust_id" => SORT_DESC])
            ->asArray();
        return $dataProvider;
    }


    /**
     * 选择客户
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchSelect($params)
    {
        $trans = new Trans();
        $query = CrmCustomerInfoShow::find();
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
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['or', ['like', "cust_filernumber", trim($this->searchKeyword)], ['like', "cust_filernumber", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_filernumber", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_sname", trim($this->searchKeyword)], ['like', "cust_sname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_sname", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_shortname", trim($this->searchKeyword)], ['like', "cust_shortname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_shortname", $trans->c2t(trim($this->searchKeyword))]]);

        return $dataProvider;
    }

    /**
     * 选择潜在客户
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchSelectPoten($params)
    {
        $trans = new Trans();
        if ($params['ctype'] == 1) {
            $query = CrmCustomerInfoShow::find()->joinWith('status')->where(["!=", 'potential_status', CrmCustomerStatus::STATUS_DEL])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        } else {
            $query = CrmCustomerInfoShow::find()->joinWith('status')->where(["!=", 'investment_status', CrmCustomerStatus::STATUS_DEL])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        }
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
        $this->load($params);
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['or', ['like', "cust_filernumber", trim($this->searchKeyword)], ['like', "cust_filernumber", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_filernumber", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_sname", trim($this->searchKeyword)], ['like', "cust_sname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_sname", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_shortname", trim($this->searchKeyword)], ['like', "cust_shortname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_shortname", $trans->c2t(trim($this->searchKeyword))]]);

        return $dataProvider;
    }

    /**
     * 选择会员客户
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchSelectMember($params)
    {
        $trans = new Trans();
        $query = CrmMemberShow::find()->joinWith('status')->where(["!=", 'member_status', CrmCustomerStatus::STATUS_DEL])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
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
        $this->load($params);
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['or', ['like', "cust_filernumber", trim($this->searchKeyword)], ['like', "cust_filernumber", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_filernumber", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_sname", trim($this->searchKeyword)], ['like', "cust_sname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_sname", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_shortname", trim($this->searchKeyword)], ['like', "cust_shortname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_shortname", $trans->c2t(trim($this->searchKeyword))]]);
        return $dataProvider;
    }

    /**
     * 新增会员选择客户
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchCheckMember($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'info.cust_id',                  //id
            'info.cust_code',                  //客户申请代码
            'apply.credit_code',                  //申请单号
            'cust_sname',               //公司名称
            'member_name',              //用户名
            'member_regweb',            //注册网站
            'cust_sname',               //公司名称
            'cust_filernumber',         //档案编号
            'cust_shortname',           //公司简称
            'cust_tel1',                //公司电话
            'member_compzipcode',       //邮编
            'cust_contacts',            //联系人
            'cust_position',            //联系人职位
            'cust_tel2',                //手机号码
            'cust_email',               //邮箱
            'cust_district_2',          //地址
            'cust_adress',              //详细地址
            'cust_inchargeperson',      //法人代表
            'cust_regdate',             //注册时间
            'cust_regfunds',            //注册资金
            'member_regcurr',           //注册货币
            'cust_compvirtue',          //公司类型
            'member_source',            //客户来源
            'cust_businesstype',        //经营模式
            'member_curr',              //交易币种
            'member_compsum',           //年营业额
            'compsum_cur',              //年营业额币别
            'cust_pruchaseqty',         //年采购额
            'pruchaseqty_cur',          //年采购额币别
            'cust_personqty',           //员工人数
            'member_compreq',           //发票需求
            'member_reqflag',           //潜在需求
            'member_reqitemclass',      //需求类目
            'member_reqdesription',     //需求类别
            'member_compcust',          //主要客户
            'member_marketing',         //主要市场
            'member_compwebside',       //主页
            'member_businessarea',      //经营范围
            'member_remark',            //备注
            'district1.district_name area_name',    //地区名称
            'district2.district_id city_id',        //城市ID
            'district2.district_name city_name',    //城市名称
            'district3.district_id provice_id',     //省份ID
            'district3.district_name provice_name', //省份名称
            'district4.district_id country_id',     //国家ID
            'district4.district_name country_name', //国家名称
            'crmc.crtf_pkid',                       //会员认证ID
            'crmc.yn',                              //会员认证状态
            'apply.aid',                            //账信申请ID
            'bp_1.bsp_svalue memberType'            //会员类型名称
        ])
            ->from(CrmCustomerInfo::tableName() . ' info')
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', 'status.customer_id=info.cust_id')
            ->leftJoin(CrmC::tableName() . ' crmc', 'crmc.cust_id = info.cust_id')
            ->leftJoin(BsPubdata::tableName() . ' bp_1', 'bp_1.bsp_id = info.member_type')
            ->leftJoin(LCrmCreditApply::tableName() . ' apply', 'apply.cust_id = info.cust_id')
            ->leftJoin(BsDistrict::tableName() . ' district1', 'district1.district_id=info.cust_district_2')
            ->leftJoin(BsDistrict::tableName() . ' district2', 'district2.district_id=district1.district_pid')
            ->leftJoin(BsDistrict::tableName() . ' district3', 'district3.district_id=district2.district_pid')
            ->leftJoin(BsDistrict::tableName() . ' district4', 'district4.district_id=district3.district_pid')
            ->where(['or', ['!=', 'status.member_status', CrmCustomerStatus::STATUS_DEFAULT], ['is', 'status.member_status', null]]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 5;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        $this->load($params);
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("info.create_at desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['or', ['like', "cust_filernumber", trim($this->searchKeyword)], ['like', "cust_filernumber", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_filernumber", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_sname", trim($this->searchKeyword)], ['like', "cust_sname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_sname", $trans->c2t(trim($this->searchKeyword))]])
            ->orFilterWhere(['or', ['like', "cust_shortname", trim($this->searchKeyword)], ['like', "cust_shortname", $trans->t2c(trim($this->searchKeyword))], ['like', "cust_shortname", $trans->c2t(trim($this->searchKeyword))]]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider]
     * 客户列表,帐信申请选择客户
     */
    public function searchManage($params)
    {
        $query = (new Query())->select([
            CrmCustomerInfo::tableName() . '.*',//客户id
//            CrmCustomerInfo::tableName() . '.cust_sname',//客户全称
//            CrmCustomerInfo::tableName() . '.cust_shortname',//客户简称
//            CrmCustomerInfo::tableName() . '.cust_filernumber',//客户编号
//            CrmCustomerInfo::tableName() . '.cust_tel1',//电话
//            CrmCustomerInfo::tableName() . '.cust_inchargeperson',//公司负责人
//            CrmCustomerInfo::tableName() . '.cust_contacts',//联系人
//            CrmCustomerInfo::tableName() . '.cust_tel2',//联系电话
//            CrmCustomerInfo::tableName() . '.cust_email',//邮箱
//            CrmCustomerInfo::tableName() . '.create_at',//档案建立时间
            CrmCustomerApply::tableName() . '.status apply_status',
//            CrmCustomerInfo::tableName().'.visitFlag',//是否回访
            "(CASE " . CrmCustomerInfo::tableName() . ".cust_ismember WHEN " . CrmCustomerInfo::MEMBER_YES . " THEN '是' WHEN  " . CrmCustomerInfo::MEMBER_NO . " THEN '否' ELSE '' END) as custIsmember",//是否会员
            "(CASE " . CrmCustomerInfo::tableName() . ".cust_islisted WHEN 1 THEN '是' WHEN  0 THEN '否' ELSE '' END) as custIslisted",//是否上市公司
            "(CASE " . CrmCustomerInfo::tableName() . ".member_visitflag WHEN " . CrmCustomerInfo::VISITFLAG_YES . " THEN '是' WHEN  " . CrmCustomerInfo::VISITFLAG_NO . " THEN '否' ELSE '其他' END) as visitFlag",//是否回访
            "(CASE " . CrmCustomerInfo::tableName() . ".personinch_status WHEN " . CrmCustomerInfo::PERSONINCH_YES . " THEN '已认领' WHEN  " . CrmCustomerInfo::PERSONINCH_NO . " THEN '未认领' ELSE '其他' END) as status",//认领状态
            "(CASE " . CrmCustomerApply::tableName() . ".status WHEN '10' THEN ' ' WHEN '20' THEN '审核中'  WHEN '30' THEN '审核中' WHEN '50' THEN '驳回' WHEN '60' THEN '已取消' ELSE " . CrmCustomerInfo::tableName() . ".cust_code END) as apply_code",
//            CrmCustomerApply::tableName().'.applyno apply_code',
            'bp1.bsp_svalue custType',//客户类型
            'bp2.bsp_svalue custLevel',//会员等级
            'bp3.bsp_svalue custSource',//客户来源
            'bp4.bsp_svalue latDemand',//潜在需求
            'bp5.bsp_svalue businessType',//经营类型
            'bp6.bsp_svalue regCurrency',//注册货币
            'bp7.bsp_svalue dealCurrency',//交易货币
            'bp8.bsp_svalue compvirtue',//公司属性
            'bp9.bsp_svalue custCompscale',//公司规模
            'bp10.bsp_svalue memberType',//会员类型
            'bp11.bsp_svalue invoiceType',//申请发票类型
//            'hs1.staff_name manager',//客户经理人
            "group_concat(hs1.staff_name) manager",
            'hs2.staff_name sale_op',//销售op
            'hs3.staff_name createName',//档案建立人
            'bd5.district_name area',//所在地区
            'category.catg_name category_name', //需求类目
            CrmSalearea::tableName() . '.csarea_name saleArea',//营销区域
            BsIndustrytype::tableName() . ".idt_sname custIndustrytype",//行业类别
            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,' . CrmCustomerInfo::tableName() . '.cust_adress) AS customerAddress',//客户地址
            'IFNULL(' . CrmCustomerInfo::tableName() . '.update_at,' . CrmCustomerInfo::tableName() . '.create_at) AS sort_at'
        ])->from(CrmCustomerInfo::tableName())
            //客户类型
            ->leftJoin(BsPubdata::tableName() . ' bp1', 'bp1.bsp_id=' . CrmCustomerInfo::tableName() . '.cust_type')
            ->leftJoin(BsPubdata::tableName() . ' bp2', 'bp2.bsp_id=' . CrmCustomerInfo::tableName() . '.cust_level')
            ->leftJoin(BsPubdata::tableName() . ' bp3', 'bp3.bsp_id=' . CrmCustomerInfo::tableName() . '.member_source')
            ->leftJoin(BsPubdata::tableName() . ' bp4', 'bp4.bsp_id=' . CrmCustomerInfo::tableName() . '.member_reqflag')
            ->leftJoin(BsPubdata::tableName() . ' bp5', 'bp5.bsp_id=' . CrmCustomerInfo::tableName() . '.cust_businesstype')
            ->leftJoin(BsPubdata::tableName() . ' bp6', 'bp6.bsp_id=' . CrmCustomerInfo::tableName() . '.member_regcurr')
            ->leftJoin(BsPubdata::tableName() . ' bp7', 'bp7.bsp_id=' . CrmCustomerInfo::tableName() . '.member_curr')
            ->leftJoin(BsPubdata::tableName() . ' bp8', 'bp8.bsp_id=' . CrmCustomerInfo::tableName() . '.cust_compvirtue')
            ->leftJoin(BsPubdata::tableName() . ' bp9', 'bp9.bsp_id=' . CrmCustomerInfo::tableName() . '.cust_compscale')
            ->leftJoin(BsPubdata::tableName() . ' bp10', 'bp10.bsp_id=' . CrmCustomerInfo::tableName() . '.member_type')
            ->leftJoin(BsPubdata::tableName() . ' bp11', 'bp11.bsp_id=' . CrmCustomerInfo::tableName() . '.invoice_type')
            ->leftJoin(BsIndustrytype::tableName(), BsIndustrytype::tableName() . ".idt_id=" . CrmCustomerInfo::tableName() . ".cust_industrytype")
            //客户经理人
            ->leftJoin(CrmCustPersoninch::tableName(), CrmCustPersoninch::tableName() . '.cust_id=' . CrmCustomerInfo::tableName() . '.cust_id')
            //客户代码申请
            ->leftJoin(CrmCustomerApply::tableName(), CrmCustomerApply::tableName() . '.cust_id=' . CrmCustomerInfo::tableName() . '.cust_id')
            ->leftJoin(HrStaff::tableName() . ' hs1', 'hs1.staff_id=' . CrmCustPersoninch::tableName() . '.ccpich_personid')
            ->leftJoin(HrStaff::tableName() . ' hs2', 'hs2.staff_id=' . CrmCustPersoninch::tableName() . '.ccpich_personid2')
            ->leftJoin(HrStaff::tableName() . ' hs3', 'hs3.staff_id=' . CrmCustomerInfo::tableName() . '.create_by')
            //营销区域
            ->leftJoin(CrmSalearea::tableName(), CrmSalearea::tableName() . '.csarea_id=' . CrmCustomerInfo::tableName() . '.cust_salearea')
            //客户地址
            ->leftJoin(BsDistrict::tableName() . ' bd1', 'bd1.district_id=' . CrmCustomerInfo::tableName() . '.cust_district_2')
            ->leftJoin(BsDistrict::tableName() . ' bd5', 'bd5.district_id=' . CrmCustomerInfo::tableName() . '.cust_area')
            ->leftJoin(BsDistrict::tableName() . ' bd2', 'bd1.district_pid=bd2.district_id')
            ->leftJoin(BsDistrict::tableName() . ' bd3', 'bd2.district_pid=bd3.district_id')
            ->leftJoin(BsDistrict::tableName() . ' bd4', 'bd3.district_pid=bd4.district_id')
            ->leftJoin('pdt.bs_category category', 'category.catg_id=' . CrmCustomerInfo::tableName() . '.member_reqitemclass')
            //客户状态表
            ->leftJoin(CrmCustomerStatus::tableName(), CrmCustomerStatus::tableName() . '.customer_id=' . CrmCustomerInfo::tableName() . '.cust_id')
            ->where(['in', CrmCustomerInfo::tableName() . '.company_id', BsCompany::getIdsArr($params['companyId'])])
            ->andWhere([CrmCustomerStatus::tableName() . '.sale_status' => CrmCustomerStatus::STATUS_DEFAULT]);
        if (!empty($params['staffId'])) {
            $staffcode = HrStaff::find()->where(['staff_id' => $params['staffId']])->select('staff_code')->one();
            $staff = CrmEmployee::find()->where(['staff_code' => $staffcode['staff_code']])->select('isrule,leader_id')->one();
            if ($staff['isrule'] == 1) {
                $query->andWhere([
                    'or',
                    [
                        'and',
                        [CrmCustPersoninch::tableName() . '.ccpich_personid' => $params['staffId']],
                        [CrmCustPersoninch::tableName() . '.ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES],
                        [CrmCustPersoninch::tableName() . '.ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT]
                    ],
                    [
                        'and',
//                        ['is', CrmCustPersoninch::tableName() . '.ccpich_personid', null],// 过滤认领没反写客户表状态的被查出
//                        ['=', CrmCustomerInfo::tableName() . '.personinch_status', CrmCustomerInfo::PERSONINCH_NO]

                    ]
//                    ['is',CrmCustPersoninch::tableName().'.ccpich_personid',null]
                ]);
            } else {
                $code = CrmEmployee::find()->where(['staff_id' => $staff['leader_id']])->select('staff_code')->one();
                $staffId = HrStaff::find()->where(['staff_code' => $code['staff_code']])->select('staff_id')->one();
                $query->andWhere([
                    'or',
                    [
                        'and',
                        [CrmCustPersoninch::tableName() . '.ccpich_personid' => $staffId['staff_id']],
                        [CrmCustPersoninch::tableName() . '.ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES],
                    ],
//                    ['is',CrmCustPersoninch::tableName().'.ccpich_personid',null]
//                    ['=', CrmCustomerInfo::tableName() . '.personinch_status', CrmCustomerInfo::PERSONINCH_NO]
                ]);
            }

        }
        $query->orderBy(['sort_at' => SORT_DESC])->groupBy(CrmCustomerInfo::tableName() . '.cust_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['rows'],
            ],
        ]);
//        echo $params['manageId'];exit;
//        $query->andFilterWhere([CrmCustPersoninch::tableName() . '.ccpich_personid' => isset($params['manageId']) ? $params['manageId'] : '']);
        if (isset($params['searchKeyword'])) {
            //处理简体繁体
            $trans = new Trans();
            $query->andFilterWhere([
                'or',
                //客户全称
                ['like', CrmCustomerInfo::tableName() . '.cust_sname', trim($params['searchKeyword'])],
                ['like', CrmCustomerInfo::tableName() . '.cust_sname', $trans->t2c(trim($params['searchKeyword']))],
                ['like', CrmCustomerInfo::tableName() . '.cust_sname', $trans->c2t(trim($params['searchKeyword']))],
                //客户全称
                ['like', CrmCustomerInfo::tableName() . '.cust_shortname', trim($params['searchKeyword'])],
                ['like', CrmCustomerInfo::tableName() . '.cust_shortname', $trans->t2c(trim($params['searchKeyword']))],
                ['like', CrmCustomerInfo::tableName() . '.cust_shortname', $trans->c2t(trim($params['searchKeyword']))],
                //客户经理人
                ['like', CrmSalearea::tableName() . '.csarea_name', trim($params['searchKeyword'])],
                ['like', CrmSalearea::tableName() . '.csarea_name', $trans->t2c(trim($params['searchKeyword']))],
                ['like', CrmSalearea::tableName() . '.csarea_name', $trans->c2t(trim($params['searchKeyword']))],
                //客户代码
                ['like', CrmCustomerInfo::tableName() . '.cust_code', trim($params['searchKeyword'])],
                ['like', CrmCustomerInfo::tableName() . '.cust_code', $trans->t2c(trim($params['searchKeyword']))],
                ['like', CrmCustomerInfo::tableName() . '.cust_code', $trans->c2t(trim($params['searchKeyword']))],

                //客户编码
                ['like', CrmCustomerInfo::tableName() . '.cust_filernumber', trim($params['searchKeyword'])],
                ['like', CrmCustomerInfo::tableName() . '.cust_filernumber', $trans->t2c(trim($params['searchKeyword']))],
                ['like', CrmCustomerInfo::tableName() . '.cust_filernumber', $trans->c2t(trim($params['searchKeyword']))],
            ]);
        }
//        $a = clone $query;
//        echo $a->createCommand()->getRawSql();exit;
        return $dataProvider;
    }

    public function export($params)
    {
        $trans = new Trans();
        $query = CustomerExportShow::find();

        $this->load($params);

        if (!$this->validate()) {
            return $query;
        }
        $query->joinWith("manager");
        $query->joinWith("buildStaff");

        $query->andFilterWhere([
            'cust_type' => $this->cust_type,
            'cust_salearea' => $this->cust_salearea,
        ]);
        $query->andFilterWhere(['or', ['like', "cust_filernumber", trim($this->cust_filernumber)], ['like', "cust_filernumber", $trans->t2c(trim($this->cust_filernumber))], ['like', "cust_filernumber", $trans->c2t(trim($this->cust_filernumber))]])
            ->andFilterWhere(['or', ['like', "cust_sname", trim($this->cust_sname)], ['like', "cust_sname", $trans->t2c(trim($this->cust_sname))], ['like', "cust_sname", $trans->c2t(trim($this->cust_sname))]])
            ->andFilterWhere(['or', ['like', "u2.staff_name", trim($this->create_by)], ['like', "u2.staff_name", $trans->t2c(trim($this->create_by))], ['like', "u2.staff_name", $trans->c2t(trim($this->create_by))]]);
        if ($this->startDate && !$this->endDate) {
            $query->andFilterWhere([">=", "create_at", $this->startDate]);
        }
        if ($this->endDate && !$this->startDate) {
            $query->andFilterWhere(["<=", "create_at", date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        if ($this->endDate && $this->startDate) {
            $query->andFilterWhere(["between", "create_at", $this->startDate, date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        return $query;
    }

    /*会员列表*/
    public function searchMember($params)
    {
        $trans = new Trans();
        $query = CrmMemberShow::find()->joinWith('status')->where(["or", ["!=", 'member_status', CrmCustomerStatus::STATUS_DEL]])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])])->orderBy('create_at DESC');

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
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'member_regweb' => $this->member_regweb,
            'member_type' => $this->member_type,
            'member_source' => $this->member_source,
            'member_reqflag' => $this->member_reqflag,
            'member_visitflag' => $this->member_visitflag,
        ]);

        $query->andFilterWhere(['or', ['like', 'cust_sname', trim($this->cust_sname)], ['like', 'cust_sname', $trans->t2c(trim($this->cust_sname))], ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))]])
            ->andFilterWhere(['or', ['like', 'member_name', trim($this->member_name)], ['like', 'member_name', $trans->t2c(trim($this->member_name))], ['like', 'member_name', $trans->c2t(trim($this->member_name))]])
            ->andFilterWhere(['like', 'cust_tel2', trim($this->cust_tel2)]);
        if (!empty($params['keywords'])) {
            $query->andFilterWhere(['or', ['like', "cust_contacts", trim($params['keywords'])], ['like', "cust_contacts", $trans->t2c(trim($params['keywords']))], ['like', "cust_contacts", $trans->c2t(trim($params['keywords']))]])
                ->orFilterWhere(['or', ['like', "cust_sname", trim($params['keywords'])], ['like', "cust_sname", $trans->t2c(trim($params['keywords']))], ['like', "cust_sname", $trans->c2t(trim($params['keywords']))]])
                ->orFilterWhere(['or', ['like', "cust_shortname", trim($params['keywords'])], ['like', "cust_shortname", $trans->t2c(trim($params['keywords']))], ['like', "cust_shortname", $trans->c2t(trim($params['keywords']))]])
                ->orFilterWhere(['like', "cust_tel2", trim($params['keywords'])]);

        }
        //发信息、发邮件在继续添加中过滤已选中的客户
        if (isset($params["customers"])) {
            $query->andFilterWhere(["not", ["in", CrmCustomerInfo::tableName() . ".cust_id", explode(",", $params["customers"])]]);
        }
        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 会员开发任务列表
     */
    public function searchMemberDevelop($params)
    {
        $trans = new Trans();
//        $query = CrmCustomerInfoShow::find()->joinWith('status')->joinWith('personinch')->where(['!=', 'potential_status', CrmCustomerStatus::STATUS_DEL])->andWhere([CrmCustPersoninch::tableName() . '.ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        $query = (new Query())->select([
            'info.cust_id',             //id
            'info.cust_filernumber',        //客户编号
            'info.cust_sname',              //客户名称
            'info.cust_shortname',          //客户简称
            'info.cust_contacts',           //联系人
            'info.cust_position',           //职位
            'info.cust_tel2',               //联系方式
            'info.cust_email',              //邮箱
            'info.member_businessarea',     //经营范围说明
            'bp_1.bsp_svalue businessType',     //经营范围
            'bp_2.bsp_svalue custSource',       //客户来源
            'bp_3.bsp_svalue latDemand',        //潜在需求
            'bp_4.bsp_svalue regCurrency',        //注册货币
            'bp_5.bsp_svalue dealCurrency',        //交易货币
            'bp_6.bsp_svalue compvirtue',        //公司属性
            'category.catg_name category_name', //需求类目
            'hr_1.staff_name claimPerson',     //被分配者
            "(CASE member_visitflag WHEN 1 THEN '已拜访' ELSE '未拜访' END) as  visitFlag",//是否拜访
            "(CASE status.investment_status WHEN " . CrmCustomerStatus::STATUS_DEFAULT . " THEN '已转招商开发'  ELSE '未转招商开发' END) as investment	",//是否转招商开发
            "(CASE status.sale_status WHEN " . CrmCustomerStatus::STATUS_DEFAULT . " THEN '已转销售'  ELSE '未转销售' END) as sales	",//是否转销售
        ])->from(CrmCustomerInfo::tableName() . ' info')
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', 'status.customer_id=info.cust_id')
            ->leftJoin(CrmCustPersoninch::tableName() . ' personinch', 'personinch.cust_id=info.cust_id')
            ->leftJoin(HrStaff::tableName() . ' hr_1', 'hr_1.staff_id = personinch.ccpich_personid')
            ->leftJoin(BsCategory::tableName() . ' category', 'category.catg_id=info.member_reqitemclass')
            ->leftJoin(BsPubdata::tableName() . ' bp_1', 'bp_1.bsp_id=info.cust_businesstype')
            ->leftJoin(BsPubdata::tableName() . ' bp_2', 'bp_2.bsp_id=info.member_source')
            ->leftJoin(BsPubdata::tableName() . ' bp_3', 'bp_3.bsp_id=info.member_reqflag')
            ->leftJoin(BsPubdata::tableName() . ' bp_4', 'bp_4.bsp_id=info.member_regcurr')
            ->leftJoin(BsPubdata::tableName() . ' bp_5', 'bp_5.bsp_id=info.member_curr')
            ->leftJoin(BsPubdata::tableName() . ' bp_6', 'bp_6.bsp_id=info.cust_compvirtue')
            ->where(['!=', 'status.potential_status', CrmCustomerStatus::STATUS_DEL])
            ->andWhere(['personinch.ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT])
            ->andWhere(['personinch.ccpich_stype' => CrmCustPersoninch::PERSONINCH_DEFAULT])
            ->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        if (!empty($params['staffId'])) {
            $query->andWhere(['personinch.ccpich_personid' => $params['staffId']]);
//            $query->andWhere([CrmCustPersoninch::tableName() . '.ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT]);
        }
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
            ]
        ]);
        $this->load($params);
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("info.create_at desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cust_businesstype' => $this->cust_businesstype,
            'member_reqitemclass' => $this->member_reqitemclass,
            'member_source' => $this->member_source,
            'member_reqflag' => $this->member_reqflag,
            'member_visitflag' => $this->member_visitflag,
        ]);

        $query->andFilterWhere(['or', ['like', 'cust_sname', trim($this->cust_sname)], ['like', 'cust_sname', $trans->t2c(trim($this->cust_sname))], ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))]])
            ->andFilterWhere(['or', ['like', 'member_businessarea', trim($this->member_businessarea)], ['like', 'member_businessarea', $trans->t2c(trim($this->member_businessarea))], ['like', 'member_businessarea', $trans->c2t(trim($this->member_businessarea))]])
            ->andFilterWhere(['or', ['like', 'cust_contacts', trim($this->cust_contacts)], ['like', 'cust_contacts', $trans->t2c(trim($this->cust_contacts))], ['like', 'cust_contacts', $trans->c2t(trim($this->cust_contacts))]]);
        if (!empty($params['keywords'])) {
            $query->andFilterWhere(['or', ['like', "cust_contacts", trim($params['keywords'])], ['like', "cust_contacts", $trans->t2c(trim($params['keywords']))], ['like', "cust_contacts", $trans->c2t(trim($params['keywords']))]])
                ->orFilterWhere(['or', ['like', "cust_sname", trim($params['keywords'])], ['like', "cust_sname", $trans->t2c(trim($params['keywords']))], ['like', "cust_sname", $trans->c2t(trim($params['keywords']))]])
                ->orFilterWhere(['or', ['like', "cust_shortname", trim($params['keywords'])], ['like', "cust_shortname", $trans->t2c(trim($params['keywords']))], ['like', "cust_shortname", $trans->c2t(trim($params['keywords']))]])
                ->orFilterWhere(['like', "cust_tel2", trim($params['keywords'])]);

        }
        //发信息、发邮件在继续添加中过滤已选中的客户
        if (isset($params["customers"])) {
            $query->andFilterWhere(["not", ["in", "info.cust_id", explode(",", $params["customers"])]]);
        }
//        echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /*
     * 招商客户搜索
     */
    public function searchInvestmentCustomer($params)
    {
        $trans = new Trans();
        $query = (new Query())
            ->select([
                'info.cust_id',             //id
                'info.cust_filernumber',    //编号
                'info.cust_sname',          //全名
                'info.cust_shortname',      //简称
                'district.district_name cust_area',       //公司所在地区
                'info.cust_contacts',       //联系人
                'info.cust_position',       //职位
                'bp_1.bsp_svalue member_businessarea',//经营范围
                'info.cust_tel2',           //手机号码
                'info.cust_email',          //邮箱
                'bp_1.bsp_svalue cust_businesstype',//经营模式
                'bp_2.bsp_svalue member_source',   //客户来源
                'bp_3.bsp_svalue member_reqflag',    //潜在需求
                'category.catg_name member_reqitemclass', //需求类目
                'hr_1.staff_name create_name',        //创建人
                'info.member_reqdesription', //需求类别
                "(CASE personinch.ccpich_status WHEN 10 THEN '已分配' ELSE '未分配' END) as  assign_status",//是否分配
                'hr_2.staff_name personinch_name',        //分配人
                "(CASE info.cust_ismember WHEN " . CrmCustomerInfo::MEMBER_NO . " THEN '否' WHEN  " . CrmCustomerInfo::MEMBER_YES . " THEN '是' ELSE '其他' END) as cust_ismember",//是否会员
                'bp_4.bsp_svalue member_type',        //会员类别
                'info.member_name',          //会员名称
                "left (info.member_regtime,10) as member_regtime",          //注册时间
//                "(CASE status.investment_status WHEN ".CrmCustomerInfo::INVESTEMNT_UN." THEN '未开发' WHEN  ".CrmCustomerInfo::INVESTEMNT_IN." THEN '开发中' WHEN  ".CrmCustomerInfo::INVESTEMNT_SUCC." THEN '开发完成' WHEN  ".CrmCustomerInfo::INVESTEMNT_FAILURE." THEN '开发失败' ELSE '其他' END) as investemnt_status",//状态
            ])
            ->from(CrmCustomerInfo::tableName() . ' info')
            ->leftJoin(BsPubdata::tableName() . ' bp_1', 'bp_1.bsp_id=info.cust_businesstype')
            ->leftJoin(BsPubdata::tableName() . ' bp_2', 'bp_2.bsp_id=info.member_source')
            ->leftJoin(BsPubdata::tableName() . ' bp_3', 'bp_3.bsp_id=info.member_reqflag')
            ->leftJoin(BsPubdata::tableName() . ' bp_4', 'bp_4.bsp_id=info.member_type')
            ->leftJoin('pdt.bs_category category', 'category.catg_id=info.member_reqitemclass')
            ->leftJoin(BsDistrict::tableName() . ' district', 'district.district_id=info.cust_area')
            ->leftJoin(HrStaff::tableName() . ' hr_1', 'hr_1.staff_id=info.create_by')
            ->leftJoin(CrmCustPersoninch::tableName() . ' personinch', 'personinch.cust_id=info.cust_id and personinch.ccpich_stype=' . CrmCustPersoninch::PERSONINCH_INVEST)
            ->leftJoin(CrmMchpdtype::tableName() . ' mchpdtype', 'mchpdtype.id=personinch.ccpich_personid and ccpich_status=' . CrmMchpdtype::STATUS_DEFAULT)
            ->leftJoin(HrStaff::tableName() . ' hr_2', 'hr_2.staff_code=mchpdtype.staff_code')
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', "status.customer_id=info.cust_id")
            ->andWhere(['=', 'status.investment_status', CrmCustomerStatus::INVESTMENT_SUCC])
//            ->andWhere(['=', 'hr_2.staff_id', $params['companyId']])
            ->groupBy('info.cust_id')
            ->orderBy(['info.create_at' => SORT_DESC]);

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
        if (isset($params['keywords'])) {
            $query->andFilterWhere([
                "or",
                ["like", "cust_sname", trim($params['keywords'])],
                ["like", "cust_sname", $trans->t2c(trim($params['keywords']))],
                ["like", "cust_sname", $trans->c2t(trim($params['keywords']))],
                ["like", "cust_shortname", trim($params['keywords'])],
                ["like", "cust_shortname", $trans->t2c(trim($params['keywords']))],
                ["like", "cust_shortname", $trans->c2t(trim($params['keywords']))],
                ["like", "cust_filernumber", trim($params['keywords'])],
                ["like", "cust_contacts", trim($params['keywords'])],
                ["like", "cust_contacts", $trans->t2c(trim($params['keywords']))],
                ["like", "cust_contacts", $trans->c2t(trim($params['keywords']))],
            ]);
        }
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->mchpdtype_status === '0') {
            $personinch = CrmCustPersoninch::find()->select('cust_id')->where(['ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT])->andWhere(['ccpich_stype' => CrmCustPersoninch::PERSONINCH_INVEST])->asArray()->all();
            $custArr = [];
            foreach ($personinch as $val) {
                $custArr[] = $val['cust_id'];
            }
            $query->andFilterWhere(['not in', 'info.cust_id', $custArr]);
        } elseif ($this->mchpdtype_status === '10') {

            $query->andFilterWhere(['=', 'personinch.ccpich_status', $this->mchpdtype_status]);
        }

        $query->andFilterWhere(['or', ['like', 'cust_sname', trim($this->cust_sname)],
            ['like', 'cust_sname', $trans->t2c(trim($this->cust_sname))], ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))],
        ]);
        $query->andFilterWhere(['or', ['like', 'cust_sname', trim($this->member_reqdesription)],
            ['like', 'cust_sname', $trans->t2c(trim($this->member_reqdesription))], ['like', 'cust_sname', $trans->c2t(trim($this->member_reqdesription))],
        ]);
        $query->andFilterWhere(['=', 'info.member_type', $this->member_type]);
        $query->andFilterWhere(['=', 'info.member_reqitemclass', $this->member_reqitemclass]);
        $query->andFilterWhere(['=', 'cust_ismember', $this->cust_ismember]);
        $query->andFilterWhere(['=', 'member_source', $this->member_source]);
        $query->andFilterWhere(['=', 'member_reqflag', $this->member_reqflag]);
//        $query->andFilterWhere(['=', 'cust_businesstype',$this->cust_businesstype]);
        $query->andFilterWhere(['or',
            ['like', 'hr_2.staff_name', $trans->t2c(trim($this->mchpdtype_sname))], ['like', 'hr_2.staff_name', $trans->c2t(trim($this->mchpdtype_sname))],
        ]);
//        $query->andFilterWhere(['=', 'assign_status',$this->mchpdtype_status]);
        //发信息、发邮件在继续添加中过滤已选中的客户
        if (isset($params["customers"])) {
            $query->andFilterWhere(["not", ["in", "info.cust_id", explode(",", $params["customers"])]]);
        }

        return $dataProvider;
    }

    /*
     * 招商开发客户搜索
     */
    public function searchInvestmentDvelopment($params)
    {
        $trans = new Trans();
        $query = (new Query())
            ->select([
                'info.cust_id',             //id
                'info.cust_filernumber',    //编号
                'info.cust_sname',          //全名
                'info.cust_shortname',      //简称
                'district.district_name cust_area',       //公司所在地区
                'info.cust_contacts',       //联系人
                'info.cust_position',       //职位
                'info.cust_tel2',           //手机号码
                'info.cust_tel1',           //号码
                'info.cust_email',          //邮箱
                'bp_1.bsp_svalue cust_businesstype',//经营模式
                'bp_2.bsp_svalue member_source',   //客户来源
                'bp_3.bsp_svalue member_reqflag',    //潜在需求
                'category.catg_name member_reqitemclass', //需求类目
                'category.catg_id reqitemclass', //需求类目ID
                'info.member_reqdesription', //需求类别
                'hr_1.staff_name create_name',        //创建人
                "(CASE personinch.ccpich_status WHEN 10 THEN '已分配' ELSE '未分配' END) as  assign_status",//是否分配
                'hr_2.staff_name personinch_name',        //分配人
                "(CASE info.cust_ismember WHEN " . CrmCustomerInfo::MEMBER_NO . " THEN '否' WHEN  " . CrmCustomerInfo::MEMBER_YES . " THEN '是' ELSE '其他' END) as cust_ismember",//是否会员
                'bp_4.bsp_svalue member_type',        //会员类别
                'info.member_name',          //会员名
                "left (info.member_regtime,10) as member_regtime",          //注册时间
                "(CASE status.investment_status WHEN " . CrmCustomerInfo::INVESTEMNT_UN . " THEN '未开发' WHEN  " . CrmCustomerInfo::INVESTEMNT_IN . " THEN '开发中' WHEN  " . CrmCustomerInfo::INVESTEMNT_SUCC . " THEN '开发完成' WHEN  " . CrmCustomerInfo::INVESTEMNT_FAILURE . " THEN '开发失败' ELSE '其他' END) as investemnt_status",//状态

            ])
            ->from(CrmCustomerInfo::tableName() . ' info')
            ->leftJoin(BsPubdata::tableName() . ' bp_1', 'bp_1.bsp_id=info.cust_businesstype')
            ->leftJoin(BsPubdata::tableName() . ' bp_2', 'bp_2.bsp_id=info.member_source')
            ->leftJoin(BsPubdata::tableName() . ' bp_3', 'bp_3.bsp_id=info.member_reqflag')
            ->leftJoin(BsPubdata::tableName() . ' bp_4', 'bp_4.bsp_id=info.member_type')
            ->leftJoin('pdt.bs_category category', 'category.catg_id=info.member_reqitemclass')
            ->leftJoin(BsDistrict::tableName() . ' district', 'district.district_id=info.cust_area')
            ->leftJoin(HrStaff::tableName() . ' hr_1', 'hr_1.staff_id=info.create_by')
            ->leftJoin(CrmCustPersoninch::tableName() . ' personinch', 'personinch.cust_id=info.cust_id and personinch.ccpich_stype=' . CrmCustPersoninch::PERSONINCH_INVEST)
            ->leftJoin(CrmMchpdtype::tableName() . ' mchpdtype', 'mchpdtype.id=personinch.ccpich_personid and ccpich_status=' . CrmMchpdtype::STATUS_DEFAULT)
            ->leftJoin(HrStaff::tableName() . ' hr_2', 'hr_2.staff_code=mchpdtype.staff_code')
            ->leftJoin(CrmCustomerStatus::tableName() . ' status', "status.customer_id=info.cust_id")
            ->andWhere(['!=', 'status.investment_status', CrmCustomerStatus::STATUS_DEL])
            ->andWhere(['!=', 'status.investment_status', CrmCustomerInfo::INVESTEMNT_SUCC])
//            ->andWhere(['=', 'personinch.ccpich_stype', 20])
            ->groupBy('info.cust_id')
            ->orderBy(['info.create_at' => SORT_DESC]);

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

        if (isset($params['keywords'])) {
            $query->andFilterWhere([
                "or",
                ["like", "cust_sname", trim($params['keywords'])],
                ["like", "cust_sname", $trans->t2c(trim($params['keywords']))],
                ["like", "cust_sname", $trans->c2t(trim($params['keywords']))],
                ["like", "cust_shortname", trim($params['keywords'])],
                ["like", "cust_shortname", $trans->t2c(trim($params['keywords']))],
                ["like", "cust_shortname", $trans->c2t(trim($params['keywords']))],
                ["like", "cust_filernumber", trim($params['keywords'])],
                ["like", "cust_contacts", trim($params['keywords'])],
                ["like", "cust_contacts", $trans->t2c(trim($params['keywords']))],
                ["like", "cust_contacts", $trans->c2t(trim($params['keywords']))],
            ]);
        } else {
            $query->andFilterWhere([
                'or',
                ['like', 'cust_sname', trim($this->cust_sname)],
                ['like', 'cust_sname', $trans->t2c(trim($this->cust_sname))],
                ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))],
            ]);
            $query->andFilterWhere(['or', ['like', 'hr_2.staff_name', trim($this->mchpdtype_sname)],
                ['like', 'hr_2.staff_name', $trans->t2c(trim($this->mchpdtype_sname))], ['like', 'hr_2.staff_name', $trans->c2t(trim($this->mchpdtype_sname))],
            ]);
            if ($this->mchpdtype_status === '0') {
                $personinch = CrmCustPersoninch::find()->select('cust_id')->where(['ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT])->andWhere(['ccpich_stype' => CrmCustPersoninch::PERSONINCH_INVEST])->asArray()->all();
                $custArr = [];
                foreach ($personinch as $val) {
                    $custArr[] = $val['cust_id'];
                }
                $query->andFilterWhere(['not in', 'info.cust_id', $custArr]);
            } elseif ($this->mchpdtype_status === '10') {

                $query->andFilterWhere(['=', 'personinch.ccpich_status', $this->mchpdtype_status]);
            }
            $query->andFilterWhere(['or', ['like', 'cust_contacts', trim($this->cust_contacts)],
                ['like', 'cust_contacts', $trans->t2c(trim($this->cust_contacts))], ['like', 'cust_contacts', $trans->c2t(trim($this->cust_contacts))],
            ]);
        }
        $query->andFilterWhere(['=', 'status.investment_status', $this->investment_status]);
        $query->andFilterWhere(['=', 'cust_businesstype', $this->cust_businesstype]);
        $query->andFilterWhere(['=', 'member_source', $this->member_source]);
        $query->andFilterWhere(['=', 'member_reqflag', $this->member_reqflag]);
        //发信息、发邮件在继续添加中过滤已选中的客户
        if (isset($params["customers"])) {
            $query->andFilterWhere(["not", ["in", "info.cust_id", explode(",", $params["customers"])]]);
        }
        return $dataProvider;
    }

    /*
     * 客户资料查询
     */
    public function searchAll($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            "ci.cust_id",
            "ci.cust_filernumber", // 系统编号
            "ci.cust_code", // 客户代码
            "ci.cust_sname", // 客户名称
            "ci.cust_shortname", // 客户简称
            "bp_1.bsp_svalue cust_type", // 类型
            "bp_2.bsp_svalue cust_level", // 等级
            "ci.cust_tel1", // 公司电话
            "ci.cust_fax", // 传真
            "ci.cust_inchargeperson", // 负责人 法人
            "ci.member_regweb",  // 公司网址
            "ci.cust_contacts",  // 联系人
            "ci.cust_tel1",  // 联系电话
            "hr2.staff_email",  // 邮箱
            "hr.staff_name custManager",  // 客户经理人
            "sa.csarea_name cust_salearea",  // 所在军区
            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,ci.cust_adress) AS customerAddress',// 公司地址
            "bp_3.bsp_svalue cust_compscale", // 公司规模
            "bc.catg_name member_reqitemclass", // 需求类目
            'bp_6.bsp_svalue cust_businesstype',//经营类型
            'bd5.district_name',//所在地区
            "bi.idt_sname cust_industrytype", // 行业类型
            "bp_4.bsp_svalue cust_compvirtue", // 公司属性
            "ci.cust_personqty", // 员工人数
            "ci.cust_regdate", // 注册时间
            "ci.cust_regfunds", // 注册资金
            "ci.cust_islisted", // 是否上市
            "ci.member_compsum", // 年营业额
            "ci.cust_tax_code", // 税籍编码
            "if(cs.potential_status=10,'潜在客户',if(cs.investment_status!=0 and cs.investment_status is not null,'招商客户',if(cs.sale_status!=0,'销售客户','??'))) cust_t", // 客户属性
            "ci.cust_ismember", // 是否会员
            'crmc.YN',//认证状态
            "bp_5.bsp_svalue",//会员类型
            "ci.member_name", // 会员名
            "ci.cust_email",  // 注册邮箱
            "ci.cust_tel2", // 注册手机
        ])->from(['ci' => CrmCustomerInfo::tableName()])
            ->leftJoin(CrmCustomerStatus::tableName() . " cs", "cs.customer_id=ci.cust_id")
            ->leftJoin(CrmCustPersoninch::tableName() . " cp", "cp.cust_id=ci.cust_id")
            ->leftJoin(HrStaff::tableName() . " hr", "cp.ccpich_personid=hr.staff_id")// 客户经理人
            ->leftJoin(HrStaff::tableName() . " hr2", "ci.staff_id=hr2.staff_id")// 找到邮箱
            ->leftJoin(BsPubdata::tableName() . " bp_1", "bp_1.bsp_id=ci.cust_type")
            ->leftJoin(BsPubdata::tableName() . " bp_2", "bp_2.bsp_id=ci.cust_level")
            ->leftJoin(BsPubdata::tableName() . " bp_3", "bp_3.bsp_id=ci.cust_compscale")
            ->leftJoin(BsPubdata::tableName() . " bp_4", "bp_4.bsp_id=ci.cust_compvirtue")
            ->leftJoin(BsPubdata::tableName() . " bp_5", "bp_5.bsp_id=ci.member_type")
            ->leftJoin(BsPubdata::tableName() . " bp_6", "bp_5.bsp_id=ci.cust_businesstype")
            ->leftJoin("pdt.bs_category bc", "bc.catg_no=ci.member_reqitemclass")
            ->leftJoin(BsDistrict::tableName() . ' bd1', 'bd1.district_id=ci.cust_district_2')
            ->leftJoin(BsDistrict::tableName() . ' bd2', 'bd1.district_pid=bd2.district_id')
            ->leftJoin(BsDistrict::tableName() . ' bd3', 'bd2.district_pid=bd3.district_id')
            ->leftJoin(BsDistrict::tableName() . ' bd4', 'bd3.district_pid=bd4.district_id')
            ->leftJoin(BsDistrict::tableName() . ' bd5', 'bd5.district_id=ci.cust_area')
            ->leftJoin(CrmC::tableName() . ' crmc', 'crmc.cust_id = ci.cust_id')
            ->leftJoin(CrmSalearea::tableName() . " sa", "sa.csarea_id=ci.cust_salearea")
            ->leftJoin(BsIndustrytype::tableName() . " bi", "bi.idt_id=ci.cust_industrytype")
            ->where([
                'and',
                ['or', ['!=', 'cust_status', CrmCustomerInfo::CUSTOMER_INVALID], ['is', 'cust_status', null]],
                ['!=', "if(cs.potential_status=10,'潜在客户',if(cs.investment_status!=0 and cs.investment_status is not null,'招商客户',if(cs.sale_status!=0,'销售客户','')))", ''] // 排除数据表中不属于任何一种客户的无效数据
            ]);

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params['rows']) ? $params['rows'] : ""
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'or',
            ['like', 'cust_sname', trim($this->cust_sname)],
            ['like', 'cust_sname', $trans->c2t(trim($this->cust_sname))],
            ['like', 'cust_sname', $trans->t2c(trim($this->cust_sname))]
        ]);
        $query->andFilterWhere([
            'or',
            ['like', 'hr.staff_name', trim($this->custManager)],
            ['like', 'hr.staff_name', $trans->c2t(trim($this->custManager))],
            ['like', 'hr.staff_name', $trans->t2c(trim($this->custManager))]
        ]);
        if ($this->cust_ismember == '是') {
            $this->cust_ismember = 1;
        }

        if ($this->cust_ismember == '否') {
            $this->cust_ismember = 0;
        }
        $query->andFilterWhere([
            'cust_type' => $this->cust_type,
            'cust_salearea' => $this->cust_salearea,
            'bd3.district_id' => $this->cust_area,
            'ci.cust_ismember' => $this->cust_ismember,
        ]);
        $query->andFilterWhere([
            'like', 'if(cs.potential_status=10,\'潜在客户\',if(cs.investment_status!=0 and cs.investment_status is not null,\'招商客户\',if(cs.sale_status!=0,\'销售客户\',\'??\')))', $this->property,
        ]);
//         return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /*
     * 客户资料查询
     */
    public function searchAllSale($params)
    {
        $trans = new Trans();
        if ($params['isSuper'] == 0) {
            $query = (new Query())->select([
                "ci.cust_id",
                "ci.cust_filernumber", // 系统编号
                "ci.cust_code", // 客户代码
                "ci.cust_sname", // 客户名称
                "ci.cust_shortname", // 客户简称
                "bp_1.bsp_svalue cust_type", // 类型
                "bp_2.bsp_svalue cust_level", // 等级
                "ci.cust_tel1", // 公司电话
                "ci.cust_fax", // 传真
                "ci.cust_inchargeperson", // 负责人 法人
                "ci.member_regweb",  // 公司网址
                "ci.cust_contacts",  // 联系人
                "ci.cust_tel1",  // 联系电话
                "ci.cust_email",  // 邮箱
                "hr.staff_name custManager",  // 客户经理人
                "sa.csarea_name cust_salearea",  // 所在军区
                'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,ci.cust_adress) AS customerAddress',// 公司地址
                "bp_3.bsp_svalue cust_compscale", // 公司规模
                "bc.catg_name member_reqitemclass", // 需求类目
                "bi.idt_sname cust_businesstype", // 经营类型
                "bp_4.bsp_svalue cust_compvirtue", // 公司属性
                "ci.cust_personqty", // 员工人数
                "ci.cust_regdate", // 注册时间
                "ci.cust_regfunds", // 注册资金
                "ci.cust_islisted", // 是否上市
                "ci.member_compsum", // 年营业额
                "ci.cust_tax_code", // 税籍编码
                "if(cs.potential_status=10,'潜在客户',if(cs.investment_status!=0 and cs.investment_status is not null,'招商客户',if(cs.sale_status!=0,'销售客户','??'))) cust_t", // 客户属性
                "ci.cust_ismember", // 是否会员
                "ci.member_name", // 会员名
                "ci.cust_email",  // 邮箱
                "ci.cust_tel2", // 注册手机
            ])->from(['ci' => CrmCustomerInfo::tableName()])
                ->leftJoin(CrmCustomerStatus::tableName() . " cs", "cs.customer_id=ci.cust_id")
                ->leftJoin(CrmCustPersoninch::tableName() . " cp", "cp.cust_id=ci.cust_id")
                ->leftJoin(HrStaff::tableName() . " hr", "cp.ccpich_personid=hr.staff_id")
                ->leftJoin(BsPubdata::tableName() . " bp_1", "bp_1.bsp_id=ci.cust_type")
                ->leftJoin(BsPubdata::tableName() . " bp_2", "bp_2.bsp_id=ci.cust_level")
                ->leftJoin(BsPubdata::tableName() . " bp_3", "bp_3.bsp_id=ci.cust_compscale")
                ->leftJoin(BsPubdata::tableName() . " bp_4", "bp_4.bsp_id=ci.cust_compvirtue")
                ->leftJoin("pdt.bs_category bc", "bc.catg_no=ci.member_reqitemclass")
                ->leftJoin(BsDistrict::tableName() . ' bd1', 'bd1.district_id=ci.cust_district_2')
                ->leftJoin(BsDistrict::tableName() . ' bd2', 'bd1.district_pid=bd2.district_id')
                ->leftJoin(BsDistrict::tableName() . ' bd3', 'bd2.district_pid=bd3.district_id')
                ->leftJoin(BsDistrict::tableName() . ' bd4', 'bd3.district_pid=bd4.district_id')
                ->leftJoin(CrmSalearea::tableName() . " sa", "sa.csarea_id=ci.cust_salearea")
                ->leftJoin(BsIndustrytype::tableName() . " bi", "bi.idt_id=ci.cust_industrytype")
                ->where([
                    'and',
                    ["!=", "sale_status", CrmCustomerStatus::STATUS_DEL],
                    ['!=', "if(cs.potential_status=10,'潜在客户',if(cs.investment_status!=0 and cs.investment_status is not null,'招商客户',if(cs.sale_status!=0,'销售客户','')))", ''] // 排除数据表中不属于任何一种客户的无效数据
                ])->andWhere(['in', "role_auth(" . $params['user_id'] . ",ci.cust_id)", ['1', '2']]);
        } else {
            $query = (new Query())->select([
                "ci.cust_id",
                "ci.cust_filernumber", // 系统编号
                "ci.cust_code", // 客户代码
                "ci.cust_sname", // 客户名称
                "ci.cust_shortname", // 客户简称
                "bp_1.bsp_svalue cust_type", // 类型
                "bp_2.bsp_svalue cust_level", // 等级
                "ci.cust_tel1", // 公司电话
                "ci.cust_fax", // 传真
                "ci.cust_inchargeperson", // 负责人 法人
                "ci.member_regweb",  // 公司网址
                "ci.cust_contacts",  // 联系人
                "ci.cust_tel1",  // 联系电话
                "ci.cust_email",  // 邮箱
                "hr.staff_name custManager",  // 客户经理人
                "sa.csarea_name cust_salearea",  // 所在军区
                'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,ci.cust_adress) AS customerAddress',// 公司地址
                "bp_3.bsp_svalue cust_compscale", // 公司规模
                "bc.catg_name member_reqitemclass", // 需求类目
                "bi.idt_sname cust_businesstype", // 经营类型
                "bp_4.bsp_svalue cust_compvirtue", // 公司属性
                "ci.cust_personqty", // 员工人数
                "ci.cust_regdate", // 注册时间
                "ci.cust_regfunds", // 注册资金
                "ci.cust_islisted", // 是否上市
                "ci.member_compsum", // 年营业额
                "ci.cust_tax_code", // 税籍编码
                "if(cs.potential_status=10,'潜在客户',if(cs.investment_status!=0 and cs.investment_status is not null,'招商客户',if(cs.sale_status!=0,'销售客户','??'))) cust_t", // 客户属性
                "ci.cust_ismember", // 是否会员
                "ci.member_name", // 会员名
                "ci.cust_email",  // 邮箱
                "ci.cust_tel2", // 注册手机
            ])->from(['ci' => CrmCustomerInfo::tableName()])
                ->leftJoin(CrmCustomerStatus::tableName() . " cs", "cs.customer_id=ci.cust_id")
                ->leftJoin(CrmCustPersoninch::tableName() . " cp", "cp.cust_id=ci.cust_id")
                ->leftJoin(HrStaff::tableName() . " hr", "cp.ccpich_personid=hr.staff_id")
                ->leftJoin(BsPubdata::tableName() . " bp_1", "bp_1.bsp_id=ci.cust_type")
                ->leftJoin(BsPubdata::tableName() . " bp_2", "bp_2.bsp_id=ci.cust_level")
                ->leftJoin(BsPubdata::tableName() . " bp_3", "bp_3.bsp_id=ci.cust_compscale")
                ->leftJoin(BsPubdata::tableName() . " bp_4", "bp_4.bsp_id=ci.cust_compvirtue")
                ->leftJoin("pdt.bs_category bc", "bc.catg_no=ci.member_reqitemclass")
                ->leftJoin(BsDistrict::tableName() . ' bd1', 'bd1.district_id=ci.cust_district_2')
                ->leftJoin(BsDistrict::tableName() . ' bd2', 'bd1.district_pid=bd2.district_id')
                ->leftJoin(BsDistrict::tableName() . ' bd3', 'bd2.district_pid=bd3.district_id')
                ->leftJoin(BsDistrict::tableName() . ' bd4', 'bd3.district_pid=bd4.district_id')
                ->leftJoin(CrmSalearea::tableName() . " sa", "sa.csarea_id=ci.cust_salearea")
                ->leftJoin(BsIndustrytype::tableName() . " bi", "bi.idt_id=ci.cust_industrytype")
                ->where([
                    'and',
                    ["!=", "sale_status", CrmCustomerStatus::STATUS_DEL],
                    ['!=', "if(cs.potential_status=10,'潜在客户',if(cs.investment_status!=0 and cs.investment_status is not null,'招商客户',if(cs.sale_status!=0,'销售客户','')))", ''] // 排除数据表中不属于任何一种客户的无效数据
                ]);
        }


        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params['rows']) ? $params['rows'] : ""
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cust_type' => $this->cust_type,
            'cust_salearea' => $this->cust_salearea,
            'cust_area' => $this->cust_area,
            'personinch_status' => $this->personinch_status,
            'cp.sts_id' => $this->sts_id,
            'hr.staff_id' => $this->custManager
        ]);
//         echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * @param $params
     * 账信申请选择客户
     */
    public function searchCreditCust($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'info.cust_id',
            'info.cust_sname',
            'info.cust_shortname',
            'info.cust_salearea',
            'info.cust_type',
            'info.cust_level',
            'info.cust_tel1',
            'info.cust_compvirtue',
            'info.cust_regfunds',
            'info.member_businessarea',
            'info.cust_parentcomp',
            'info.total_investment',
            'info.official_receipts',
            'info.shareholding_ratio',
            'info.cust_contacts',
            'info.cust_tel2',
            'info.cust_tax_code',
            'info.cust_code apply_code',
            'hs.staff_name',
            'hs.staff_mobile',
            'bc.company_name',
            'bp_1.bsp_svalue regcurr',
        ])->from([CrmCustomerInfo::tableName() . ' info'])
            ->leftJoin(CrmCustomerStatus::tableName() . ' cs', 'cs.customer_id=info.cust_id')
            ->leftJoin(CrmCustomerApply::tableName() . ' ca', 'ca.cust_id=info.cust_id')
            ->leftJoin(CrmCustPersoninch::tableName() . ' inch', 'inch.cust_id=info.cust_id')
            ->leftJoin(HrStaff::tableName() . ' hs', 'hs.staff_id=inch.ccpich_personid')
            ->leftJoin(User::tableName() . ' us', 'us.staff_id=hs.staff_id')
            ->leftJoin(BsCompany::tableName() . ' bc', 'bc.company_id=us.company_id')
            ->leftJoin(BsPubdata::tableName() . ' bp_1', 'bp_1.bsp_id=info.member_regcurr')
            ->where([
                'and',
                ["!=", "cs.sale_status", CrmCustomerStatus::STATUS_DEL],
                ['=', 'ca.status', CrmCustomerApply::STATUS_FINISH]
            ])
            ->andWhere(['in', 'info.company_id', BsCompany::getIdsArr($params['companyId'])]);

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params['rows']) ? $params['rows'] : ""
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'or',
            ['like', 'cust_sname', trim($this->searchKeyword)],
            ['like', 'cust_sname', $trans->c2t(trim($this->searchKeyword))],
            ['like', 'cust_sname', $trans->t2c(trim($this->searchKeyword))],
            ['like', 'ca.applyno', trim($this->searchKeyword)],
            ['like', 'ca.applyno', $trans->c2t(trim($this->searchKeyword))],
            ['like', 'ca.applyno', $trans->t2c(trim($this->searchKeyword))],
        ]);
//        $c = clone $query;
//        echo $c->createCommand()->getRawSql();exit;
        return $dataProvider;
    }
}
