<?php
namespace app\modules\rpt\controllers;

use app\controllers\BaseActiveController;
use yii\data\SqlDataProvider;

class StockController extends BaseActiveController
{
    public $modelClass = 'app\modules\rpt\models\RptTemplate';
    //库存报表列表
    public function actionIndex()
    {
        $params = \Yii::$app->request->queryParams;
        $sql = "SELECT
                b.people,
                b.company,
                a.wh_name,
                a.part_no,
                a.pdt_name,
                a.tp_spec,
                a.wh_code,
                a.st_code,
                a.unit_name,
                a.invt_num,
                MAX(c.opp_date) mydate
                FROM
                    wms.bs_sit_invt a
                LEFT JOIN wms.bs_wh b ON a.wh_code = b.wh_code
                LEFT JOIN wms.l_invt_re c on a.part_no=c.part_no
                where 1=1";
        //查询
        if(!empty($params['company'])){
            $params['company']=str_replace(['%','_'],['\%','\_'],$params['company']);
            $queryParams[':company']='%'.$params['company'].'%';
            $sql.=" and b.company like :company";
        }
        if(!empty($params['wh_name'])){
            $params['wh_name']=str_replace(['%','_'],['\%','\_'],$params['wh_name']);
            $queryParams[':wh_name']='%'.$params['wh_name'].'%';
            $sql.=" and a.wh_name like :wh_name";
        }
        if(!empty($params['wh_code'])){
            $params['wh_code']=str_replace(['%','_'],['\%','\_'],$params['wh_code']);
            $queryParams[':wh_code']='%'.$params['wh_code'].'%';
            $sql.=" and a.wh_code like :wh_code";
        }

        if(!empty($params['part_no'])){
            $params['part_no']=str_replace(['%','_'],['\%','\_'],$params['part_no']);
            $queryParams[':part_no']='%'.$params['part_no'].'%';
            $sql.=" and a.part_no like :part_no";
        }
        $sql.=" group by a.invt_id";
        if(!empty($params['LogSearch']['startTime'])){
            $queryParams[':val5']=date('Y-m-d H:i:s',strtotime($params['LogSearch']['startTime'])           );
            $sql.=" having mydate >= :val5";
        }
        if(!empty($params['LogSearch']['endTime'])){
            $queryParams[':val6']=date('Y-m-d H:i:s',strtotime($params['LogSearch']['endTime']           .'+1 day'));
            $sql.=" and mydate < :val6";
        }
        $totalCount = \Yii::$app->db->createCommand("select count(*) from ({$sql}) A", empty($queryParams) ? [] : $queryParams)->queryScalar();
        $sql .= " order by c.opp_date desc";
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params' => empty($queryParams) ? [] : $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($params['rows']) ? false : $params['rows']
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }
}
