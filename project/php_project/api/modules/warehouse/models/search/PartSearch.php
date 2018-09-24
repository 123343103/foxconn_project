<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/12
 * Time: 下午 05:21
 */
namespace app\modules\warehouse\models\search;

use app\classes\Trans;
use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\BsSt;
use app\modules\warehouse\models\show\PartShow;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;

class PartSearch extends BsPart
{
    public $wh_name;
    public $YN;
    public $rack_code;
    public $st_code;

    public function rules()
    {
        return [
            [['part_name', 'part_code', 'wh_name', 'YN', 'rack_code', 'st_code'], 'safe']
        ];
    }

    public function search($params)
    {
//        $data = PartShow::find()->orderBy('OPP_DATE DESC');
//
//        if (isset($params['rows'])) {
//            $pageSize = $params['rows'];
//        } else {
//            $pageSize = 10;
//        }
//        $dataProvider = new ActiveDataProvider([
//            'query' => $data,
//            'key' => 'wh_code'
//        ]);
//        $this->load($params);
//        if (!$this->validate()) {
//            return $dataProvider;
//        }
//        $data->joinWith("whCode");
//        $data->joinWith('bsSt');
//        $data->andFilterWhere(['like', 'part_code', $this->part_code]);
//        $data->andFilterWhere(['like', 'part_name', $this->part_name]);
//        $data->andFilterWhere(['like', 'bs_wh.wh_name', $this->wh_name]);
//        $data->andFilterWhere(['like', 'bs_st.rack_code', $this->rack_code]);
//        if ($this->YN == '启用') {
//            $this->YN = 'Y';
//        } else if ($this->YN == '禁用') {
//            $this->YN = 'N';
//        }
//        $data->andFilterWhere(['bs_st.YN' => $this->YN]);
//        $data->andFilterWhere(['like', 'bs_st.st_code', $this->st_code]);
//        return $dataProvider;
        $countsql = "select count(*)  from wms.bs_st bs 
LEFT JOIN wms.bs_part bp on bs.part_code=bp.part_code
LEFT JOIN wms.bs_wh bw on bw.wh_id=bs.wh_id where 1=1  ";

        $sql = "SELECT bs.st_code,
              bs.st_id,
              bs.rack_code,
              bs.YN,
			  bs.NW_DATE,
              bs.remarks,
              bs.NWER,
              bs.part_code,bp.part_name,
              bw.wh_name,
               bs.OPPER,
               bs.OPP_DATE from wms.bs_st bs 
LEFT JOIN wms.bs_part bp on bs.part_code=bp.part_code
LEFT JOIN wms.bs_wh bw on bw.wh_id=bs.wh_id where 1=1  ";


        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }

        $t2c = new Trans();
        $queryParams = [];
        if (!empty($this->YN)) {
            if($this->YN!=3) {
                $sql = $sql . ' and bs.YN=:YN';
                $countsql = $countsql . ' and bs.YN=:YN';
                if ($this->YN == '启用') {
                    $this->YN = 'Y';
                } else if ($this->YN == '禁用') {
                    $this->YN = 'N';
                }
                $queryParams[':YN'] = $this->YN;//查询sql追加参数
            }
        }else{
            $sql = $sql . ' and bs.YN="Y"';
            $countsql = $countsql . ' and bs.YN="Y"';
        }
        if (!empty($this->part_code)) {
            $sql = $sql . ' and bs.part_code LIKE \'%' . $this->part_code . '%\'';

            $countsql = $countsql . ' and bs.part_code LIKE \'%' . $this->part_code . '%\'';
        }
//        return $t2c->c2t($this->wh_name);
        if (!empty($this->part_name)) {
//            $sql=$sql.' and bp.part_name LIKE \'%'.$this->part_name.'%\'';
            $sql = $sql . ' and (bp.part_name LIKE \'%' . $t2c->c2t($this->part_name) . '%\'';
            $sql = $sql . ' or bp.part_name LIKE \'%' . $t2c->t2c($this->part_name) . '%\')';

//            $countsql=$countsql.' and bp.part_name LIKE \'%'.$this->part_name.'%\'';
            $countsql = $countsql . ' and (bp.part_name LIKE \'%' . $t2c->c2t($this->part_name) . '%\'';
            $countsql = $countsql . ' or bp.part_name LIKE \'%' . $t2c->t2c($this->part_name) . '%\')';
        }
        if (!empty($this->wh_name)) {
//            $sql=$sql.' and bw.wh_name LIKE \'%'.$this->wh_name.'%\'';
            $sql = $sql . ' and (bw.wh_name LIKE \'%' . $t2c->t2c($this->wh_name) . '%\'';
            $sql = $sql . ' or bw.wh_name LIKE \'%' . $t2c->c2t($this->wh_name) . '%\')';

//            $countsql=$countsql.' and bw.wh_name LIKE \'%'.$this->wh_name.'%\'';
            $countsql = $countsql . ' and (bw.wh_name LIKE \'%' . $t2c->t2c($this->wh_name) . '%\'';
            $countsql = $countsql . ' or bw.wh_name LIKE \'%' . $t2c->c2t($this->wh_name) . '%\')';
        }
        if (!empty($this->st_code)) {
            $sql = $sql . ' and bs.st_code LIKE \'%' . $this->st_code . '%\'';

            $countsql = $countsql . ' and bs.st_code LIKE \'%' . $this->st_code . '%\'';
        }
        if (!empty($this->rack_code)) {
            $sql = $sql . ' and bs.rack_code LIKE \'%' . $this->rack_code . '%\'';

            $countsql = $countsql . ' and bs.rack_code LIKE \'%' . $this->rack_code . '%\'';
        }
        $sql = $sql . ' order by bs.OPP_DATE DESC ';

        $totalCount = \Yii::$app->db->createCommand($countsql, $queryParams)->queryScalar();//总条数

//        if (isset($params['rows'])) {
//            $pageSize = $params['rows'];
//        } else {
//            if (isset($params['export'])) {
//                $pageSize = false;
//            } else {
//                $pageSize = 10;
//            }
//        }
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($params['rows']) ? false : $params['rows']
            ]
        ]);
        return $provider;
    }

    public function export($params)
    {
        $countsql = "select count(*) from wms.bs_st bs,wms.bs_part bp,wms.bs_wh bw 
              where bs.part_code=bp.part_code 
              and bp.wh_code=bw.wh_code";

        $sql = "select 
              bw.wh_name,
              bs.part_code,
              bp.part_name,
              bs.rack_code,
              bs.st_code,              
              bs.YN,
              bs.remarks
              from wms.bs_st bs,wms.bs_part bp,wms.bs_wh bw 
              where bs.part_code=bp.part_code 
              and bp.wh_code=bw.wh_code";
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        $totalCount = \Yii::$app->db->createCommand($countsql, null)->queryScalar();//总条数
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => false
            ]
        ]);
        return $provider;
    }

    //获取仓库名称
    public function getWhname()
    {
        $list = PartShow::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $list,
            'key' => 'wh_code',
        ]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $list->joinWith("whCode");
        $list->groupBy('bs_wh.wh_name');
        return $dataProvider;
    }

    //根据st_id获取数据
    public function getViewsbyid($st_id)
    {
        $list = PartShow::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $list,
            'key' => 'wh_code',
        ]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $list->joinWith("bsSt");
        $list->andFilterWhere(['bs_st.st_id' => $st_id]);
        return $dataProvider;
    }

    //异动储位选择
    public function changeSearch($params)   //搜索方法
    {
        $query = (new Query())
            ->select([
                'bs.st_code',
                'bs.st_id',
                'bs.rack_code',
                'bs.YN',
                'bs.NW_DATE',
                'bs.remarks',
                'bs.NWER',
                'bs.part_code',
                'bp.part_name',
                'bw.wh_name',
                'bs.OPPER',
                'bs.OPP_DATE'
            ])
            ->from(['bs' => 'wms.bs_st'])
            ->leftJoin('wms.bs_part bp', 'bs.part_code=bp.part_code')
            ->leftJoin('wms.bs_wh bw', 'bw.wh_code=bp.wh_code');
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 5;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        $query->andFilterWhere(['bw.wh_id' => $params['whId']])
            ->andFilterWhere(['like', 'bs.part_code', isset($params['partCode']) ? $params['partCode'] : ""])
            ->andFilterWhere(['like', 'bs.rack_code', isset($params['rackCode']) ? $params['rackCode'] : ""])
            ->andFilterWhere(['like', 'bs.st_code', isset($params['storeCode']) ? $params['storeCode'] : ""]);

        return $dataProvider;
    }
}