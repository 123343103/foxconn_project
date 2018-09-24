<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/23
 * Time: 上午 10:59
 */

namespace app\modules\warehouse\models\search;


use app\modules\common\models\BsProduct;
use app\modules\sale\models\SaleOrderh;
use app\modules\warehouse\models\IcInvh;
use app\modules\warehouse\models\IcInvl;
use app\modules\warehouse\models\show\IcInvlShow;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\modules\common\models\BsCompany;
use app\classes\Trans;
use app\modules\crm\models\CrmCarrier;

class IcInvlSearch extends IcInvl
{
    //public $SohCode;
    public $pdt_no;
    public function rules()
    {
        return [
            [['invl_id', 'invh_id', 'origin_id', 'p_bill_id', 'category_id', 'part_no',
                'comp_pdtid', 'lor_id', 'abnormal_id', 'origin_quantity', 'p_origin_quantity',
                'out_quantity', 'real_oquantity', 'in_quantity', 'real_quantity', 'invoice_quantity',
                'pack_quantity', 'suttle', 'gross_weight', 'inout_time', 'origin_type',
                'p_origin_type', 'batch_no', 'bar_code', 'pack_type', 'logistics_no', 'brand', 'is_largess', 'subitem_remark','pdt_no'], 'safe']
        ];

    }
//根据订单号查询商品信息
    public function search($params)
    {
        $query = IcInvlShow::find();
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
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $SohCode = 0;
        if (!empty($params['soh_code'])) {
           $info = SaleOrderh::find()->select(['soh_id'])->where(['saph_code' => $params['soh_code']])->one();
           // $info = SaleOrderh::getSohId($params['soh_code']);
            $SohCode = (int)$info['soh_id'];
        }
        //dumpE($SohCode);
        //$query->joinWith('saleOrderh');
        // $query->joinWith(['product']);
        $query->joinWith('icInvh', $eagerLoading = true, $joinType = 'RIGHT JOIN');
        $query->andFilterWhere(['=', 'wms.ic_invl.origin_id', $SohCode]);
       // $query->andFilterWhere(['=', 'ic_invh.invh_code', empty($params['invh_code'])?'':$params['invh_code']]);
        file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }
//根据出货单号查询商品信息
    public function searchcode($params)
    {
        $query = IcInvlShow::find();
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
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('icInvh', $eagerLoading = true, $joinType = 'RIGHT JOIN');
        $query->andWhere(['=', 'ic_invh.invh_code', empty($params['invh_code']) ? '' :$params['invh_code']]);
        //file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }
    //根据物流单号查询商品信息
    public function searchorder($params)
    {
        $query = IcInvlShow::find();
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
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('icInvh', $eagerLoading = true, $joinType = 'RIGHT JOIN');
        $query->andWhere(['=', 'ic_invh.logistics_no', empty($params['ORDERNO']) ? '' :$params['ORDERNO']]);
        //file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }
//全部条件查询商品信息
    public function searchall($params)
    {
        $query = IcInvlShow::find();
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
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $SohCode = 0;
        if (!empty($params['soh_code'])) {
            $info = SaleOrderh::find()->select(['soh_id'])->where(['saph_code' => $params['soh_code']])->one();
            // $info = SaleOrderh::getSohId($params['soh_code']);
            $SohCode = (int)$info['soh_id'];
        }
       if(empty($params['soh_code']) && !empty($params['invh_code']))
        {
            $invhinfo=IcInvh::getInvhId($params['invh_code']);
            $invhid=(int)$invhinfo['invh_id'];
            $info = IcInvl::getIcInvlid($invhid);
            $SohCode = (int)$info['origin_id'];
        }
        //dumpE($SohCode);
        //$query->joinWith('saleOrderh');
        // $query->joinWith(['product']);
        $query->joinWith('icInvh', $eagerLoading = true, $joinType = 'RIGHT JOIN');
        $query->andFilterWhere(['=', IcInvl::tableName() . '.origin_id', $SohCode]);
        $query->andFilterWhere(['=', 'ic_invh.invh_code', empty($params['invh_code']) ? '' :$params['invh_code']]);
        $query->andFilterWhere(['=', 'ic_invh.logistics_no', empty($params['ORDERNO']) ? '' :$params['ORDERNO']]);
        // $query->andFilterWhere(['=', 'ic_invh.invh_code', empty($params['invh_code'])?'':$params['invh_code']]);
        file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }

    //搜索其他入库单对应的商品
    public function searchOtherStockInProduct($params)
    {
        //绑定参数
        $queryParams=[
            ':default_status'=>IcInvl::DEFAULT_STATUS,
            ':id'=>(int)$params['id']
        ];
        //统计总数sql
        $countSql="select count(a.invl_id)
                   from wms.ic_invl a
                   left join erp.bs_product b on b.pdt_id = a.pdt_id
                   left join erp.bs_brand c on c.BRAND_ID = b.brand_id
                   left join erp.bs_category_unit d on d.id = b.unit
                   left join erp.category_attr e on e.CATEGORY_ATTR_ID = b.CATEGORY_ATTR_ID
                   left join wms.bs_st f on f.st_id = a.lor_id
                   where a.invl_status = :default_status
                   and a.invh_id = :id";
        //查询sql
        $querySql="select a.invl_id,
                          b.pdt_no,
                          b.pdt_name,
                          c.BRAND_NAME_CN,
                          e.ATTR_NAME,
                          a.batch_no,
                          a.in_quantity,
                          a.real_quantity,
                          a.in_warehouse_quantity,
                          d.unit_name,
                          a.pack_type,
                          a.pack_quantity,
                          f.part_code
                   from wms.ic_invl a
                   left join erp.bs_product b on b.pdt_id = a.pdt_id
                   left join erp.bs_brand c on c.BRAND_ID = b.brand_id
                   left join erp.bs_category_unit d on d.id = b.unit
                   left join erp.category_attr e on e.CATEGORY_ATTR_ID = b.tp_spec
                   left join wms.bs_st f on f.st_id = a.lor_id
                   where a.invl_status = :default_status
                   and a.invh_id = :id";
        //总条数
        $totalCount=\Yii::$app->db->createCommand($countSql,$queryParams)->queryScalar();
        //查询sql排序
        $querySql.=" order by a.invl_id desc";
        //SQL数据提供者
        $provider=new SqlDataProvider([
            'sql'=>$querySql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        return $provider;
    }
}