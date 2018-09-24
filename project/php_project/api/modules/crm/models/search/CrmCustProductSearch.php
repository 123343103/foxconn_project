<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustProductShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustProduct;

class CrmCustProductSearch extends CrmCustProduct
{

    public function rules()
    {
        return [
            [['ccp_id', 'cust_id', 'create_by', 'update_by'], 'integer'],
            [['ccp_sname', 'ccp_model', 'ccp_annual', 'ccp_brand', 'ccp_remark', 'create_at', 'update_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$id)
    {

        $query = CrmCustProductShow::find()->where(['and',['cust_id'=>$id],['=','status',CrmCustProduct::STATUS_DEFAULT]]);

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

        if (!$this->validate()) {

            return $dataProvider;
        }

        return $dataProvider;
    }
}
