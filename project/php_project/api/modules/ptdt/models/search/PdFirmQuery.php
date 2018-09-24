<?php

/**
 *厂商搜索模型
 */
namespace app\modules\ptdt\models\Search;

use app\modules\ptdt\models\show\PdFirmShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdFirm;
use app\modules\common\models\BsCompany;
/**
 * This is the ActiveQuery class for [[Firm]].
 *
 * @see Firm
 */
class PdFirmQuery extends PdFirm
{

    public $firmMessage;
    public function rules()
    {
        return [
            //[['firm_sname','firm_brand','firm_ename','firm_issupplier'], 'required'],
            [[ 'firm_status', 'firm_source', 'firm_type', 'firm_position', 'firm_issupplier', 'firm_pddepid', 'firm_productperson', 'firm_agentstype', 'firm_pdtype', 'firm_transacttype', 'firm_agentstype2', 'firm_agentslevel', 'firm_agents_position', 'firm_authorize_area', 'firm_salarea', 'create_by', 'update_by'], 'integer'],
            [['firm_authorize_bdate', 'firm_authorize_edate', 'create_at', 'update_at','firm_brand_english'], 'safe'],
            [['firm_code', 'firm_category_id', 'firm_comptype', 'firm_scale', 'firm_compprincipal', 'firm_comptel', 'firm_compfax', 'firm_compmail', 'firm_contaperson', 'firm_contaperson_tel', 'firm_contaperson_mobile', 'firm_contaperson_mail'], 'string', 'max' => 20],
            [['firm_sname','firmMessage', 'firm_shortname', 'firm_ename', 'firm_eshortname', 'firm_brand'], 'string', 'max' => 60],
            [['firm_compaddress','firm_annual_turnover', 'firm_remark1', 'firm_remark2', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'string', 'max' => 120],

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
    public function search($params,$companyId)   //搜索方法
    {
        $query = PdFirmShow::find()->where(['!=','firm_status',self::STATUS_DELETE])
            ->andWhere(['in','pd_firm.company_id',BsCompany::getIdsArr($companyId)]);

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
            $query->orderBy("firm_id desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }


        // grid filtering conditions
        $query->andFilterWhere([        //根据字段搜索
            'firm_id' => $this->firm_id,
            'firm_issupplier' => $this->firm_issupplier,
            'firm_type' => $this->firm_type,
            'firm_status' => $this->firm_status,
            //'firm_category_id' => unserialize($this->firm_category_id),
        ]);
        //根据字段模糊查询
        $query->andFilterWhere(['like', 'firm_sname', $this->firm_sname])
            ->andFilterWhere(['like','firm_category_id',$this->firm_category_id])
            ->andFilterWhere(['like', 'firm_brand', $this->firm_brand]);
        return $dataProvider;
    }

    public function searchQuery($params)   //搜索方法
    {
        $query = PdFirm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
//        $query->joinWith('firmType');

        //根据字段模糊查询
        $query->orFilterWhere(['like', 'firm_sname', $this->firmMessage])
            ->orFilterWhere(['like', 'firm_shortname', $this->firmMessage])
            ->orFilterWhere(['like', 'firm_compaddress', $this->firmMessage])
            ->orFilterWhere(['like', 'bsp_svalue', $this->firmMessage])
        ;

        return $dataProvider;
    }

}
