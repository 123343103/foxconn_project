<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/13
 * Time: 下午 04:39
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\show\WhAdmShow;
use app\modules\warehouse\models\WhAdm;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use Yii;
use app\modules\hr\models\HrStaff;

class WhAdmSearch extends WhAdm
{
    /**
     * @inheritdoc
     */
    public $wh_code;
    public $emp_no;
    public $wh_name;
    public $wh_state;
    public $wh_type;
    public $wh_lev;
    public $wh_attr;

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['wh_code', 'emp_no', 'wh_name', 'wh_state', 'wh_type', 'wh_lev', 'wh_attr'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = WhAdmShow::find()->orderBy("opp_date");
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
            'sort' => [         //查询按照操作时间倒序
                'defaultOrder' => ['OPP_DATE' => SORT_DESC],
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
//        $query->joinWith('hrStaff');
        $HrCode=null;
        if(!empty($params['hrstaff_name']))
        {
            $info=HrStaff::getStaffByName($params['hrstaff_name']);
            $HrCode=$info->staff_code;
        }
        $query->joinWith('bsWh');
        $query->andFilterWhere(['like', 'bs_Wh.wh_code', empty($params['wh_code']) ? '' : $params['wh_code']])
            ->andFilterWhere(['like', 'bs_Wh.wh_name', empty($params['wh_name']) ? '' : $params['wh_name']])
            ->andFilterWhere(['=', 'bs_Wh.wh_type', empty($params['wh_type']) ? '' : $params['wh_type']])
            ->andFilterWhere(['=', 'bs_Wh.wh_state', empty($params['wh_state']) ? '' : $params['wh_state']])
            ->andFilterWhere(['=', 'bs_Wh.wh_lev', empty($params['wh_lev']) ? '' : $params['wh_lev']])
            ->andFilterWhere(['=', 'bs_Wh.wh_attr', empty($params['wh_attr']) ? '' : $params['wh_attr']])
            ->andFilterWhere(['=', 'wh_adm.emp_no', empty($HrCode) ? '' : $HrCode]);
        return $dataProvider;
    }

}