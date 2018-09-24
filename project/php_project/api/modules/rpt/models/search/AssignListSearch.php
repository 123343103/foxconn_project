<?php
namespace app\modules\rpt\models\search;
use app\models\User;
use app\modules\hr\models\HrStaff;
use app\modules\rpt\models\RptAssign;
use app\modules\rpt\models\RptTemplate;
use app\modules\system\models\AuthItem;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * 分配列表
 */
class AssignListSearch extends RptAssign
{
    /**
     * 规则
     */
    public function rules()
    {
        return [
//            [['staff_code', 'staff_name', 'csarea_name'], 'safe'],
        ];
    }

    /**
     * 场景
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 模糊查询分配列表
     */
    public function searchById($params)
    {
        $query=(new Query())->select([
            'R.rpta_id',
            'R.rpt_id',
            'R.roru',
            'R.rpta_type',
            'R.cdate',
            'R.udate',
            'if(R.udate,R.udate,R.cdate) my_date',
            'A.title',
            'H1.staff_name',
            'H2.staff_name assign_by',
        ])->from(RptAssign::tableName() . ' R')
            ->leftJoin(AuthItem::tableName() . ' A','A.type=1 AND R.roru=A.`name` AND R.rpta_type=10')
            ->leftJoin(User::tableName() . ' U','R.roru=U.user_id AND R.rpta_type=11')
            ->leftJoin(HrStaff::tableName() . ' H1','U.staff_id=H1.staff_id')
            ->leftJoin(HrStaff::tableName() . ' H2','U.create_by=H2.staff_id')
            ->where(['R.rpt_id'=>intval($params['tpId'])])
            ->orderBy('R.rpta_type');
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
        ]);
        return $dataProvider;
    }

    // 搜索用户角色分配到的模板id
    public function searchIdByUser($params)
    {
        $query = RptAssign::find();
        $query->select('rpt_id')->distinct()
        ->where(['or',
            ['and', ['rpta_type'=>10], ['in','roru',$params['roles']]],
            ['and', ['rpta_type'=>11], ['=','roru',$params['uid']]]])
        ->orderBy('rpt_id');

//        if (isset($params['rows'])) {
//            $pageSize = $params['rows'];
//        } else {
//            $pageSize = 10;
//        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ],
        ]);
        return $dataProvider;
    }
}
