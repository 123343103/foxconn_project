<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:03
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class OtherOutStockController extends BaseController
{
    public $_url = "/warehouse/other-out-stock/";

    //首页
    public function actionIndex()
    {
        $params = \Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "index?staff_id=" . \Yii::$app->user->identity->staff->staff_id . http_build_query($params);
        if (\Yii::$app->request->isAjax) {
            $data = Json::decode($this->findCurl()->get($url));
            foreach ($data["rows"] as $key => $value) {
                $data["rows"][$key]['o_whcode'] = Html::a($data["rows"][$key]['o_whcode'], ['view', 'id' => $data["rows"][$key]['o_whpkid']], ['class' => 'viewitem']);
                if ($data["rows"][$key]['o_whstatus'] == 1) {
                    $data["rows"][$key]['o_whstatus'] = "待收货";
                } else if ($data["rows"][$key]['o_whstatus'] == 2) {
                    $data["rows"][$key]['o_whstatus'] = "已收货";
                } else if ($data["rows"][$key]['o_whstatus'] == 3) {
                    $data["rows"][$key]['o_whstatus'] = "已出库";
                } else if ($data["rows"][$key]['o_whstatus'] == 4) {
                    $data["rows"][$key]['o_whstatus'] = "已取消";
                } else if ($data["rows"][$key]['o_whstatus'] == 5) {
                    $data["rows"][$key]['o_whstatus'] = "待提交";
                } else if ($data["rows"][$key]['o_whstatus'] == 6) {
                    $data["rows"][$key]['o_whstatus'] = "审核中";
                } else if ($data["rows"][$key]['o_whstatus'] == 7) {
                    $data["rows"][$key]['o_whstatus'] = "审核完成";
                } else if ($data["rows"][$key]['o_whstatus'] == 8) {
                    $data["rows"][$key]['o_whstatus'] = "驳回";
                }
                $data["rows"][$key]["o_date"] = date("Y/m/d", strtotime($data["rows"][$key]["o_date"]));
                $data["rows"][$key]["plan_odate"] = date("Y/m/d", strtotime($data["rows"][$key]["plan_odate"]));
                $data["rows"][$key]["opp_date"] = date("Y/m/d", strtotime($data["rows"][$key]["opp_date"]));
                $data["rows"][$key]["creat_date"] = date("Y/m/d", strtotime($data["rows"][$key]["creat_date"]));

            }
            return json_encode($data);
        }
        if (\Yii::$app->request->get('export')) {
            $data = Json::decode($this->findCurl()->get($url));
//            $rows=$data["rows"];
//            foreach($rows as &$row){
//                $row["o_whstatus"]=strip_tags($row["o_whstatus"]);
//            }
            foreach ($data["rows"] as $key => $value) {
//                $data["rows"][$key]['o_whcode']=Html::a($data["rows"][$key]['o_whcode'],['view', 'id' => $data["rows"][$key]['o_whpkid']],['class'=>'viewitem']);
                if ($data["rows"][$key]['o_whstatus'] == 1) {
                    $data["rows"][$key]['o_whstatus'] = "待收货";
                } else if ($data["rows"][$key]['o_whstatus'] == 2) {
                    $data["rows"][$key]['o_whstatus'] = "已收货";
                } else if ($data["rows"][$key]['o_whstatus'] == 3) {
                    $data["rows"][$key]['o_whstatus'] = "已出库";
                } else if ($data["rows"][$key]['o_whstatus'] == 4) {
                    $data["rows"][$key]['o_whstatus'] = "已取消";
                } else if ($data["rows"][$key]['o_whstatus'] == 5) {
                    $data["rows"][$key]['o_whstatus'] = "待提交";
                } else if ($data["rows"][$key]['o_whstatus'] == 6) {
                    $data["rows"][$key]['o_whstatus'] = "审核中";
                } else if ($data["rows"][$key]['o_whstatus'] == 7) {
                    $data["rows"][$key]['o_whstatus'] = "审核完成";
                } else if ($data["rows"][$key]['o_whstatus'] == 8) {
                    $data["rows"][$key]['o_whstatus'] = "驳回";
                }
                $data["rows"][$key]["o_date"] = date("Y/m/d", strtotime($data["rows"][$key]["o_date"]));
                $data["rows"][$key]["plan_odate"] = date("Y/m/d", strtotime($data["rows"][$key]["plan_odate"]));
                $data["rows"][$key]["opp_date"] = date("Y/m/d", strtotime($data["rows"][$key]["opp_date"]));
                $data["rows"][$key]["creat_date"] = date("Y/m/d", strtotime($data["rows"][$key]["creat_date"]));

            }
            return $this->exportFiled($data["rows"]);
        }
        $columns = $this->getField("/warehouse/other-out-stock/index");
        $url = $this->findApiUrl() . $this->_url . "options";
        $url_wh = $this->findApiUrl() . $this->_url . "get-wh-jurisdiction?staff_id=" . \Yii::$app->user->identity->staff->staff_id;
        $options = Json::decode($this->findCurl()->get($url));
        $options['warehouse'] = Json::decode($this->findCurl()->get($url_wh));
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");
        return $this->render("index", ["columns" => $columns, "options" => $options, 'businessType' => $businessType]);
    }

    //加载关联商品信息
    public function actionLoadProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'child-data?id=' . $id;
//        $url.='?'.http_build_query(\Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //根据料号获取库存商品详情
    public function actionGetProduct($part_no, $id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-product?part_no=" . $part_no . "&id=" . $id;
        $data = $this->findCurl()->get($url);
        return $data;
    }

    //新增
    public function actionCreate()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $data = Json::decode($curl->post($url));
            if ($data["status"] == 1) {
                return Json::encode(["msg" => $data["msg"][1], "flag" => 1, 'url' => Url::to(['view', 'id' => $data['msg'][0]])]);
            }
            return Json::encode(["msg" => $data["msg"][1], "flag" => 0]);
        }
        $url = $this->findApiUrl() . $this->_url . "options";
        $url_wh = $this->findApiUrl() . $this->_url . "get-wh-jurisdiction?staff_id=" . \Yii::$app->user->identity->staff->staff_id;
        $options = Json::decode($this->findCurl()->get($url));
        $options['warehouse'] = Json::decode($this->findCurl()->get($url_wh));
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");
        $staff_url = $this->findApiUrl() . "hr/staff/view?id=" . \Yii::$app->user->identity->staff_id;
        $staff = Json::decode($this->findCurl()->get($staff_url));
        $is_supper = $this->findApiUrl() . $this->_url . "get-user?staff_id=" . \Yii::$app->user->identity->staff_id;
        $staff_is_supper = Json::decode($this->findCurl()->get($is_supper));
//        print_r($staff);
        return $this->render("create", ["options" => $options, 'businessType' => $businessType, 'staff' => $staff, "staff_is_supper" => $staff_is_supper]);
    }

    //修改
    public function actionEdit($id)
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "edit?id={$id}";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
            $data = Json::decode($curl->post($url));
            if ($data["status"] == 1) {
                return Json::encode(["msg" => $data["msg"], "flag" => 1, 'url' => Url::to(['view', 'id' => $id])]);
            }
            return Json::encode(["msg" => $data["msg"], "flag" => 0]);
        }
        $url = $this->findApiUrl() . $this->_url . "model?id={$id}";
        $model = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "child-data?id={$id}";
        $childs = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "options?id={$id}";
        $url_wh = $this->findApiUrl() . $this->_url . "get-wh-jurisdiction?staff_id=" . \Yii::$app->user->identity->staff->staff_id;
        $options = Json::decode($this->findCurl()->get($url));
        $options['warehouse'] = Json::decode($this->findCurl()->get($url_wh));
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");
        $staff_url = $this->findApiUrl() . "hr/staff/view?id=" . \Yii::$app->user->identity->staff_id;
        $staff = Json::decode($this->findCurl()->get($staff_url));
//        print_r(\Yii::$app->user->identity->company_id);
//        print_r($staff);
        return $this->render("edit", ["model" => $model, "childs" => $childs, "options" => $options, 'businessType' => $businessType, 'staff' => $staff]);
    }

    //根据id查询
    public function actionGetStaff($id)
    {
        $staff_url = $this->findApiUrl() . "hr/staff/view?id=" . $id;
        $staff = $this->findCurl()->get($staff_url);
        return $staff;
    }

    //根据工号查询信息
    public function actionGetStaffCode($staff_code)
    {
        $url = $this->findApiUrl() . "hr/staff/get-staff-info?code=" . $staff_code;
        $staff = $this->findCurl()->get($url);
        $staffs = Json::decode($staff);
        if (!empty($staffs)) {
            return $staff;
        } else {
            return 1;
        }
    }

    //详情
    public function actionView($id)
    {
        $url = $this->findApiUrl() . $this->_url . "view?id={$id}";
        $model = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "child-data?id={$id}";
        $childs = Json::decode($this->findCurl()->get($url));
        $urls = $this->findApiUrl() . $this->_url . "options?id={$id}";
        $url_wh = $this->findApiUrl() . $this->_url . "get-wh-jurisdiction?staff_id=" . \Yii::$app->user->identity->staff->staff_id;
        $options = Json::decode($this->findCurl()->get($urls));
        $options['warehouse'] = Json::decode($this->findCurl()->get($url_wh));
        $model['all_address'] = $this->actionGetAddress($model['district_id']) . $model['address'];
        $businessType = $this->findCurl()->get($this->findApiUrl() . $this->_url . "business-type");
//        print_r($options);
        return $this->render("view", ["model" => $model, "childs" => $childs, "options" => $options, 'businessType' => $businessType]);
    }

    //审核
    public function actionCheck($id)
    {
        $url = $this->findApiUrl() . $this->_url . "view?id={$id}";
        $model = Json::decode($this->findCurl()->get($url));
        $url = $this->findApiUrl() . $this->_url . "child-data?id={$id}";
        $childs = Json::decode($this->findCurl()->get($url));
        return $this->render("view", ["model" => $model, "childs" => $childs]);
    }

    //取消出库
    public function actionCancel($id)
    {
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "cancel?id={$id}";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data["status"] == 1) {
                return Json::encode(["msg" => $data["msg"], "flag" => 1]);
            }
            return Json::encode(["msg" => $data["msg"], "flag" => 0]);
        }
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render("cancel");
    }

    //库存商品信息
    public function actionSelectProduct($id,$part_no)
    {
        $params = \Yii::$app->request->queryParams;
        $params['part_no']=$part_no;
        $url = $this->findApiUrl() . $this->_url . "product-data?staff_id=".\Yii::$app->user->identity->staff->staff_id."&id=".$id.http_build_query($params);
        if (\Yii::$app->request->isAjax) {
            $data = Json::decode($this->findCurl()->get($url));
//            dumpE($data);
            return json_encode($data);
        }
        $url = $this->findApiUrl() . $this->_url . "options";
        $url_wh = $this->findApiUrl() . $this->_url . "get-wh-jurisdiction?staff_id=" . \Yii::$app->user->identity->staff->staff_id;
        $options = Json::decode($this->findCurl()->get($url));
        $options['warehouse'] = Json::decode($this->findCurl()->get($url_wh));
        $url_st = $this->findApiUrl() . $this->_url . "get-st?wh_id=" . $id;
        $options['bsst'] = Json::decode($this->findCurl()->get($url_st));
        $this->layout = "@app/views/layouts/ajax.php";
        return $this->render("select-product", ["options" => $options, "id" => $id,"part_no"=>$part_no]);
    }

    //根据部门获取人员
    public function actionGetOrgStaff($org_id, $selected = "")
    {
        $orgs = HrStaff::find()
            ->select("staff_name")
            ->leftJoin(HrOrganization::tableName(), HrOrganization::tableName() . ".organization_code=" . HrStaff::tableName() . ".organization_code")
            ->where([
                "organization_id" => $org_id,
                "staff_status" => 10
            ])
            ->indexBy("staff_id")
            ->column();
        return Html::renderSelectOptions($selected, $orgs, $options = ["prompt" => "请选择", "class" => "width-120"]);
    }

    //根据仓库ID获取仓库管理员
    public function actionGetWhCode($wh_id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-wh-code?wh_id=" . $wh_id;
        $data = Json::decode($this->findCurl()->get($url));
//        $data=$this->findCurl()->get($url);
        $name = "";
        foreach ($data as $key => $val) {
            $name .= $data[$key]['staff_name'] . ",";
        }
        return $name;
    }

    //根据最后一阶id获取完整地址
    public function actionGetAddress($district_id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-address?district_id=" . $district_id;
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }

}

?>