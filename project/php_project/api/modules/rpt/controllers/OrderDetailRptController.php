<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmSalearea;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\BsCategory;
use app\modules\rpt\models\search\OrdDelRptSearch;
use app\modules\sale\models\OrdStatus;
use yii;

class OrderDetailRptController extends BaseActiveController
{
    public $modelClass = 'app\modules\rpt\models\OrdDelRpt';

    public function actionIndex()
    {
        $search = new OrdDelRptSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        $model = $dataProvider->getModels();
        $list["rows"] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //下拉列表数据
   /* public function actionDropdownList($staff_id,$org_code="",$is_supper=""){
        if($org_code){
            $org=HrOrganization::findOne(["organization_code"=>$org_code]);
            $orgs=HrOrganization::getOrgChild($org->organization_id);
            return HrStaff::find()->select("staff_id,staff_code,staff_name,organization_code")->where(["in","organization_code",array_column($orgs,"organization_code")])->all();
        }else{
            $staff=HrStaff::findOne($staff_id);
            if($is_supper){
                $org=HrOrganization::findOne(["organization_pid"=>0]);
            }else{
                $org=HrOrganization::findOne(["organization_code"=>$staff->organization_code]);
            }
            $data=HrOrganization::getOrgChild($org->organization_id);
            return $data;
        }
    }*/

    public function actionDownList(){
        $downList['saleArea'] = CrmSalearea::getSalearea();//营销区域
        $downList['ordStatus'] = OrdStatus::find()->select(['os_id', 'os_name'])->where(['yn' => 1])->all(); //订单状态
        $downList['catgName'] = BsCategory::getLevelOne(); //商品类别
        return $downList;

    }
}

