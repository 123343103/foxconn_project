<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/28
 * Time: 上午 10:24
 */
namespace app\modules\ptdt\models\show;

use app\classes\Transportation;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\RPrtWh;
use app\modules\warehouse\models\BsWh;
use yii\data\ActiveDataProvider;

class BsPartnoSelectorShow extends BsPartno
{
    public function fields()
    {
        $fields["prt_pkid"] = function () {
            return $this->prt_pkid;
        };
        $fields["part_no"] = function () {
            return $this->part_no;
        };
        $fields["tp_spec"] = function () {
            return $this->tp_spec;
        };
        $fields["min_order"] = function () {
            return $this->min_order;
        };
        $fields["pdt_name"] = function () {
            return $this->product["pdt_name"];
        };
        $fields["pack_num"] = function () {
            return $this->bsPack["pdt_qty"];
        };
        $fields["self_take"] = function () {
            return $this->isselftake;
        };
        $fields["transport"] = function () {
            $transportation = new Transportation();
            return $transportation->getAllTansType($this->prt_pkid);
        };
        $fields["category"] = function () {
            $data = [];
            isset($this->product->productType->parent->parent->catg_name) && $data[] = $this->product->productType->parent->parent->catg_name;
            isset($this->product->productType->parent->catg_name) && $data[] = $this->product->productType->parent->catg_name;
            isset($this->product->productType->catg_name) && $data[] = $this->product->productType->catg_name;
            return implode("->", $data);
        };
        $fields["unit"] = function () {
            return isset($this->product->unite->bsp_svalue) ? $this->product->unite->bsp_svalue : "";
        };
        $fields["price"] = function () {
            return array_filter($this->price,function($row){
                return $row['currency']==\Yii::$app->request->get('curr') && $row['item']==0;
            });
        };
        $fields["wh"] = function () {
            $result = BsWh::find()->with(["whAdms.hrStaff"])->where(["yn_deliv" => 1])->asArray()->all();
            $rprtWhArr = RPrtWh::find()->select("wh_id")->where(["prt_pkid" => $this->primaryKey])->asArray()->column();
            $result = array_map(function ($row) use ($rprtWhArr) {
                if (in_array($row["wh_id"], $rprtWhArr)) {
                    return [
                        "wh_id" => $row["wh_id"],
                        "wh_name" => $row["wh_name"]
                    ];
                }
            }, $result);
            $result = array_filter($result);
            sort($result);
            return $result;
        };
        return $fields;
    }
}