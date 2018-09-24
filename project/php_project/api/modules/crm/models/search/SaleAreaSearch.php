<?php

namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmCustomerStatus;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\show\SaleAreaShow;
use app\modules\common\models\BsCompany;

class SaleAreaSearch extends CrmSalearea
{

    public function rules()
    {
        return [

            [['csarea_code', 'csarea_name', 'csarea_status', 'csarea_remark'], 'safe'],
            [['csarea_code', 'csarea_name', 'csarea_status'], 'string', 'max' => 20],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SaleAreaShow::find()->where(['!=', 'csarea_status', CrmSalearea::STATUS_DELETE]);
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
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $trans = new Trans();
        if (empty($this->csarea_status)) {
            $query->andFilterWhere(['=', "csarea_status", 20]);
        }
        if (!empty($params['SaleAreaSearch']['csarea_status'])) {
            if ($params['SaleAreaSearch']['csarea_status'] == 30) {
                $csarea_status = "";
            }
            if ($params['SaleAreaSearch']['csarea_status'] == 20) {
                $csarea_status = 20;
            }
            if ($params['SaleAreaSearch']['csarea_status'] == 10) {
                $csarea_status = 10;
            }
        }else{
            $csarea_status="";
        }
        $query->andFilterWhere(['or', ['like', "csarea_code", $trans->t2c(trim($this->csarea_code))], ['like', "csarea_code", $trans->c2t(trim($this->csarea_code))]])
            ->andFilterWhere(['or', ['like', "csarea_name", $trans->t2c(trim($this->csarea_name))], ['like', "csarea_name", $trans->c2t(trim($this->csarea_name))]])
            ->andFilterWhere(['=', "csarea_status", $csarea_status]);//$this->csarea_status
        return $dataProvider;
    }
}
