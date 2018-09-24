<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/2
 * Time: 上午 11:35
 */

namespace app\modules\sync\controllers;
use app\controllers\BaseController;
use app\modules\sync\models\member\BsCurrency;
use app\modules\sync\models\member\CrmCustomerInfo;
use app\modules\sync\models\member\TmpCustomerInfo;
use app\modules\sync\models\ptdt\BsBrand;
use app\modules\sync\models\ptdt\BsCategory;
use app\modules\sync\models\ptdt\BsCategoryUnit;
use app\modules\sync\models\ptdt\BsCompany;
use app\modules\sync\models\ptdt\BsProduct;
use app\modules\sync\models\ptdt\CategoryAttr;
use app\modules\sync\models\ptdt\FpPartno;
use app\modules\sync\models\ptdt\FpPrice;
use app\modules\sync\models\ptdt\PartnoPrice;
use app\modules\sync\models\ptdt\TmpCategory;
use app\modules\sync\models\ptdt\TmpPartno;
use app\modules\sync\models\ptdt\TmpPartnoPrice;
use yii\data\Pagination;

class SyncController extends BaseController
{
    public $steps=[
        "0"=>"富贸数据->临时表",
        "1"=>"临时表->正式表"
    ];
    public $tables=[
//        "0"=>'客户信息',
        "1"=>'料号信息',
        "2"=>'定价信息',
        "3"=>'分类信息'
    ];
    public $baseUrl="http://127.0.0.1/php_project";
    public function actionIndex(){
        return $this->render("index",[
            "steps"=>$this->steps,
            "tables"=>$this->tables
        ]);
    }

    public function actionSync($step,$table,$size=50){
        @ini_set('memory_limit', '-1');
        @ini_set('max_execution_time','0');
        switch($step){
            case 0:
                $this->syncToTmp($table,$size);
                echo "<script>parent.$('#start').prop('disabled',false);</script>";
                break;
            case 1:
                $this->syncToErp($table,$size);
                echo "<script>parent.$('#start').prop('disabled',false);</script>";
                break;


        }
    }

    private function syncToTmp($table,$size){
        switch($table){
            case 0:
                $insert_counter=0;
                $update_counter=0;
                $url = $this->baseUrl.'/mall_api/web/index.php?r=member/customer-info/counter';
                $count = $this->findCurl()->get($url);
                for($x=0;$x<ceil($count/$size);$x++){
                    $url = $this->baseUrl.'/mall_api/web/index.php?r=member/customer-info/index&page='.$x."&size=".$size;
                    $dataProvider=$this->findCurl()->get($url);
                    $obj = json_decode($dataProvider, true);
                    $customers = $obj['rows'];
                    foreach ($customers as $customer) {
                        $model=TmpCustomerInfo::findOne(["CMP_CODE"=>$customer["CMP_CODE"]]);
                        if(!$model){
                            $model=new TmpCustomerInfo();
                        }
                        $model->setAttributes($customer);
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="客户资料同步开始<br />"</script>';
                    echo "客户资料同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    ob_flush();
                    flush();
                }
                echo "客户资料同步完成<br />";
                ob_flush();
                flush();
                break;
            case 1:
                $insert_counter=0;
                $update_counter=0;
                $url = $this->baseUrl.'/fm_api/web/index.php?r=ptdt/part-no/counter';
                $count = $this->findCurl()->get($url);
                for($x=0;$x<ceil($count/$size);$x++){
                    $url = $this->baseUrl.'/fm_api/web/index.php?r=ptdt/part-no/index&page='.$x."&size=".$size;
                    $dataProvider=$this->findCurl()->get($url);
                    $obj = json_decode($dataProvider, true);
                    $products = $obj['rows'];
                    foreach ($products as $product) {
                        $model=TmpPartno::findOne(["PART_NO"=>$product['PART_NO']]);
                        if(!$model){
                            $model = new TmpPartno();
                        }
                        $model->setAttributes($product);
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="料号同步开始<br />"</script>';
                    echo "料号同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    ob_flush();
                    flush();
                }
                echo "料号同步完成<br />";
                break;
            case 2:
                $url = $this->baseUrl.'/fm_api/web/index.php?r=ptdt/price/counter';
                $count= $this->findCurl()->get($url);
                TmpPartnoPrice::deleteAll();
                $insert_counter=0;
                $update_counter=0;
                for($x=0;$x<ceil($count/$size);$x++){
                    $url = $this->baseUrl.'/fm_api/web/index.php?r=ptdt/price/index&page='.$x."&size=".$size;
                    $dataProvider= $this->findCurl()->get($url);
                    $obj = json_decode($dataProvider, true);
                    $prices = $obj['rows'];
                    foreach ($prices as $price) {
                        $model = new TmpPartnoPrice();
                        $model->setAttributes($price);
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="定价同步开始<br />"</script>';
                    echo "定价同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    ob_flush();
                    flush();
                }
                echo "定价同步完成<br />";
                break;
            case 3:
                $insert_counter=0;
                $update_counter=0;
                $url = $this->baseUrl.'/fm_api/web/index.php?r=ptdt/category/counter';
                $count = $this->findCurl()->get($url);
                for($x=0;$x<ceil($count/$size);$x++){
                    $url = $this->baseUrl.'/fm_api/web/index.php?r=ptdt/category/index&page='.$x."&size=".$size;
                    $dataProvider=$this->findCurl()->get($url);
                    $obj = json_decode($dataProvider, true);
                    $categorys = $obj['rows'];
                    foreach ($categorys as $category) {
                        $model=TmpCategory::findOne(["CATEGORY_ID"=>$category['CATEGORY_ID']]);
                        if(!$model){
                            $model = new TmpCategory();
                        }
                        $model->setAttributes($category);
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="分类同步开始<br />"</script>';
                    echo "分类同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    ob_flush();
                    flush();
                }
                echo "分类同步完成<br />";
                break;
            default:
                break;
        }
    }

    private function syncToErp($table,$size){
        switch($table){
            case 0:
                $insert_counter=0;
                $update_counter=0;
                $count = TmpCustomerInfo::find()->count();
                for($x=0;$x<ceil($count/$size);$x++){
                    $pagination=new Pagination([
                        "pageSize"=>$size,
                        "page"=>$x
                    ]);
                    $customers=TmpCustomerInfo::find()
                        ->offset($pagination->offset)
                        ->limit($pagination->limit)
                        ->asArray()
                        ->all();
                    foreach ($customers as $customer) {
                        $ss = array_change_key_case($customer);
                        $model=CrmCustomerInfo::find()->Where(["cmp_code"=>$ss['cmp_code']])
                            ->orFilterWhere(['or',['cust_tel1'=>$ss["telephone"]],['cust_tel2'=>$ss["mobile_telephone"]]])
                            ->orFilterWhere(['cust_sname'=>$ss["cmp_name"]])->one();
                        if(!$model){
                            $model = new CrmCustomerInfo();
                        }
                        $curr = BsCurrency::getIdByCode($ss["reg_currency"]);
                        $curr = (!empty($curr)) ? current($curr)['cur_id'] : null;
                        $model->company_id=3213; // 作为测试company_id 暂时写死为3213
                        $model->data_from='2';
                        $model->cmp_code=$ss["cmp_code"];
                        $model->create_at=$ss["creat_date"];
                        $model->update_at=date('Y-m-d',time());
                        $model->com_type=$ss["com_type"];
                        $model->cust_sname=$ss["cmp_name"];
                        $model->cust_shortname=$ss["cmp_stortname"];
                        $model->cust_tel1=$ss["telephone"];
                        $model->cust_contacts=$ss["account_code"]; // 联系人账号
                        $model->cust_tel2=$ss["mobile_telephone"];
                        $model->cust_email=$ss["email"];
        //                $model->risk_level=$ss["reg_province"];
        //                $model->risk_level=$ss["reg_city"];
        //                $model->risk_level=$ss["reg_district"];
                        $model->cust_adress=$ss["reg_addr"]; // 营业地址
                        $model->cust_regfunds=$ss["organization_code"]; //注册资金
                        $model->member_regcurr=$curr; // 存储币种id
                        $model->invo_type=$ss["invo_type"];
                        $model->cust_fax=$ss["com_tax"]; // 传真
                        $model->cust_regnumber=$ss["license_code"];
                        $model->cust_tax_code=$ss["tax_code"];
                        $model->three_to_one=$ss["three_to_one"];
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="客户资料同步开始<br />"</script>';
                    echo "客户资料同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    ob_flush();
                    flush();
                }
                echo "客户资料同步结束<br />";
                break;
            case 1:
                $insert_counter=0;
                $update_counter=0;
                $count =TmpPartno::find()->count();
                for($x=0;$x<ceil($count/$size);$x++){
                    $pagination=new Pagination([
                        "pageSize"=>$size,
                        "page"=>$x
                    ]);
                    $products=TmpPartno::find()
                        ->offset($pagination->offset)
                        ->limit($pagination->limit)
                        ->asArray()
                        ->all();
                    foreach ($products as $product) {
                        $model=FpPartno::findOne(["PART_NO"=>$product['PART_NO']]);
                        if(!$model){
                            $model = new FpPartno();
                        }
                        $model->setAttributes($product);
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="料号同步开始<br />"</script>';
                    echo "料号同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    echo "内存占用:".memory_get_usage();
                    ob_flush();
                    flush();
                }
                echo "料号同步结束<br />";
                break;
            case 2:
                $count=TmpPartnoPrice::find()->count();
                FpPrice::deleteAll();
                $insert_counter=0;
                $update_counter=0;
                for($x=0;$x<ceil($count/$size);$x++){
                    $pagination=new Pagination([
                        "pageSize"=>$size,
                        "page"=>$x
                    ]);
                    $prices=TmpPartnoPrice::find()
                        ->offset($pagination->offset)
                        ->limit($pagination->limit)
                        ->asArray()
                        ->all();
                    foreach ($prices as $price) {
//                        $sss = array_change_key_case($price);
                        $model = new FpPrice();
                        $model->setAttributes($price);
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="定价同步开始<br />"</script>';
                    echo "定价同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    ob_flush();
                    flush();
                }
                echo "定价同步结束<br />";
                break;
            case 3:
                $insert_counter=0;
                $update_counter=0;
                $count =TmpCategory::find()->count();
                for($x=0;$x<ceil($count/$size);$x++){
                    $pagination=new Pagination([
                        "pageSize"=>$size,
                        "page"=>$x
                    ]);
                    $categorys=TmpCategory::find()
                        ->offset($pagination->offset)
                        ->limit($pagination->limit)
                        ->asArray()
                        ->all();
                    foreach ($categorys as $category) {
                        $ss = array_change_key_case($category);
                        $model=BsCategory::findOne(["category_id"=>$ss['category_id']]);
                        if(!$model){
                            $model = new BsCategory();
                        }
                        $model->category_id=$ss["category_id"];
                        $model->category_sname=$ss["category_name"];
                        $model->p_category_id=$ss["p_category_id"];
                        $model->category_level=$ss["category_level"];
                        $model->orderby=$ss["orderby"];
                        $model->isvalid=$ss["isvalid"];
                        $model->isNewRecord?$insert_counter++:$update_counter++;
                        $model->save();
                    }
                    echo '<script>document.body.innerHTML="分类同步开始<br />"</script>';
                    echo "分类同步(".round(($insert_counter+$update_counter)/$count*100)."%):".($insert_counter+$update_counter)."/".$count."<br />";
                    ob_flush();
                    flush();
                }
                echo "分类同步结束<br />";
                break;
            default:
                break;
        }
    }
}