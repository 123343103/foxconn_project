<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/27
 * Time: 下午 02:05
 */
namespace app\widgets\tableview;
use yii\base\Widget;
use yii\helpers\Json;

class TableView extends Widget{
    public $indexField;
    public $options=[
        "class"=>"table-view"
    ];
    public $fields;
    public $data;
    public $rownumber=true;
    public $rownumberColOptions=[
        "class"=>"width-50 num-row"
    ];
    public $checkable=false;
    public $checkColOptions=[
        "class"=>"width-50"
    ];
    public $editable=false;
    public $actions=[
        [
            "options"=>[
                "class"=>"icon-trash row-del ml-10 mr-10",
                "url"=>["remove-child"],
            ]
        ],
        [
            "options"=>[
                "class"=>"icon-edit row-edit ml-10 ml-10",
                "url"=>["edit-child"]
            ]
        ]
    ];
    public $actionsColOptions=[
        "class"=>"width-100 action-col"
    ];
    public $emptyTip="列表为空";
    public function init(){

    }
    public function run(){
        return $this->render('index',["widget"=>$this]);
    }
}
?>