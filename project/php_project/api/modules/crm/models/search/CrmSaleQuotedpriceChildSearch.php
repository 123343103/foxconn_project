<?php

namespace app\modules\crm\models\search;

use app\modules\common\models\BsCompany;
use app\modules\crm\models\show\CrmSaleQuotedpriceChildShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmSaleQuotedpriceChild;

/**
 * CrmSaleQuotedpriceChildSearch represents the model behind the search form about `app\modules\crm\models\CrmSaleQuotedpriceChild`.
 */
class CrmSaleQuotedpriceChildSearch extends CrmSaleQuotedpriceChild
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sapl_id', 'saph_id', 'num', 'MOQ', 'order_num', 'status'], 'integer'],
            [['part_no', 'brand', 'remark'], 'safe'],
            [['ws_origin_unit_price', 'origin_unit_price', 'ws_local_unit_price', 'local_unit_price', 'ws_origin_total_price', 'origin_total_price', 'ws_local_total_price', 'local_total_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$id)
    {
        $query = CrmSaleQuotedpriceChildShow::find()->joinWith('saleQuotedprice')->where(['!=','crm_sale_quotedprice_l.status',self::STATUS_DEL])->andWhere(['crm_sale_quotedprice_h.cust_id'=>$id]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageParam'=>"page",
                'pageSizeParam'=>'rows'
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
    public function searchLast($params)
    {
        if($params['id']==null){
            $query = CrmSaleQuotedpriceChildShow::find()->joinWith('saleQuotedprice')->where(['!=','crm_sale_quotedprice_l.status',self::STATUS_DEL])->andWhere(["between", "crm_sale_quotedprice_h.saph_date", date("Y-m-d",strtotime("-1 week")),date("Y-m-d",time())])->andWhere(['in','crm_sale_quotedprice_h.corp_id',BsCompany::getIdsArr($params['companyId'])]);
        }else{
            $query = CrmSaleQuotedpriceChildShow::find()->joinWith('saleQuotedprice')->where(['!=','crm_sale_quotedprice_l.status',self::STATUS_DEL])->andWhere(['crm_sale_quotedprice_h.cust_id'=>$params['id']])->andWhere(["between", "crm_sale_quotedprice_h.saph_date", date("Y-m-d",strtotime("-1 week")),date("Y-m-d",time())])->andWhere(['in','crm_sale_quotedprice_h.corp_id',BsCompany::getIdsArr($params['companyId'])]);
        }
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 5;
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
        return $dataProvider;
    }
}
