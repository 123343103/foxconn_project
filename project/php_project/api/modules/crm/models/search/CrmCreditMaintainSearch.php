<?php

namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCreditMaintain;
use yii\db\Query;

/**
 * CrmCreditMaintainSearch represents the model behind the search form about `app\modules\crm\models\CrmCreditMaintain`.
 */
class CrmCreditMaintainSearch extends CrmCreditMaintain
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_by', 'update_by'], 'integer'],
            [['code', 'credit_name', 'remark', 'credit_status', 'create_at', 'update_at'], 'safe'],
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
    public function search($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'id',
            'code',
            'credit_name',
            'maintain.remark',
            'credit_status',
            'maintain.create_at',
            'maintain.update_at',
            'hr_1.staff_name create',
            'hr_2.staff_name update',
            '(CASE credit_status WHEN '.CrmCreditMaintain::STATUS_DEFAULT.' THEN "启用" WHEN '.CrmCreditMaintain::STATUS_FORBID.' THEN "禁用" ELSE "删除" END) as status',
        ])->from(CrmCreditMaintain::tableName().' maintain')
            ->leftJoin(HrStaff::tableName().' hr_1','hr_1.staff_id = maintain.create_by')
            ->leftJoin(HrStaff::tableName().' hr_2','hr_2.staff_id = maintain.update_by')
            ->where(['!=','credit_status',CrmCreditMaintain::STATUS_DELETE])
        ;


        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
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

//        $query->andFilterWhere([
//            'credit_status' => $params['status'],
//        ]);
        if (!empty($params['keyWords'])) {
            $query->andFilterWhere([
                'or',
                ['like', 'code', trim($params['keyWords'])],
                ['like', 'credit_name', trim($params['keyWords'])],
                ['like', 'credit_name', $trans->t2c(trim($params['keyWords']))],
                ['like', 'credit_name', $trans->c2t(trim($params['keyWords']))],
            ]);
        }
//        echo $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}
