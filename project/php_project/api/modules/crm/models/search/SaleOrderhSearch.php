<?php

namespace app\modules\crm\models\search;

use app\modules\common\models\BsCompany;
use app\modules\crm\models\show\SaleOrderhShow;
use app\modules\sale\models\SaleOrderh;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SaleOrderhSearch represents the model behind the search form about `app\modules\crm\models\SaleOrderh`.
 */
class SaleOrderhSearch extends SaleOrderh
{
    /**
     * @inheritdoc
     */
//    public function rules()
//    {
//        return [
//            [['soh_id', 'comp_id', 'cust_id', 'inv_cust_id', 'pat_id', 'pac_id', 'dec_id', 'cur_id', 'bill_from', 'sell_delegate', 'district_id', 'sell_manager', 'create_by', 'review_by', 'update_by', 'whs_id'], 'integer'],
//            [['bus_code', 'soh_code', 'soh_date', 'consignment_date', 'soh_type', 'soh_status', 'invoice_address', 'consignment_ogan', 'packtype_desc', 'contract_no', 'logistics_type', 'istock', 'stock_status', 'bill_remark', 'cdate', 'rdate', 'udate'], 'safe'],
//            [['bill_oamount', 'bill_camount'], 'number'],
//        ];
//    }

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
    public function search($params, $id)
    {
        $query = SaleOrderhShow::find()->where(['!=', 'soh_status', self::STATUS_DELETE])->andWhere(['cust_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

    public function searchLast($params)
    {
        if (empty($params['id'])) {
            $query = SaleOrderhShow::find()->where(['!=', 'saph_status', self::STATUS_DELETE])->andWhere(["between", "saph_date", date("Y-m-d", strtotime("-1 week")), date("Y-m-d", time())]);
//                ->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
        } else {
            $query = SaleOrderhShow::find()->where(['!=', 'saph_status', self::STATUS_DELETE])->andWhere(['cust_id' => $params['id']])->andWhere(["between", "saph_date", date("Y-m-d", strtotime("-1 week")), date("Y-m-d", time())]);
//                ->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])]);
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
