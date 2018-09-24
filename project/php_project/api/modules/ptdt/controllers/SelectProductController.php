<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/11
 * Time: 上午 09:17
 */
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use yii\data\SqlDataProvider;

class SelectProductController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\BsProduct';
    //根据料号查询商品
    public function actionProductData($pdt_no='',$wh_id=''){
        $params=\Yii::$app->request->queryParams;
        $where=" where bw.wh_state='Y' ";
//        $params["kwd"]='口';
        $bindParams=[];
        if(!empty($params["kwd"])){
            $bindParams[":pdt_no"]="%{$params["kwd"]}%";
            $bindParams[":pdt_name"]="%{$params["kwd"]}%";
//            $bindParams[":tp_spec"]="%{$params["kwd"]}%";
//            $bindParams[":brand_name"]="%{$params["kwd"]}%";
            $bindParams[":wh_name"]="%{$params["kwd"]}%";
            $where.=" and (bm.part_no like :pdt_no or bm.pdt_name like :pdt_name  or bw.wh_name like :wh_name) ";
        }
        if(!empty($pdt_no))
        {
            $bindParams[":pdt_no"]=$pdt_no;
            $where.=" and erp.bs_product.pdt_no = :pdt_no";
        }
        if(!empty($wh_id))
        {
            $bindParams[":wh_id"]=$wh_id;
            $where.=" and lb.whs_id=:wh_id ";
        }
        $fields="lb.L_invt_num,lb.part_no,lb.L_invt_bach,bm.pdt_name,bm.tp_spec,bm.brand,bm.unit,bw.wh_name,bs.st_code";
        $sql="select {$fields} FROM
		wms.l_bs_invt_list lb
LEFT JOIN pdt.bs_material bm ON bm.part_no = lb.part_no
LEFT JOIN wms.bs_wh bw ON bw.wh_id = lb.whs_id
LEFT JOIN wms.bs_st bs ON bs.st_id = lb.st_id
        {$where}";
        $count=\Yii::$app->db->createCommand("select count(*) FROM 	wms.l_bs_invt_list lb
        LEFT JOIN pdt.bs_material bm ON bm.part_no = lb.part_no
        LEFT JOIN wms.bs_wh bw ON bw.wh_id = lb.whs_id
        LEFT JOIN wms.bs_st bs ON bs.st_id = lb.st_id
        {$where} group by lb.part_no",$bindParams)->query()->count();
        $provider=new SqlDataProvider([
            "sql"=>$sql,
            "totalCount"=>$count,
            "params"=>$bindParams,
            "pagination"=>[
                "page"=>isset($params["page"])?$params["page"]-1:0,
                "pageSize"=>isset($params["rows"])?$params["rows"]:10,
            ]
        ]);
//        return $sql;
        return [
            "rows"=>$provider->models,
            "total"=>$provider->totalCount
        ];
    }
}