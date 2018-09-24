<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/19
 * Time: 上午 11:36
 */

namespace app\modules\warehouse\models\search;


use app\classes\Trans;
use app\modules\warehouse\models\BsInvWarn;
use app\modules\warehouse\models\BsInvWarnH;
use app\modules\warehouse\models\show\BsInvWarnShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;


class BsInvWarnSearch extends BsInvWarnShow
{


    /**
     * @inheritdoc
     */
    public $wh_code;
    public $pdt_model;
    public $part_no;
    public $pdt_name;
    public $down_nums;
    public $up_nums;

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['wh_code', 'pdt_model', 'part_no', 'pdt_name', 'down_nums', 'up_nums'], 'safe']
        ];
    }


    public function search($params)
    {
        $query = BsInvWarnShow::find()->where(['YN'=>1]);
//        return $query;
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [         //查询按照操作时间倒序
                'defaultOrder' => ['OPP_DATE' => SORT_DESC],
            ],
        ]);
        if ((!empty($params['wh_id'])) && $params['so_type'] == '1') {
            $query->andFilterWhere(['like', 'bs_inv_warn_h.inv_id', empty($params['inv_id']) ? '' : $params['inv_id']])
                ->andFilterWhere(['=', 'bs_inv_warn_h.wh_id', empty($params['wh_id']) ? '' : $params['wh_id']])
                ->andFilterWhere(['is', 'bs_inv_warn_h.so_type', NULL])
                ->andFilterWhere(['>=', "DATE_FORMAT(bs_inv_warn_h.OPP_DATE,'%Y-%m-%d')", empty($params['startDate']) ? '' : $params['startDate']])
                ->andFilterWhere(['<=',"DATE_FORMAT(bs_inv_warn_h.OPP_DATE,'%Y-%m-%d')",empty($params['endDate']) ? '' : $params['endDate']]);
        } else {
            $query->andFilterWhere(['like', 'bs_inv_warn_h.inv_id', empty($params['inv_id']) ? '' : $params['inv_id']])
                ->andFilterWhere(['=', 'bs_inv_warn_h.wh_id', empty($params['wh_id']) ? '' : $params['wh_id']])
                ->andFilterWhere(['=', 'bs_inv_warn_h.so_type', empty($params['so_type']) ? '' : $params['so_type']])
                ->andFilterWhere(['>=', "DATE_FORMAT(bs_inv_warn_h.OPP_DATE,'%Y-%m-%d')", empty($params['startDate']) ? '' : $params['startDate']])
                ->andFilterWhere(['<=',"DATE_FORMAT(bs_inv_warn_h.OPP_DATE,'%Y-%m-%d')",empty($params['endDate']) ? '' : $params['endDate']]);
        }
//        return $query;
        return $dataProvider;
    }


    public function searchProduct($params)
    {
        $countsql = "SELECT
	count(*)
from
	wms.bs_invt a
left join pdt.bs_partno b on a.part_no = b.part_no
left join pdt.bs_product f on b.pdt_pkid=f.pdt_pkid
left join wms.bs_wh c on a.wh_id = c.wh_id
 where a.wh_id not in (select c.wh_id from (select a.*,b.wh_id from wms.bs_inv_warn a
 left join wms.bs_inv_warn_h b on a.biw_h_pkid=b.biw_h_pkid) c,wms.bs_invt t where c.wh_id=t.wh_id and c.part_no=t.part_no)
and a.part_no not in (select c.part_no from (select a.*,b.wh_id from wms.bs_inv_warn a
 left join wms.bs_inv_warn_h b on a.biw_h_pkid=b.biw_h_pkid) c,wms.bs_invt t where c.wh_id=t.wh_id and c.part_no=t.part_no)";

        $sql = "select
	c.wh_name,
	a.wh_id,
	a.part_no,
	f.pdt_name,
	ceil(a.invt_num) invt_num,
  b.tp_spec
from
	wms.bs_invt a
left join pdt.bs_partno b on a.part_no = b.part_no
left join pdt.bs_product f on b.pdt_pkid=f.pdt_pkid
left join wms.bs_wh c on a.wh_id = c.wh_id
 where a.wh_id not in (select c.wh_id from (select a.*,b.wh_id from wms.bs_inv_warn a
 left join wms.bs_inv_warn_h b on a.biw_h_pkid=b.biw_h_pkid) c,wms.bs_invt t where c.wh_id=t.wh_id and c.part_no=t.part_no)
and a.part_no not in (select c.part_no from (select a.*,b.wh_id from wms.bs_inv_warn a
 left join wms.bs_inv_warn_h b on a.biw_h_pkid=b.biw_h_pkid) c,wms.bs_invt t where c.wh_id=t.wh_id and c.part_no=t.part_no)";


        $t2c = new Trans();
        $queryParams = null;
        if (!empty($params['wh_id'])) {
            $sql = $sql . '  and a.wh_id = \'' . $params['wh_id'] . '\'';
            $countsql = $countsql . '  and a.wh_id = \'' . $params['wh_id'] . '\'';
        }
        if(isset($params['searchText']))
        {
            $sql=$sql.' and (a.part_no like \'%'.trim($t2c->t2c($params['searchText'])).'%\' or f.pdt_name like \'%'.trim($t2c->t2c($params['searchText'])).'%\' or b.tp_spec like \'%'.trim($t2c->t2c($params['searchText'])).'%\')';
            $countsql=$countsql.' and (a.part_no like \'%'.trim($t2c->t2c($params['searchText'])).'%\' or f.pdt_name like \'%'.trim($t2c->t2c($params['searchText'])).'%\' or b.tp_spec like \'%'.trim($t2c->t2c($params['searchText'])).'%\')';
        }
        $sql = $sql . ' order by a.opp_date DESC ';

        $totalCount = \Yii::$app->db->createCommand($countsql, $queryParams)->queryScalar();//总条数

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
        return $provider;
    }


    public function searchWaring($biw_h_pkid)
    {

        $BsInvWarmH=BsInvWarnH::findOne(['biw_h_pkid'=>$biw_h_pkid]);
        $sql = "SELECT
a.inv_id,
a.wh_id,
b.biw_h_pkid,
	b.part_no,
	c.pdt_name,
	ceil(b.down_nums) down_nums,
	ceil(b.save_num) save_num,
	ceil(d.invt_num) invt_num,
	ceil(b.up_nums) up_nums,
	e.wh_name,
	b.remarks,
	f.tp_spec,
	a.so_type
FROM
	wms.bs_inv_warn_h a
LEFT JOIN wms.bs_inv_warn b ON a.biw_h_pkid = b.biw_h_pkid
LEFT JOIN pdt.bs_partno f ON b.part_no=f.part_no
LEFT JOIN pdt.bs_product c ON f.pdt_pkid = c.pdt_pkid
LEFT JOIN wms.bs_wh e ON e.wh_id = a.wh_id
LEFT JOIN wms.bs_invt d ON (
	d.wh_id = a.wh_id && d.part_no = b.part_no
)  where 1=1 
and a.inv_id=:inv_id and a.wh_id=:wh_id
";


        $t2c = new Trans();
        $queryParams = ['inv_id'=>$BsInvWarmH->inv_id,'wh_id'=>$BsInvWarmH->wh_id];
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params'=>$queryParams
        ]);
        return $provider;
    }


    public function searchWaringInfo($biw_h_pkid)
    {

        $queryParams = [
            ':biw_h_pkid' => $biw_h_pkid
        ];
        $countsql = "SELECT
	count(*)
FROM
	wms.bs_inv_warn_h a
LEFT JOIN wms.bs_inv_warn b ON a.biw_h_pkid = b.biw_h_pkid
LEFT JOIN pdt.bs_partno f ON b.part_no=f.part_no
LEFT JOIN pdt.bs_product c ON f.pdt_pkid = c.pdt_pkid
LEFT JOIN wms.bs_wh e ON e.wh_id = a.wh_id
LEFT JOIN erp.hr_staff t ON a.OPPER = t.staff_code
LEFT JOIN erp.hr_organization g on t.organization_code= g.organization_code
LEFT JOIN wms.bs_invt d ON (
	d.wh_id = a.wh_id && d.part_no = b.part_no
)  where 1=1
and b.biw_h_pkid=:biw_h_pkid ";

        $sql = "SELECT
a.inv_id,
a.wh_id,
b.biw_h_pkid,
	b.part_no,
	c.pdt_name,
	ceil(b.down_nums) down_nums,
	ceil(b.save_num) save_num,
	ceil(d.invt_num) invt_num,
	ceil(b.up_nums) up_nums,
	e.wh_name,
	b.remarks,
	f.tp_spec,
  t.staff_name,
  t.staff_code,
  g.organization_name,
  a.OPP_DATE,
  a.so_type
FROM
	wms.bs_inv_warn_h a
LEFT JOIN wms.bs_inv_warn b ON a.biw_h_pkid = b.biw_h_pkid
LEFT JOIN pdt.bs_partno f ON b.part_no=f.part_no
LEFT JOIN pdt.bs_product c ON f.pdt_pkid = c.pdt_pkid
LEFT JOIN wms.bs_wh e ON e.wh_id = a.wh_id
LEFT JOIN erp.hr_staff t ON a.OPPER = t.staff_code
LEFT JOIN erp.hr_organization g on t.organization_code= g.organization_code
LEFT JOIN wms.bs_invt d ON (
	d.wh_id = a.wh_id && d.part_no = b.part_no
)  where 1=1
and b.biw_h_pkid=:biw_h_pkid";


        $totalCount = \Yii::$app->db->createCommand($countsql, $queryParams)->queryScalar();//总条数

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
        return $provider;
    }


}