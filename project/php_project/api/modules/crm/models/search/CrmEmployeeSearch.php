<?php

namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\CrmSaleRoles;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmEmployee;
use yii\db\Query;

/**
 * CrmEmployeeSearch represents the model behind the search form about `app\modules\crm\models\CrmEmployee`.
 */
class CrmEmployeeSearch extends CrmEmployee
{
    /**
     * @inheritdoc
     */
    public $keyWord;
    public function rules()
    {
        return [
            [['staff_id', 'sarole_id', 'sts_id', 'leader_id', 'leaderrole_id', 'category_id','sale_area'], 'integer'],
            [['staff_code', 'sts_superior', 'sts_boss', 'sale_area','keyWord','sale_status'], 'safe'],
            [['sale_qty', 'sale_quota'], 'number'],
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
        $query=(new Query())->select([
            CrmEmployee::tableName().'.staff_code',
            CrmEmployee::tableName().'.staff_code code',
            CrmEmployee::tableName().'.sale_qty',
            CrmEmployee::tableName().'.sale_quota',
            CrmEmployee::tableName().'.create_at',
            CrmEmployee::tableName().'.update_at',
            CrmEmployee::tableName().'.category_id',
            CrmEmployee::tableName().'.staff_id id',
            CrmSaleRoles::tableName().'.sarole_sname',
            "(CASE crm_employee.sale_status WHEN ".CrmEmployee::SALE_STATUS_DEFAULT." THEN '启用' ELSE '禁用' END) as  status",
            HrStaff::tableName().'.staff_name',
            HrStaff::tableName().'.staff_id',
            CrmStoresinfo::tableName().'.sts_sname',
            CrmStoresinfo::tableName().'.sts_id',
            CrmSalearea::tableName().'.csarea_name',
            CrmSalearea::tableName().'.csarea_id',
            "hr_1.staff_name create_by",
            "hr_2.staff_name update_by",
        ])->from(CrmEmployee::tableName())
            ->leftJoin(HrStaff::tableName(),HrStaff::tableName().'.staff_code='.CrmEmployee::tableName().'.staff_code')
            ->leftJoin(CrmSaleRoles::tableName(),CrmSaleRoles::tableName().'.sarole_id='.CrmEmployee::tableName().'.sarole_id')
            ->leftJoin(CrmStoresinfo::tableName(),CrmStoresinfo::tableName().'.sts_id='.CrmEmployee::tableName().'.sts_id')
            ->leftJoin(CrmSalearea::tableName(),CrmSalearea::tableName().'.csarea_id='.CrmEmployee::tableName().'.sale_area')
            ->leftJoin(HrStaff::tableName()." hr_1","hr_1.staff_id=".CrmEmployee::tableName().".create_by")
            ->leftJoin(HrStaff::tableName()." hr_2","hr_2.staff_id=".CrmEmployee::tableName().".update_by")
            ->where(['!=',CrmEmployee::tableName().'.sale_status',CrmEmployee::SALE_STATUS_DEL])
            ->orderBy([CrmEmployee::tableName().'.create_at'=>SORT_DESC]);
        ;
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            if(isset($params['export'])){
                $pageSize =false;
            }else{
                $pageSize =10;
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
        $trans=new Trans();
        if(empty($this->sale_status)){
            $query->andFilterWhere([ 'crm_employee.sale_status'=>20]);
        }
        if (!empty($params['CrmEmployeeSearch']['sale_status'])) {
            if ($params['CrmEmployeeSearch']['sale_status'] == 30) {
                $csarea_status = "";
            }
            if ($params['CrmEmployeeSearch']['sale_status'] == 20) {
                $csarea_status = 20;
            }
            if ($params['CrmEmployeeSearch']['sale_status'] == 10) {
                $csarea_status = 10;
            }
        }else{
            $csarea_status="";
        }
        $query->andFilterWhere([
            'crm_employee.sarole_id' => $this->sarole_id,
            'crm_employee.sts_id' => $this->sts_id,
            'crm_employee.sale_area'=>$this->sale_area,
            'crm_employee.sale_status'=>$csarea_status,
        ]);
            $query->andFilterWhere(['or',
                ['like',CrmEmployee::tableName().'.staff_code',trim($this->keyWord)],
                ['like',HrStaff::tableName().'.staff_name',trim($this->keyWord)],
                ['like',HrStaff::tableName().'.staff_name',$trans->t2c(trim($this->keyWord))],
                ['like',HrStaff::tableName().'.staff_name',$trans->c2t(trim($this->keyWord))]
            ]);
        return $dataProvider;
    }

    public function searchManager($params)
    {
        $query=(new Query())->select([
            CrmEmployee::tableName().'.staff_code',
            CrmEmployee::tableName().'.sale_qty',
            CrmEmployee::tableName().'.sale_quota',
            CrmEmployee::tableName().'.create_at',
            CrmEmployee::tableName().'.update_at',
            CrmEmployee::tableName().'.category_id',
            CrmEmployee::tableName().'.staff_id id',
            HrStaff::tableName().'.staff_name',
            HrStaff::tableName().'.staff_id',
            CrmStoresinfo::tableName().'.sts_sname',
            CrmStoresinfo::tableName().'.sts_id',
            CrmSalearea::tableName().'.csarea_name',
            CrmSalearea::tableName().'.csarea_id',
            "hr_1.staff_name create_by",
            "hr_2.staff_name update_by",
        ])->from(CrmEmployee::tableName())
            ->leftJoin(HrStaff::tableName(),HrStaff::tableName().'.staff_code='.CrmEmployee::tableName().'.staff_code')
            ->leftJoin(CrmStoresinfo::tableName(),CrmStoresinfo::tableName().'.sts_id='.CrmEmployee::tableName().'.sts_id')
            ->leftJoin(CrmSalearea::tableName(),CrmSalearea::tableName().'.csarea_id='.CrmEmployee::tableName().'.sale_area')
            ->leftJoin(HrStaff::tableName()." hr_1","hr_1.staff_id=".CrmEmployee::tableName().".create_by")
            ->leftJoin(HrStaff::tableName()." hr_2","hr_2.staff_id=".CrmEmployee::tableName().".update_by")
            ->where(['=',CrmEmployee::tableName().'.sale_status',CrmEmployee::SALE_STATUS_DEFAULT])
            ->andWhere(['=',CrmEmployee::tableName().'.isrule',CrmEmployee::SALE_MANAGER_Y])
        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['rows'])?$params['rows']:'10',
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $trans=new Trans();
        $query->andFilterWhere([
            'crm_employee.sale_area' => $this->sale_area,
            'crm_employee.sts_id' => $this->sts_id,
        ]);
        if(!empty($params['keyWord'])){
            $query->andFilterWhere(['or',
                ['like',CrmEmployee::tableName().'.staff_code',trim($params['keyWord'])],
                ['like',HrStaff::tableName().'.staff_name',trim($params['keyWord'])],
                ['like',HrStaff::tableName().'.staff_name',$trans->t2c(trim($params['keyWord']))],
                ['like',HrStaff::tableName().'.staff_name',$trans->c2t(trim($params['keyWord']))],
                ['like',CrmSalearea::tableName().'.csarea_name',trim($params['keyWord'])],
                ['like',CrmSalearea::tableName().'.csarea_name',$trans->t2c(trim($params['keyWord']))],
                ['like',CrmSalearea::tableName().'.csarea_name',$trans->c2t(trim($params['keyWord']))],
                ['like',CrmStoresinfo::tableName().'.sts_sname',trim($params['keyWord'])],
                ['like',CrmStoresinfo::tableName().'.sts_sname',$trans->t2c(trim($params['keyWord']))],
                ['like',CrmStoresinfo::tableName().'.sts_sname',$trans->c2t(trim($params['keyWord']))],
            ]);
        }
//                ->orFilterWhere(['or', ['like',HrStaff::tableName().'.staff_name',$trans->t2c($this->keyWord)], ['like',HrStaff::tableName().'.staff_name',$trans->c2t($this->keyWord)]])
        ;
//        $a = clone $query;
//        echo $a->createCommand()->getRawSql();exit();
        return $dataProvider;
    }
}
