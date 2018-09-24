<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/7
 * Time: 下午 05:59
 */

namespace app\modules\warehouse\models\search;

use app\classes\Trans;
use app\modules\warehouse\models\WhAdm;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\show\SetWarehouseShow;
use yii\db\Query;

/**
 * SetWarehouseSearch represents the model behind the search form about `app\modules\warehouse\models\BsWh`.
 */
class SetWarehouseSearch extends BsWh
{
    /**
     * @inheritdoc
     */
    public $wh_code;
    public $wh_name;
    public $people;
    public $company;
    public $wh_addr;
    public $wh_state;
    public $wh_type;
    public $wh_lev;
    public $wh_attr;
    public $wh_YN;
    public $wh_YNw;
    public $remarks;
    public $OPPER;
    public $OPP_DATE;
    public $NWER;
    public $NW_DATE;
    public $DISTRICT_ID;
    public $opp_ip;
    public $wh_nature;
    public $emp_no;
    public function rules()
    {
        return [
            [['wh_code','people','company', 'wh_name', 'wh_addr', 'wh_state', 'wh_type', 'wh_lev', 'wh_attr', 'wh_YN', 'wh_YNw','remarks', 'OPPER', 'OPP_DATE', 'NWER', 'NW_DATE','wh_nature','opp_ip'], 'safe'],
            [['DISTRICT_ID'], 'integer'],
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
        $query = SetWarehouseShow::find();
        // add conditions that should always apply here
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [         //查询按照创建时间倒序
                'defaultOrder' => ['NW_DATE'=> SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'DISTRICT_ID' => $this->DISTRICT_ID,
            'OPP_DATE' => $this->OPP_DATE,
            'NW_DATE' => $this->NW_DATE,
        ]);

        $query->andFilterWhere(['like', 'wh_code', $this->wh_code])
            ->andFilterWhere(['like', 'wh_name', $this->wh_name])
            ->andFilterWhere(['like', 'people', $this->people])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'wh_addr', $this->wh_addr])
            ->andFilterWhere(['like', 'wh_state', $this->wh_state])
            ->andFilterWhere(['like', 'wh_type', $this->wh_type])
            ->andFilterWhere(['like', 'wh_lev', $this->wh_lev])
            ->andFilterWhere(['like', 'wh_attr', $this->wh_attr])
            ->andFilterWhere(['like', 'wh_YN', $this->wh_YN])
            ->andFilterWhere(['like', 'wh_YNw', $this->wh_YNw])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'OPPER', $this->OPPER])
            ->andFilterWhere(['like', 'NWER', $this->NWER]);

        return $dataProvider;
    }
    public function searchPlace($params)
    {
        $query = BsWh::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['rows'])?$params['rows']:'10',
            ],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $trans=new Trans();
        if(!empty($params['keyWord'])) {
            $query->andFilterWhere(['or',
                ['like',BsWh::tableName().'.wh_code',trim($params['keyWord'])],
                ['like',BsWh::tableName().'.wh_name',trim($params['keyWord'])],
                ['like',BsWh::tableName().'.wh_name',$trans->c2t(trim($params['keyWord']))],
                ['like',BsWh::tableName().'.wh_name',$trans->t2c(trim($params['keyWord']))],
            ]);
        }
        return $dataProvider;
    }

    public function searchActiveName($params)
    {
        $db = Yii::$app->get('wms');
        $query=(new Query())->select([
            BsWh::tableName().'.*',
//            'IFNULL('.CrmActiveName::tableName().'.update_at,'.CrmActiveName::tableName().'.create_at) AS sort_at',
//            "(CASE ".CrmActiveName::tableName().".actbs_status WHEN ".CrmActiveName::ADD_STATUS." THEN '未开始' WHEN ".CrmActiveName::ALREADY_START." THEN '进行中' WHEN ".CrmActiveName::ALREADY_END." THEN '已结束' WHEN ".CrmActiveName::ALREADY_CANCEL." THEN '已取消' ELSE '删除' END) AS activeNameStatus",//活动状态
            WhAdm::tableName().'.*'
        ])->from(BsWh::tableName())
            ->leftJoin(WhAdm::tableName().' B','B.wh_code='.BsWh::tableName().'.wh_code');
//            ->where(['!=',CrmActiveName::tableName().'.actbs_status',CrmActiveName::DELETE_STATUS]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ],
        ]);
        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'wh_code' => '倉庫代碼',
            'wh_name' => '倉庫名稱',
            'people' =>'法人，0.请选择1.鸿富锦精密工业(深圳)有限公司2.富泰华工业(深圳)有限公司',
            'company' =>'创业公司，0.请选择...1.公司一2.公司二3.公司三',
            'DISTRICT_ID' => '倉庫地址代碼.存儲省市區最小的代碼(erp.bs_district)',
            'wh_addr' => '倉庫詳情地址',
            'wh_state' => '倉庫狀態.Y：有效',
            'wh_type' => '倉庫類別，0.其它倉,1.普通倉庫2.恒溫恒濕倉庫3.危化品倉庫4.貴重物品倉庫',
            'wh_lev' => '倉庫級別,0.其它，1.一級，2.二級，3.三級',
            'wh_attr' => '倉庫屬性.N.外租Y.自有',
            'wh_YN' => '是否报废仓：Y是',
            'wh_YNw' => '是否自提点：Y是',
            'remarks' => '備注',
            'OPPER' => '操作人',
            'OPP_DATE' => '操作時間',
            'NWER' => '創建人',
            'NW_DATE' => '創建時間',
            'wh_nature' => 'Y.保稅,N非保稅',
            'opp_ip' => 'IP地址',
        ];
    }
}