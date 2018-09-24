<?php
/**
 * User: F1677929
 * Date: 2017/3/30
 */
namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\show\CrmVisitRecordChildShow;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;

//客户拜访记录子表搜索模型
class CrmVisitRecordChildSearch extends CrmVisitRecordChild
{

    /**
     * @param $params
     * @return ActiveDataProvider
     * 搜索模型
     * F1678086  --START--
     */
    public function search($params)
    {
        $staff = HrStaff::find()->select(['staff_code'])->where(['staff_id' => $params['staff_id']])->one();
        if ($params['id'] == null) {
            $query = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', self::STATUS_DELETE])
                ->andWhere(['sil_staff_code' => $staff['staff_code']])
                ->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);

        } else {
            $sihId = CrmVisitRecord::find()->select('sih_id')->where(['cust_id' => $params['id']])->one();
            $query = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', self::STATUS_DELETE])
                ->andWhere(['and', ['sih_id' => $sihId['sih_id']], ['sil_staff_code' => $staff['staff_code']]])
                ->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        }
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

        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("sil_date desc");
        }
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 查询会员开发任务回访记录
     */
    public function searchPentInfo($params)
    {
        $sihId = CrmVisitRecord::find()->select('sih_id')->where(['cust_id' => $params['id']])->one();
        $query = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', self::STATUS_DELETE])->andWhere(['sih_id' => $sihId['sih_id']])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
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
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 查询会员回访记录
     */
    public function searchMemberInfo($params = null)
    {
        $sihId = CrmVisitRecord::find()->select('sih_id')->where(['cust_id' => $params['id']])->andWhere(['!=', 'sih_status', CrmVisitRecord::STATUS_DELETE])->one();
//        $query = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', self::STATUS_DELETE])->andWhere(['and', ['sih_id' => $sihId['sih_id']], ['=', 'type', CrmVisitRecordChild::TYPE_MEMBER]])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        //记录显示所有
        $query = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', self::STATUS_DELETE])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        //判断 无记录时  undefined index
        if (!empty($sihId['sih_id'])) {
            $query->andWhere(['sih_id' => $sihId['sih_id']]);
        } else {
            $query->andWhere('1=2');
        }
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
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 招商拜访记录
     */
    public function searchInvestmentInfo($params = null)
    {
        $sihId = CrmVisitRecord::find()->select('sih_id')->where(['cust_id' => $params['id']])->andWhere(['!=', 'sih_status', CrmVisitRecord::STATUS_DELETE])->one();
        $query = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', self::STATUS_DELETE])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        if (!empty($sihId['sih_id'])) {
            $query->andWhere(['sih_id' => $sihId['sih_id']]);
        } else {
            $query->andWhere('1=2');
        }
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
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
//return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 查询会员回访记录
     */
    public function searchInfo($params)
    {
        $sihId = CrmVisitRecord::find()->select('sih_id')->where(['cust_id' => $params['id']])->one();
        $query = CrmVisitRecordChildShow::find()->where(['!=', 'sil_status', self::STATUS_DELETE])->andWhere(['=', 'type', self::TYPE_MEMBER])->andWhere(['sih_id' => $sihId['sih_id']])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
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

        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("sil_date desc");
        }
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
    /**
     * @param $params
     * @return ActiveDataProvider
     * 搜索模型
     * F1678086  --END--
     */

    //搜索拜访记录子表-郭文聪
    public function searchVisitRecord($params)
    {
        $query = (new Query())->select([
            CrmVisitRecordChild::tableName() . '.sil_id',//拜访记录子表id
            CrmVisitRecordChild::tableName() . '.sil_code',//拜访记录子表编号
            CrmVisitRecordChild::tableName() . '.type',//拜访记录子表编号
            'hs1.staff_name visitPerson',//拜访人
            'bp1.bsp_svalue visitType',//拜访类型
            CrmVisitRecordChild::tableName() . '.start',//拜访开始时间
            CrmVisitRecordChild::tableName() . '.end',//拜访结束时间
            CrmVisitRecordChild::tableName() . '.sil_time',//拜访用时
            CrmVisitRecordChild::tableName() . '.sil_interview_conclus',//拜访总结
            CrmVisitPlan::tableName() . '.svp_id',//关联拜访计划id
            CrmVisitPlan::tableName() . '.svp_code',//关联拜访计划
            'hs2.staff_name createPerson',//档案建立人
            CrmVisitRecordChild::tableName() . '.create_at',//创建日期
            "(case " . CrmVisitRecordChild::tableName() . ".type when " . CrmVisitRecordChild::TYPE_RECORD . " then '普通拜访' when " . CrmVisitRecordChild::TYPE_LINSHI . " then '临时拜访' when " . CrmVisitRecordChild::TYPE_MEMBER . " then '会员回访' when " . CrmVisitRecordChild::TYPE_POTENTIAL . " then '潜在客户回访' when " . CrmVisitRecordChild::TYPE_INVESTMENT . " then '招商客户拜访' else '删除' end) as recordType"
//            'IFNULL('.CrmVisitRecordChild::tableName().'.update_at,'.CrmVisitRecordChild::tableName().'.create_at) AS sort_at'
        ])->from(CrmVisitRecordChild::tableName())
            //关联拜访人
            ->leftJoin(HrStaff::tableName() . ' hs1', 'hs1.staff_code=' . CrmVisitRecordChild::tableName() . '.sil_staff_code')
            //关联拜访类型
            ->leftJoin(BsPubdata::tableName() . ' bp1', 'bp1.bsp_id=' . CrmVisitRecordChild::tableName() . '.sil_type')
            //关联拜访计划
            ->leftJoin(CrmVisitPlan::tableName(), CrmVisitPlan::tableName() . '.svp_id=' . CrmVisitRecordChild::tableName() . '.svp_plan_id')
            //关联档案建立人
            ->leftJoin(HrStaff::tableName() . ' hs2', 'hs2.staff_id=' . CrmVisitRecordChild::tableName() . '.create_by')
            ->where(['!=', CrmVisitRecordChild::tableName() . '.sil_status', CrmVisitRecordChild::STATUS_DELETE])
            ->andWhere([CrmVisitRecordChild::tableName() . '.sih_id' => $params['mainId']])
            ->orderBy([CrmVisitRecordChild::tableName() . '.sil_id' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['rows'],
            ],
        ]);
//        file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }

    //客户拜访记录明细表-郭文聪
//    public function searchList($params)
//    {
//
//        //搜索用户所拜访的相关信息
//        $query = (new Query())->select([
//            CrmVisitRecordChild::tableName() . '.sil_id',//拜访记录子表id
//            CrmVisitRecordChild::tableName() . '.sil_code',//拜访记录子表编号
//            'hs1.staff_name visitPerson',//拜访人
//            'bp1.bsp_svalue visitType',//拜访类型
//            CrmVisitRecordChild::tableName() . '.start',//拜访开始时间
//            CrmVisitRecordChild::tableName() . '.end',//拜访结束时间
//            CrmVisitRecordChild::tableName() . '.sil_time',//拜访用时
//            CrmVisitRecordChild::tableName() . '.sil_interview_conclus',//拜访总结
//            CrmVisitPlan::tableName() . '.svp_id',//拜访计划id
//            CrmVisitPlan::tableName() . '.svp_code',//关联拜访计划
//            'hs2.staff_name createPerson',//档案建立人
//            CrmVisitRecordChild::tableName() . '.create_at',//创建日期
//            CrmCustomerInfo::tableName() . '.cust_id',//客户id
//            CrmCustomerInfo::tableName() . '.cust_filernumber',//档案编号
//            CrmCustomerInfo::tableName() . '.cust_sname',//客户名称
//            'bp2.bsp_svalue customerType',//客户类型
//            'hs3.staff_name customerManager',//客户经理人
////            'group_concat(hs3.staff_name) customerManager',
//            CrmSalearea::tableName() . '.csarea_name',//营销区域
//            CrmCustomerInfo::tableName() . '.cust_contacts',//联系人
//            CrmCustomerInfo::tableName() . '.cust_tel2',//联系电话
//            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,' . CrmCustomerInfo::tableName() . '.cust_adress) AS customerAddress',
//        ])->from(CrmVisitRecordChild::tableName())
//            //关联拜访人
//            ->leftJoin(HrStaff::tableName() . ' hs1', 'hs1.staff_code=' . CrmVisitRecordChild::tableName() . '.sil_staff_code')
//            //关联拜访类型
//            ->leftJoin(BsPubdata::tableName() . ' bp1', 'bp1.bsp_id=' . CrmVisitRecordChild::tableName() . '.sil_type')
//            //关联拜访计划
//            ->leftJoin(CrmVisitPlan::tableName(), CrmVisitPlan::tableName() . '.svp_id=' . CrmVisitRecordChild::tableName() . '.svp_plan_id')
//            //关联档案建立人
//            ->leftJoin(HrStaff::tableName() . ' hs2', 'hs2.staff_id=' . CrmVisitRecordChild::tableName() . '.create_by')
//            //关联拜访记录主表
//            ->leftJoin(CrmVisitRecord::tableName(), CrmVisitRecord::tableName() . '.sih_id=' . CrmVisitRecordChild::tableName() . '.sih_id')
//            //关联客户信息
//            ->leftJoin(CrmCustomerInfo::tableName(), CrmCustomerInfo::tableName() . '.cust_id=' . CrmVisitRecord::tableName() . '.cust_id')
//            //关联客户类型
//            ->leftJoin(BsPubdata::tableName() . ' bp2', 'bp2.bsp_id=' . CrmCustomerInfo::tableName() . '.cust_type')
//            //关联客户经理人
//            ->leftJoin(CrmCustPersoninch::tableName(), CrmCustPersoninch::tableName() . '.cust_id=' . CrmCustomerInfo::tableName() . '.cust_id')
//            ->leftJoin(HrStaff::tableName() . ' hs3', 'hs3.staff_id=' . CrmCustPersoninch::tableName() . '.ccpich_personid')
//            //关联营销区域
//            ->leftJoin(CrmSalearea::tableName(), CrmSalearea::tableName() . '.csarea_id=' . CrmCustomerInfo::tableName() . '.cust_salearea')
//            //客户地址
//            ->leftJoin(BsDistrict::tableName() . ' bd1', 'bd1.district_id=' . CrmCustomerInfo::tableName() . '.cust_district_2')
//            ->leftJoin(BsDistrict::tableName() . ' bd2', 'bd1.district_pid=bd2.district_id')
//            ->leftJoin(BsDistrict::tableName() . ' bd3', 'bd2.district_pid=bd3.district_id')
//            ->leftJoin(BsDistrict::tableName() . ' bd4', 'bd3.district_pid=bd4.district_id')
//            ->where(['!=', CrmVisitRecordChild::tableName() . '.sil_status', CrmVisitRecordChild::STATUS_DELETE])
//            ->andWhere(['in', CrmVisitRecord::tableName() . '.company_id', BsCompany::getIdsArr($params['companyId'])]);
//        if (!empty($params['staffId'])) {
//            $query->andWhere(['hs3.staff_id' => $params['staffId']]);
//        }
////        $query->groupBy(CrmCustomerInfo::tableName().'.cust_id');
//        $query->orderBy([CrmVisitRecordChild::tableName() . '.sil_id' => SORT_DESC]);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => empty($params['rows']) ? '' : $params['rows'],
//            ],
//        ]);
//        $query->andFilterWhere([
//            'bp2.bsp_id' => isset($params['customerType']) ? $params['customerType'] : '',
//            'bp1.bsp_id' => isset($params['visitType']) ? $params['visitType'] : '',
//        ]);
//        if (isset($params['recordChildCode'])) {
//            $query->andFilterWhere(['like', CrmVisitRecordChild::tableName() . '.sil_code', $params['recordChildCode']]);
//        }
//        //处理简体繁体
//        $trans = new Trans();
//        if (isset($params['customerFullName'])) {
//            $query->andFilterWhere([
//                'or',
//                ['like', CrmCustomerInfo::tableName() . '.cust_sname', $params['customerFullName']],
//                ['like', CrmCustomerInfo::tableName() . '.cust_sname', $trans->t2c($params['customerFullName'])],
//                ['like', CrmCustomerInfo::tableName() . '.cust_sname', $trans->c2t($params['customerFullName'])]
//            ]);
//        }
//        if (isset($params['customerManager'])) {
//            $query->andFilterWhere([
//                'or',
//                ['like', 'hs3.staff_name', $params['customerManager']],
//                ['like', 'hs3.staff_name', $trans->t2c($params['customerManager'])],
//                ['like', 'hs3.staff_name', $trans->c2t($params['customerManager'])]
//            ]);
//        }
//        if (isset($params['contactInfo'])) {
//            $query->andFilterWhere([
//                'or',
//                ['like', CrmCustomerInfo::tableName() . '.cust_contacts', $params['contactInfo']],
//                ['like', CrmCustomerInfo::tableName() . '.cust_contacts', $trans->t2c($params['contactInfo'])],
//                ['like', CrmCustomerInfo::tableName() . '.cust_contacts', $trans->c2t($params['contactInfo'])],
//                ['like', CrmCustomerInfo::tableName() . '.cust_tel2', $params['contactInfo']],
//                ['like', CrmCustomerInfo::tableName() . '.cust_tel2', $trans->t2c($params['contactInfo'])],
//                ['like', CrmCustomerInfo::tableName() . '.cust_tel2', $trans->c2t($params['contactInfo'])]
//            ]);
//        }
//        if (isset($params['visitPerson'])) {
//            $query->andFilterWhere([
//                'or',
//                ['like', 'hs1.staff_name', $params['visitPerson']],
//                ['like', 'hs1.staff_name', $trans->t2c($params['visitPerson'])],
//                ['like', 'hs1.staff_name', $trans->c2t($params['visitPerson'])]
//            ]);
//        }
//        if (!empty($params['startDate'])) {
//            $query->andFilterWhere(['>=', CrmVisitRecordChild::tableName() . '.start', date('Y-m-d H:i:s', strtotime($params['startDate']))]);
//        }
//        if (!empty($params['endDate'])) {
//            $query->andFilterWhere(['<=', CrmVisitRecordChild::tableName() . '.end', date('Y-m-d H:i:s', strtotime($params['endDate'] . '+1 day'))]);
//        }
//        return $dataProvider;
//    }

    //搜索所有拜访记录-郭文聪
    public function searchAllRecord($mainId)
    {
        $allRecord = (new Query())->select([
            CrmVisitRecordChild::tableName() . '.sil_id',//拜访记录子表id
            CrmVisitRecordChild::tableName() . '.type',
            CrmVisitRecordChild::tableName() . '.sih_id',//拜访记录主表id
            CrmVisitRecordChild::tableName() . '.sil_code',//拜访记录子表编号
            'hs1.staff_name visitPersonName',//拜访人姓名
            'hs1.staff_code visitPersonCode',//拜访人工号
            'bp1.bsp_svalue visitType',//拜访类型
            CrmVisitRecordChild::tableName() . '.start',//拜访开始时间
            CrmVisitRecordChild::tableName() . '.end',//拜访结束时间
            CrmVisitRecordChild::tableName() . '.sil_time',//拜访用时
            CrmVisitRecordChild::tableName() . '.sil_interview_conclus',//拜访总结
            CrmVisitRecordChild::tableName() . '.execute_project',//执行项目
            CrmVisitRecordChild::tableName() . '.sil_process_descript',//过程描述
            CrmVisitRecordChild::tableName() . '.sil_track_item',//追踪事项
            CrmVisitRecordChild::tableName() . '.sil_next_interview_notice',//下次访谈注意事项
            CrmVisitRecordChild::tableName() . '.remark',//备注
            CrmVisitPlan::tableName() . '.svp_id',//关联拜访计划id
            CrmVisitPlan::tableName() . '.svp_code',//关联拜访计划
            CrmVisitPlan::tableName() . '.start plan_start_time',
            CrmVisitPlan::tableName() . '.end plan_end_time',
            'hs2.staff_name createPerson',//档案建立人
            CrmVisitRecordChild::tableName() . '.create_at',//创建日期
            'IFNULL(' . CrmVisitRecordChild::tableName() . '.update_at,' . CrmVisitRecordChild::tableName() . '.create_at) AS sort_at'
        ])->from(CrmVisitRecordChild::tableName())
            //关联拜访人
            ->leftJoin(HrStaff::tableName() . ' hs1', 'hs1.staff_code=' . CrmVisitRecordChild::tableName() . '.sil_staff_code')
            //关联拜访类型
            ->leftJoin(BsPubdata::tableName() . ' bp1', 'bp1.bsp_id=' . CrmVisitRecordChild::tableName() . '.sil_type')
            //关联拜访计划
            ->leftJoin(CrmVisitPlan::tableName(), CrmVisitPlan::tableName() . '.svp_id=' . CrmVisitRecordChild::tableName() . '.svp_plan_id')
            //关联档案建立人
            ->leftJoin(HrStaff::tableName() . ' hs2', 'hs2.staff_id=' . CrmVisitRecordChild::tableName() . '.create_by')
            ->where(['and', ['!=', CrmVisitRecordChild::tableName() . '.sil_status', CrmVisitRecordChild::STATUS_DELETE], [CrmVisitRecordChild::tableName() . '.sih_id' => $mainId]])
            ->orderBy(['sort_at' => SORT_DESC])
            ->limit(5)
            ->all();
        return $allRecord;
    }

    /*个人行程查询*/
    public function searchAll($params)
    {
        $trans = new Trans();
        //获取选中的组织的level;
        $queryParams = [

        ];
        $countSql = "select count(*) from  (
SELECT * FROM (
SELECT 
e.sale_status,
	e.staff_code,
	h.staff_name,
	ho.organization_level p_level,
	ho.organization_code p_code,
	ho.organization_name p_oname,
	h2.organization_code a_code,
	h2.organization_name a_name,
	h3.organization_code b_code,
	h3.organization_name b_name,
	e.sale_area,
	csa.csarea_name c_area_name,
	e.sts_id,
	csi.sts_sname c_sts_sname,
    info.*,
    if((ISNULL(info.cust_id)),0,info.cust_id)  cust_id1
FROM
	crm_employee e
LEFT JOIN hr_staff h ON h.staff_code = e.staff_code
LEFT JOIN hr_organization ho ON ho.organization_code = h.organization_code
LEFT JOIN hr_organization h2 ON h2.organization_id = ho.organization_pid
LEFT JOIN hr_organization h3 ON h3.organization_id = h2.organization_pid
LEFT JOIN crm_bs_salearea csa ON csa.csarea_id = e.sale_area
LEFT JOIN crm_bs_storesinfo csi ON csi.sts_id = e.sts_id
left JOIN
	(
		SELECT
			cvi1.accompany,
			cvp1.svp_id,
			cvi1.sil_id,
			cvp1.svp_staff_code staff_code1,
            cvp1.START p_start,
            cvp1.END p_end,
			CONCAT(cvp1. START, \"~\", cvp1. END) AS plan_time,
			bp1.bsp_svalue p_type,
			cvp1.svp_content,
			cvp1.svp_status,
			cc.cust_sname cust_shortname,
	        cvi1.START v_start,
	        cvi1.END v_end,
			CONCAT(cvi1. START, \"~\", cvi1. END) AS record_time,
			bp2.bsp_svalue r_type,
			cvi1.sil_process_descript,
			cvi1.sil_track_item,
			cvi1.sil_next_interview_notice,
			cvi1.sil_interview_conclus,
			cvi1.remark,
			cvp1.cust_id,
            manager(cvp1.cust_id) customerManager
		FROM
			crm_visit_plan cvp1
		LEFT JOIN (
			SELECT
				group_concat(
					CONCAT(
						b.staff_code,
						\" \",
						b.staff_name
					)
					ORDER BY
						CONCAT(
							b.staff_code,
							\" \",
							b.staff_name
						) DESC
				) accompany,
				c.*
			FROM
				crm_visit_info_child c
			LEFT JOIN erp.crm_accompany a ON c.sil_id = a.pid
			LEFT JOIN erp.hr_staff b ON b.staff_id = a.acc_id
			GROUP BY
				sil_id
		) cvi1 ON cvi1.svp_plan_id = cvp1.svp_id
		LEFT JOIN crm_visit_info cr11 ON cr11.sih_id = cvi1.sih_id
		LEFT JOIN crm_bs_customer_info cc ON cc.cust_id = cvp1.cust_id
		LEFT JOIN bs_pubdata bp1 ON bp1.bsp_id = cvp1.svp_type
		LEFT JOIN bs_pubdata bp2 ON bp2.bsp_id = cvi1.sil_type
		left join crm_bs_customer_status cbs on cc.cust_id=cbs.customer_id
		where cbs.sale_status='10'
		UNION
			SELECT
				cvi.accompany,
				cvp.svp_id,
				cvi.sil_id,
				cvi.sil_staff_code staff_code1,
                cvp.START p_start,
                cvp.END p_end,
				CONCAT(cvp. START, \"~\", cvp. END) AS plan_time,
				bp3.bsp_svalue p_type,
				cvp.svp_content,
				cvp.svp_status,
				cc1.cust_sname cust_shortname,
                cvi.START v_start,
                cvi.END v_end,
				CONCAT(cvi. START, \"~\", cvi. END) AS record_time,
				bp4.bsp_svalue r_type,
				cvi.sil_process_descript,
				cvi.sil_track_item,
				cvi.sil_next_interview_notice,
				cvi.sil_interview_conclus,
				cvi.remark,
		    	cr1.cust_id,
                manager(cr1.cust_id) customerManager
			FROM
				crm_visit_plan cvp
			RIGHT JOIN (
				SELECT
					group_concat(
						CONCAT(
							b.staff_code,
							\" \",
							b.staff_name
						)
						ORDER BY
							CONCAT(
								b.staff_code,
								\" \",
								b.staff_name
							) DESC
					) accompany,
					c.*
				FROM
					crm_visit_info_child c
				LEFT JOIN erp.crm_accompany a ON c.sil_id = a.pid
				LEFT JOIN erp.hr_staff b ON b.staff_id = a.acc_id
				GROUP BY
					sil_id
			) cvi ON cvi.svp_plan_id = cvp.svp_id
			LEFT JOIN crm_visit_info cr1 ON cr1.sih_id = cvi.sih_id
			LEFT JOIN crm_bs_customer_info cc1 ON cc1.cust_id = cr1.cust_id
			LEFT JOIN bs_pubdata bp3 ON bp3.bsp_id = cvp.svp_type
			LEFT JOIN bs_pubdata bp4 ON bp4.bsp_id = cvi.sil_type
			left join crm_bs_customer_status cbs1 on cc1.cust_id=cbs1.customer_id
		    where cbs1.sale_status='10'
	) info ON e.staff_code=info.staff_code1)info1
where sale_status=20";
        $querySql = "select * from (SELECT
	*
FROM
	(
		SELECT
			e.sale_status,
			e.staff_code,
			h.staff_name,
			ho.organization_level p_level,
			ho.organization_code p_code,
			ho.organization_name p_oname,
			h2.organization_code a_code,
			h2.organization_name a_name,
			h3.organization_code b_code,
			h3.organization_name b_name,
			e.sale_area,
			csa.csarea_name c_area_name,
			e.sts_id,
			csi.sts_sname c_sts_sname,
			info.*,
			if((ISNULL(info.cust_id)),0,info.cust_id)  cust_id1
		FROM
			crm_employee e
		LEFT JOIN hr_staff h ON h.staff_code = e.staff_code
		LEFT JOIN hr_organization ho ON ho.organization_code = h.organization_code
		LEFT JOIN hr_organization h2 ON h2.organization_id = ho.organization_pid
		LEFT JOIN hr_organization h3 ON h3.organization_id = h2.organization_pid
		LEFT JOIN crm_bs_salearea csa ON csa.csarea_id = e.sale_area
		LEFT JOIN crm_bs_storesinfo csi ON csi.sts_id = e.sts_id
		LEFT JOIN (
			SELECT
				cvi1.accompany,
				cvp1.svp_id,
				cvi1.sil_id,
				cvp1.svp_staff_code staff_code1,
				cvp1. START p_start,
				cvp1.
			END p_end,
			CONCAT(cvp1. START, \"~\", cvp1. END) AS plan_time,
			bp1.bsp_svalue p_type,
			cvp1.svp_content,
			cvp1.svp_status,
			cc.cust_sname cust_shortname,
			cvi1. START v_start,
			cvi1.
		END v_end,
		CONCAT(cvi1. START, \"~\", cvi1. END) AS record_time,
		bp2.bsp_svalue r_type,
		cvi1.sil_process_descript,
		cvi1.sil_track_item,
		cvi1.sil_next_interview_notice,
		cvi1.sil_interview_conclus,
		cvi1.remark,
		cvp1.cust_id,
        manager(cvp1.cust_id) customerManager
	FROM
		crm_visit_plan cvp1
	LEFT JOIN (
		SELECT
			group_concat(
				CONCAT(
					b.staff_code,
					\" \",
					b.staff_name
				)
				ORDER BY
					CONCAT(
						b.staff_code,
						\" \",
						b.staff_name
					) DESC
			) accompany,
			c.*
		FROM
			crm_visit_info_child c
		LEFT JOIN erp.crm_accompany a ON c.sil_id = a.pid
		LEFT JOIN erp.hr_staff b ON b.staff_id = a.acc_id
		GROUP BY
			sil_id
	) cvi1 ON cvi1.svp_plan_id = cvp1.svp_id
	LEFT JOIN crm_visit_info cr11 ON cr11.sih_id = cvi1.sih_id
	LEFT JOIN crm_bs_customer_info cc ON cc.cust_id = cvp1.cust_id
	LEFT JOIN bs_pubdata bp1 ON bp1.bsp_id = cvp1.svp_type
	LEFT JOIN bs_pubdata bp2 ON bp2.bsp_id = cvi1.sil_type
	LEFT JOIN crm_bs_customer_status cbs ON cc.cust_id = cbs.customer_id
	WHERE
		cbs.sale_status = '10'
	UNION
		SELECT
			cvi.accompany,
			cvp.svp_id,
			cvi.sil_id,
			cvi.sil_staff_code staff_code1,
			cvp. START p_start,
			cvp.END p_end,
		CONCAT(cvp. START, \"~\", cvp. END) AS plan_time,
		bp3.bsp_svalue p_type,
		cvp.svp_content,
		cvp.svp_status,
		cc1.cust_sname cust_shortname,
		cvi. START v_start,
		cvi.
	END v_end,
	CONCAT(cvi. START, \"~\", cvi. END) AS record_time,
	bp4.bsp_svalue r_type,
	cvi.sil_process_descript,
	cvi.sil_track_item,
	cvi.sil_next_interview_notice,
	cvi.sil_interview_conclus,
	cvi.remark,
    cr1.cust_id,
    manager(cr1.cust_id) customerManager
FROM
	crm_visit_plan cvp
RIGHT JOIN (
	SELECT
		group_concat(
			CONCAT(
				b.staff_code,
				\" \",
				b.staff_name
			)
			ORDER BY
				CONCAT(
					b.staff_code,
					\" \",
					b.staff_name
				) DESC
		) accompany,
		c.*
	FROM
		crm_visit_info_child c
	LEFT JOIN erp.crm_accompany a ON c.sil_id = a.pid
	LEFT JOIN erp.hr_staff b ON b.staff_id = a.acc_id
	GROUP BY
		sil_id
) cvi ON cvi.svp_plan_id = cvp.svp_id
LEFT JOIN crm_visit_info cr1 ON cr1.sih_id = cvi.sih_id
LEFT JOIN crm_bs_customer_info cc1 ON cc1.cust_id = cr1.cust_id
LEFT JOIN bs_pubdata bp3 ON bp3.bsp_id = cvp.svp_type
LEFT JOIN bs_pubdata bp4 ON bp4.bsp_id = cvi.sil_type
LEFT JOIN crm_bs_customer_status cbs1 ON cc1.cust_id = cbs1.customer_id
WHERE
	cbs1.sale_status = '10'
		) info ON e.staff_code = info.staff_code1
	) info1
WHERE
	sale_status = 20";
        if ($params['isSuper'] == 0) {
            $countSql .= "  AND role_auth(" . $params['user_id'] . ",cust_id1) in(1,2)";
            $querySql .= "  AND role_auth(" . $params['user_id'] . ",cust_id1) in(1,2)";
        }
        $countSql .= ") info2 where 1=1 ";
        $querySql .= ") info2 where 1=1 ";
        if (!empty($params['CrmVisitRecordChildSearch']['organization'])) {
            //查询出选中组织的level
            $queryParams[':organization_code'] = $params['CrmVisitRecordChildSearch']['organization'];
            $level = HrOrganization::find()->where(['=', 'organization_code', $params['CrmVisitRecordChildSearch']['organization']])->one();
            if ($params['CrmVisitRecordChildSearch']['organization'] != 'FOXCONN') {
                if ($level['organization_level'] == '1') {
                    $countSql .= " and (info2.p_code=:organization_code  )";
                    $querySql .= "  and (info2.p_code=:organization_code  )";
                }
                if ($level['organization_level'] == '2') {
                    $countSql .= "  and (info2.a_code=:organization_code  )";
                    $querySql .= "  and (info2.a_code=:organization_code  )";
                }
                if ($level['organization_level'] == '3') {
                    $countSql .= "  and (info2.b_code=:organization_code  )";
                    $querySql .= "  and (info2.b_code=:organization_code  )";
                }
            }
        }
        if (!empty($params['CrmVisitRecordChildSearch']['sale_area'])) {
            $queryParams[':sale_area'] = $params['CrmVisitRecordChildSearch']['sale_area'];
            $countSql .= " and (info2.sale_area=:sale_area )";
            $querySql .= " and (info2.sale_area=:sale_area )";
        }
        if (!empty($params['CrmVisitRecordChildSearch']['sts_id'])) {
            $queryParams[':sts_id'] = $params['CrmVisitRecordChildSearch']['sts_id'];
            $countSql .= " and (info2.sts_id=:sts_id  )";
            $querySql .= " and (info2.sts_id=:sts_id  )";
        }
        if (!empty($params['CrmVisitRecordChildSearch']['cust_sname'])) {
            $queryParams[':cust_sname_1'] = '%' . $params['CrmVisitRecordChildSearch']['cust_sname'] . '%';
            $queryParams[':cust_sname_2'] = '%' . $trans->c2t($params['CrmVisitRecordChildSearch']['cust_sname']) . '%';
            $queryParams[':cust_sname_3'] = '%' . $trans->t2c($params['CrmVisitRecordChildSearch']['cust_sname']) . '%';
            $countSql .= " and (info2.cust_shortname like :cust_sname_1 or info2.cust_shortname like :cust_sname_2 or info2.cust_shortname like :cust_sname_3 )";
            $querySql .= " and (info2.cust_shortname like :cust_sname_1 or info2.cust_shortname like :cust_sname_2 or info2.cust_shortname like :cust_sname_3 )";
        }
        if (!empty($params['CrmVisitRecordChildSearch']['staff_name'])) {
            $queryParams[':staff_name_1'] = '%' . $params['CrmVisitRecordChildSearch']['staff_name'] . '%';
            $queryParams[':staff_name_2'] = '%' . $trans->c2t($params['CrmVisitRecordChildSearch']['staff_name']) . '%';
            $queryParams[':staff_name_3'] = '%' . $trans->t2c($params['CrmVisitRecordChildSearch']['staff_name']) . '%';
            $countSql .= " and (customerManager like :staff_name_1 or customerManager like :staff_name_2 or  customerManager like :staff_name_3)";
            $querySql .= " and (customerManager like :staff_name_1 or customerManager like :staff_name_2 or  customerManager like :staff_name_3)";
        }
        if (!empty($params['CrmVisitRecordChildSearch']['start'])) {
            $queryParams[':start'] = date('Y-m-d H:i:s', strtotime($params['CrmVisitRecordChildSearch']['start']));
            $countSql .= " and (info2.p_start >=:start or info2.v_start>=:start )";
            $querySql .= " and (info2.p_start >=:start or info2.v_start>=:start )";
        }
        if (!empty($params['CrmVisitRecordChildSearch']['end'])) {
            $queryParams[':end'] = date('Y-m-d H:i:s', strtotime($params['CrmVisitRecordChildSearch']['end']));
            $countSql .= " and (info2.p_start <=:end or info2.v_start<=:end )";
            $querySql .= " and (info2.p_start <=:end or info2.v_start<=:end )";
        }

        $totalCount = \Yii::$app->db->createCommand($countSql, $queryParams)->queryScalar();
        $querySql .= " order by info2.p_start desc";
        $provider = new SqlDataProvider([
            'sql' => $querySql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($params['rows']) ? false : $params['rows']
            ]
        ]);
        return $provider;

    }
}