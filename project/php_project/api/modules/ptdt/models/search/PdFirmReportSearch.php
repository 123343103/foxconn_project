<?php

namespace app\modules\ptdt\models\Search;

use app\classes\Trans;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\show\PdFirmReportShow;
use app\modules\common\models\BsCompany;
class PdFirmReportSearch extends PdFirmReport
{
    public $firm_type;
    public $firm_issupplier;
    public $firm_sname;
    public $firm_category_id;
    public $firmMessage;
    public function rules()
    {
        return [
            [['pfr_id', 'firm_id', 'report_status', 'pdn_id', 'pdnc_id', 'pdna_id', 'pdaa_id', 'report_verifyter', 'create_by', 'update_by','firm_issupplier','firm_type'], 'integer'],
            [['report_code', 'report_agents_type', 'report_develop_type', 'report_urgency_degree', 'report_date', 'report_senddate', 'report_verifydate', 'report_remark', 'create_at', 'update_at'], 'safe'],
            [['firm_sname','firm_category_id','firmMessage'], 'string', 'max' => 60],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }
    public function search($params)
    {
        $trans = new Trans();
        $query = PdFirmReportShow::find()->where(["!=", "report_status", self::REPORT_DELETE])->andWhere(['in','pd_firm_report.company_id',BsCompany::getIdsArr($params['companyId'])]);
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        //默認排序
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
//        $query->joinWith('firmMessage');
        $query->joinWith('firmType');

        $query->andFilterWhere([
            'firm_type'=>$this->firm_type,
            'firm_issupplier'=>$this->firm_issupplier,
            'report_status'=>$this->report_status,
        ]);

        $query->andFilterWhere(['or',['like','firm_category_id',$trans->c2t(trim($this->firm_category_id))],['like','firm_category_id',$trans->t2c(trim($this->firm_category_id))]])
            ->andFilterWhere(['or',['like','pd_firm.firm_sname',$trans->c2t(trim($this->firm_sname))],['like','pd_firm.firm_sname',$trans->t2c(trim($this->firm_sname))]])
            ->orFilterWhere(['or',['like','pd_firm.firm_shortname',$trans->c2t(trim($this->firm_sname))],['like','pd_firm.firm_shortname',$trans->t2c(trim($this->firm_sname))]])
        ;
        return $dataProvider;

    }
    public function searchAnalysis($params)   //搜索方法
    {
        $trans = new Trans();
        $query = PdFirmReportShow::find();
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =5;
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
        $query->joinWith('firmMessage');
        $query->joinWith('firmType');

        $query->andFilterWhere(['or',['like', 'firm_sname', $trans->t2c(trim($this->firmMessage))],['like', 'firm_sname', $trans->c2t(trim($this->firmMessage))]])
            ->orFilterWhere(['or',['like', 'firm_shortname', $trans->t2c(trim($this->firmMessage))],['like', 'firm_shortname', $trans->c2t(trim($this->firmMessage))]])
            ->orFilterWhere(['or',['like', 'firm_compaddress', $trans->t2c(trim($this->firmMessage))],['like', 'firm_compaddress', $trans->c2t(trim($this->firmMessage))]])
            ->orFilterWhere(['or',['like', 'bsp_svalue', $trans->t2c(trim($this->firmMessage))],['like', 'bsp_svalue', $trans->c2t(trim($this->firmMessage))]])
        ;
        return $dataProvider;
    }
}
