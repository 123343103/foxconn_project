<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/22
 * Time: 下午 04:11
 */

namespace app\modules\warehouse\models\search;


use app\classes\Trans;
use app\modules\common\models\BsPubdata;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\IcInvCosth;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class IcInvCosthSearch extends IcInvCosth
{
    public function search($param)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'b.o_whcode invh_code',
            'b.buss_type',
            'b.o_whtype inout_type',
            'e.bsp_svalue inout_type_name',
            'g.company_id',
            'g.company_name  corporate',
            'a.invh_id',
            'a.audit_status',
            "b.o_date invh_date",
            "c.wh_name",
            "c.wh_code",
            "a.invch_id",
            "d.bsp_svalue wh_attr",
        ])->from(['a' => IcInvCosth::tableName()])
            ->leftJoin("wms.o_whpdt b", "a.invh_id = b.o_whpkid")
            ->leftJoin(BsWh::tableName() . " c", "b.o_whid = c.wh_id")
            ->leftJoin(BsPubdata::tableName() . " d", "c.wh_attr = d.bsp_id")
            ->leftJoin(BsPubdata::tableName() . " e", "b.o_whtype = e.bsp_id")
            ->leftJoin("oms.ord_info  f", "f.ord_id = b.ord_id")
            ->leftJoin("erp.bs_company g", "g.company_id = f.corporate");
        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($param['rows']) ? $param['rows'] : ""
            ]
        ]);
        if (isset($param['wh_name'])) {
            $query->andFilterWhere(['like', "b.o_whcode", $param['o_whcode']]);
        }
        if (isset($param['company'])) {
            $query->andFilterWhere(["g.company_id" => $param['company']]);
        }

        if (isset($param['type'])) {
            $query->andFilterWhere(["b.o_whtype" => $param['type']]);
        }
        if (isset($param['wh_name'])) {
            $query->andFilterWhere(['like', "c.wh_name", $param['wh_name']]);
        }
        if (isset($param['wh_attr'])) {
            $query->andFilterWhere(["c.wh_attr" => $param['wh_attr']]);
        }

        if (isset($param['status'])) {
            if ($param['status'] != '请选择') {
                $query->andFilterWhere(['like', "a.audit_status", $param['status']]);
            }
        }
        if (isset($param['begin_time'])) {
            $query->andFilterWhere(['>=', "b.o_date", $param['begin-date']]);
        }
        if (isset($param['end_time'])) {
            $query->andFilterWhere(['<=', "b.o_date", $param['end-date']]);
        }
//        $query->andFilterWhere(['or',
//            ['like', 'hs.staff_name', trim($param['staff_name'])],
//            ['like', 'hs.staff_name', $trans->t2c(trim($param['staff_name']))],
//            ['like', 'hs.staff_name', $trans->c2t(trim($param['staff_name']))],
//        ]);
        return $dataProvider;
    }

}