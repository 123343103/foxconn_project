<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/26
 * Time: 下午 02:33
 */
namespace app\widgets\district;
use yii\base\Action;
use app\modules\common\models\BsDistrict;
use yii\helpers\Html;
use yii\helpers\Json;

class DistrictAction extends Action{
    public $district_id;
    public $type;
    public function init(){
        $this->district_id=\Yii::$app->request->get("district_id");
        $this->type=\Yii::$app->request->get("type");
    }
    public function run(){
        if($this->type==0){
            $tree=[];
            $path=[];
            if($this->district_id==0){
                $items=BsDistrict::find()->select("district_name")->where(["district_pid"=>0])->indexBy("district_id")->asArray()->column();
                $tree[]=$items;
                $result=[
                    "tree"=>array_reverse($tree),
                    "path"=>array_reverse($path)
                ];
                return Json::encode($result);
            }
            while($this->district_id>0){
                $addr_info=BsDistrict::findOne($this->district_id);
                $path[]=$addr_info->district_id;
                $this->district_id=$addr_info->district_pid;
                $items=BsDistrict::find()->select("district_name")->where(["district_pid"=>$addr_info->district_pid])->indexBy("district_id")->asArray()->column();
                $tree[]=$items;
            }
            $result=[
                "tree"=>array_reverse($tree),
                "path"=>array_reverse($path)
            ];
            return Json::encode($result);
        }else if($this->type==1){
            $items=BsDistrict::find()->select("district_name")->where(["district_pid"=>$this->district_id])->asArray()->indexBy("district_id")->column();
            $options=["prompt"=>"请选择"];
            return Html::renderSelectOptions("",$items,$options);
        }else{
            if($keywords=\Yii::$app->request->get('keywords')){
                $items=BsDistrict::find()->select("district_name")->where(["or",["like","district_name",$keywords],["like","district_code",$keywords]])->andWhere(["district_level"=>1])->asArray()->indexBy("district_id")->column();
            }else{
                $items=BsDistrict::find()->select("district_name")->where(["district_pid"=>$this->district_id])->asArray()->indexBy("district_id")->column();
            }
            return Json::encode($items);
        }
    }
}
?>