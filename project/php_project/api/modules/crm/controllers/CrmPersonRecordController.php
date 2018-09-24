<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/6/30
 * Time: 14:08
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmVisitRecordChildSearch;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use Yii;

class CrmPersonRecordController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmVisitRecordChild';

    public function actionIndex()
    {
        $searchModel = new CrmVisitRecordChildSearch();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
//        dumpE($dataProvider);
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    public function actionDownList($id)
    {
        $downList['bsp_svalue'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['=', 'bsp_stype', 'bflx'])->andWhere(['=', 'bsp_status', 10])->asArray()->all();//拜访类型
        $downList['salearea'] = CrmSalearea::getSalearea();//所在军区
        $downList['organization'] = $this->Get($id);//组织
        $downList['store'] = CrmStoresinfo::find()->select(['sts_id', 'sts_sname'])->where(['!=', 'sts_status', CrmStoresinfo::STATUS_DELETE])->asArray()->all();//销售点
        $downList['type'] = [
            CrmVisitRecordChild::TYPE_RECORD => '计划拜访记录',
            CrmVisitRecordChild::TYPE_LINSHI => '临时拜访记录',
        ];
        return $downList;
    }

    public function actionGetAccompany($id)
    {
        return Yii::$app->db->createCommand("select b.staff_code,b.staff_name,c.title_name,a.acc_mobile from erp.crm_accompany a left join erp.hr_staff b on b.staff_id = a.acc_id left join erp.hr_staff_title c on c.title_id = b.position where a.type = 2 and a.pid = {$id}")->queryAll();
    }

    //根据工号查询
    public function Get($id)
    {
        $org_code = HrStaff::getOrgCode($id);
        $hrorg = HrOrganization::byCodeOrg($org_code);
        if ($org_code["organization_code"] != 'FOXCONN') {
            if ($hrorg['organization_level'] == '1') {
                $pOrg = HrOrganization::findOne($hrorg['organization_pid']);
                $ppOrg=HrOrganization::findOne($pOrg['organization_pid'])->toArray();
                $organization = HrOrganization::getOrgAllLevel($pOrg['organization_pid']);
                Array_unshift($organization,$ppOrg);
                return $organization;
            } else{
                $ppOrg=HrOrganization::findOne($hrorg['organization_pid'])->toArray();
                $organization = HrOrganization::getOrgAllLevel($hrorg['organization_pid']);
                Array_unshift($organization,$ppOrg);
//                dumpE($ppOrg);
                return $organization;
            }
//            $organization = HrOrganization::getOrgAllLevel($hrorg['organization_pid']);
//            foreach ($organization as $key => $val) {
//                    if ($val['organization_level']>3){
//                        $organization.remove($key);
//                    }
//            }
//            return $organization;
        } else {
            $organization = HrOrganization::getOrgAllLevel(0);
            return $organization;
        }
//        var_dump($org_code);
//          return $hrorg['organization_level'];
    }
}