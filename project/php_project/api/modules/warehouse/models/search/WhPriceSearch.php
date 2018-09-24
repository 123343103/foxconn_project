<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/14
 * Time: 下午 02:05
 */

namespace app\modules\warehouse\models\search;


use app\classes\Trans;
use app\modules\common\models\BsCurrency;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\BsWhPrice;
use app\modules\warehouse\models\WhPrice;
use app\modules\warehouse\models\WhPricel;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class WhPriceSearch extends WhPrice
{
    public function search($param)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            "wp.whp_id",//主键id
            "bw.wh_code",//仓库代码
            "bw.wh_name",//仓库名称
            "bp.bsp_svalue",//操作类型
            "(CASE wp.whp_status WHEN 1 THEN '启用' ELSE '禁用' END) as whp_status",//状态
            "CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,bw.wh_addr) AS customerAddress",//仓库地址
            "wp.whp_remark",//备注
            "wp.create_by",//创建人id
            "hs.staff_name",//创建人姓名
            "wp.cdate",//穿件时间
        ])->from(['wp' => WhPrice::tableName()])
            ->leftJoin(BsWh::tableName() . " bw", "wp.wh_id = bw.wh_id")
            ->leftJoin("erp.bs_pubdata bp", "bp.bsp_id = wp.op_id")
            ->leftJoin('erp.bs_district  bd1', 'bd1.district_id=bw.district_id')
            ->leftJoin('erp.bs_district  bd2', 'bd1.district_pid=bd2.district_id')
            ->leftJoin('erp.bs_district  bd3', 'bd2.district_pid=bd3.district_id')
            ->leftJoin('erp.bs_district  bd4', 'bd3.district_pid=bd4.district_id')
            ->leftJoin('erp.hr_staff hs', 'hs.staff_id = wp.create_by');
        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($param['rows']) ? $param['rows'] : ""
            ]
        ]);
        $param['wh_name'] = isset($param['wh_name']) ? $param['wh_name'] : '';
        $param['op_id'] = isset($param['op_id']) ? $param['op_id'] : '';
        $param['staff_name'] = isset($param['staff_name']) ? $param['staff_name'] : '';
        $query->andFilterWhere(['or',
            ['like', 'bw.wh_name', trim($param['wh_name'])],
            ['like', 'bw.wh_name', $trans->t2c(trim($param['wh_name']))],
            ['like', 'bw.wh_name', $trans->c2t(trim($param['wh_name']))],
            ['like', 'bw.wh_code', $trans->c2t(trim($param['wh_name']))]
        ]);
        $query->andFilterWhere(["wp.op_id" => $param['op_id']]);
        $query->andFilterWhere(['or',
            ['like', 'hs.staff_name', trim($param['staff_name'])],
            ['like', 'hs.staff_name', $trans->t2c(trim($param['staff_name']))],
            ['like', 'hs.staff_name', $trans->c2t(trim($param['staff_name']))],
        ]);
        return $dataProvider;
    }

    public function getBsWhList($wh_id)
    {
        $query = (new Query())->select([
            "bw.wh_id",//主键id
            "bw.wh_code",//仓库代码
            "bw.wh_name",//仓库名称
            "CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,bw.wh_addr) AS customerAddress",//仓库地址
        ])->from(['bw' => BsWh::tableName()])
            ->leftJoin('erp.bs_district  bd1', 'bd1.district_id=bw.district_id')
            ->leftJoin('erp.bs_district  bd2', 'bd1.district_pid=bd2.district_id')
            ->leftJoin('erp.bs_district  bd3', 'bd2.district_pid=bd3.district_id')
            ->leftJoin('erp.bs_district  bd4', 'bd3.district_pid=bd4.district_id');
        $dataProvider = new ActiveDataProvider([
            "query" => $query,

        ]);
        $query->andFilterWhere(['bw.wh_id' => $wh_id]);
        return $dataProvider;
    }

    public function getWhPrice($whp_id)
    {
        $query = (new Query())->select([
            "a.*",//
            "b.whpb_code",//费用代码
            "b.whpb_sname",
            "b.stcl_description",
            "c.cur_code",
            "(SELECT COUNT(*) FROM wms.ic_inv_costlist WHERE whpl_id = a.whpl_id ) AS count",//是否被引用
        ])->from(['a' => WhPricel::tableName()])
            ->leftJoin(BsWhPrice::tableName() . '  b', 'a.whpb_id = b.whpb_id')
            ->leftJoin(BsCurrency::tableName() . '  c', 'a.whpb_curr = c.cur_id');
        $dataProvider = new ActiveDataProvider([
            "query" => $query,

        ]);
        $query->andFilterWhere(['a.whp_id' => $whp_id]);
        return $dataProvider;
    }
}