<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/24
 * Time: 下午 04:58
 */
namespace app\widgets\district;
use app\classes\Curl;
use app\modules\common\models\BsDistrict;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

class District extends Widget{
    public $district_id=0;
    public $level=4;
    public $name;
    public $display="form";
    public $required=false;
    public $options=[
        'country'=>["prompt"=>"请选择","class"=>"district-level width-80 easyui-validatebox"],
        'province'=>["prompt"=>"请选择","class"=>"district-level width-80 easyui-validatebox"],
        'city'=>["prompt"=>"请选择","class"=>"district-level width-80 easyui-validatebox"],
        'district'=>["prompt"=>"请选择","class"=>"district-level width-80 easyui-validatebox"]
    ];
    public function init(){
        if($this->required){
            foreach($this->options as &$option){
                $option["data-options"]="required:true";
            }
        }
    }
    public function run(){
        $url=Url::to(['/base/district','district_id'=>$this->district_id],true);
        $curl=new Curl();
        $data=Json::decode($curl->get($url));
        return $this->render("district",[
            "level"=>$this->level,
            "data"=>$data,
            "name"=>$this->name,
            "options"=>$this->options
        ]);
    }
}
?>