<?php

namespace app\modules\crm\models\search;

use app\modules\crm\models\show\CrmCustomerApplyShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\show\CustomerExportShow;
use app\classes\Trans;
use app\modules\common\models\BsCompany;
/**
 * CrmCustomerApplySearch represents the model behind the search form about `app\modules\crm\models\CrmCustomerApply`.
 */
class CrmCustomerApplySearch extends CrmCustomerApply
{
    public $cust_level;
    public $cust_filernumber;
    public $cust_sname;
    public $cust_type;
    public $ccpich_personid;
    public $staff_name;
    public $cust_contacts;
    public $cust_salearea;
    public $cust_manager;
    public $staff_id;
    public $cust_area;
    public $applyperson;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['capply_id','cust_level', 'cust_id', 'applydep', 'member_id','ccpich_personid', 'toverify', 'verifyperson'], 'integer'],
            [['applyno','cust_area','applyperson','staff_name','staff_id','cust_manager','cust_salearea','cust_contacts','cust_filernumber','cust_sname','cust_type', 'applydate', 'description', 'thereason', 'verifydate', 'status', 'remark'], 'safe'],
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
    public function export($params)
    {
        $trans = new Trans();
        $query = CustomerExportShow::find();

        $this->load($params);

        if (!$this->validate()) {
            return $query;
        }
        $query->joinWith("manager");
        $query->joinWith("buildStaff");

        $query->andFilterWhere([
            'cust_type' => $this->cust_type,
            'cust_salearea' => $this->cust_salearea,
        ]);
        $query->andFilterWhere(['or',['like', "cust_filernumber",$trans->t2c($this->cust_filernumber)],['like', "cust_filernumber",$trans->c2t($this->cust_filernumber)]])
            ->andFilterWhere(['or',['like', "cust_sname",$trans->t2c($this->cust_sname)],['like', "cust_sname",$trans->c2t($this->cust_sname)]])
            ->andFilterWhere(['or',['like', "u2.staff_name",$trans->t2c($this->create_by)],['like', "u2.staff_name",$trans->c2t($this->create_by)]])
        ;
        if ($this->startDate && !$this->endDate) {
            $query->andFilterWhere([">=", "create_at", $this->startDate]);
        }
        if ($this->endDate && !$this->startDate) {
            $query->andFilterWhere(["<=", "create_at", date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        if ($this->endDate && $this->startDate) {
            $query->andFilterWhere(["between", "create_at", $this->startDate, date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        return $query;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$companyId,$staffName)
    {
        $query = CrmCustomerApplyShow::find()->where(["and",["!=", "status", self::STATUS_WAIT],["!=","status",self::STATUS_CREATE]])->andWhere(["NOT",["status"=>""]]);
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
        //默認排序
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("applydate  desc,cust_filernumber desc");
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $this->load($params);
        $trans = new Trans();//繁简转换

        $query->joinWith('applyStaff');
        $query->joinWith('custCustomer');
        $query->joinWith('personinch');
        $query->joinWith("manager");
        $query->distinct('capply_id');
        $query->andFilterWhere([
            'cust_level' => $this->cust_level,
            'cust_type'=> $this->cust_type,
            'status' => $this->status,
            'cust_salearea'=> $this->cust_salearea,
            'applydate' => $this->applydate,
            'applydep' => $this->applydep,
            'member_id' => $this->member_id,
            'cust_area' => $this->cust_area,
        ]);
        $query->andFilterWhere(['=','hr_staff.staff_name',$staffName]);
        $query->andFilterWhere(['or', ['like', 'cust_filernumber', $trans->t2c(trim($this->cust_filernumber))], ['like', 'cust_filernumber', $trans->c2t(trim($this->cust_filernumber))]])
            ->andFilterWhere(['like', 'cust_sname', trim($this->cust_sname)])
            ->andFilterWhere(['or',['like','hr_staff.staff_name',$trans->t2c($this->cust_manager)],['like','hr_staff.staff_name',$trans->c2t($this->cust_manager)]])
            ->andFilterWhere(['like', 'cust_filernumber', $this->cust_filernumber])
            ->andFilterWhere(['like', 'cust_contacts', $this->cust_contacts])
//            ->andFilterWhere(['or',['like','cust_contacts',$trans->t2c($this->cust_contacts)],['like','cust_contracts',$trans->c2t($this->cust_contacts)]])
            ->andFilterWhere(['or',['like','u2.staff_name',$trans->t2c($this->applyperson)],['like','u2.staff_name',$trans->c2t($this->applyperson)]]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 客户管理--我的申请
     */
    public function searchApply($params)
    {
//        if(empty($params['id'])){
//            $query = CrmCustomerApplyShow::find()->where(["!=", "is_delete", self::DELETE])->andWhere(['applyperson'=>$params['staff_id']])->andWhere(['in', 'crm_customer_apply.company_id', BsCompany::getIdsArr($params['companyId'])]);
//        }else{
//            $query = CrmCustomerApplyShow::find()->where(["!=", "is_delete", self::DELETE])->andWhere(['applyperson'=>$params['staff_id']])->andWhere(['crm_customer_apply.cust_id'=>$params['id']])->andWhere(['in', 'crm_customer_apply.company_id', BsCompany::getIdsArr($params['companyId'])]);
//        }
        $query = CrmCustomerApplyShow::find()->where(["!=", "is_delete", self::DELETE])->andWhere(['crm_customer_apply.cust_id'=>$params['id']]);
            if(isset($params["staff_id"])){
                $query->andWhere(['applyperson'=>$params['staff_id']])->andWhere(['in', 'crm_customer_apply.company_id', BsCompany::getIdsArr($params['companyId'])]);
            }
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            if(isset($params['export'])){
                $pageSize =false;
            }else{
                $pageSize =5;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        //默認排序
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("capply_id desc");
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $this->load($params);

        $trans = new Trans();//繁简转换

        $query->joinWith('applyStaff');
        $query->joinWith('custCustomer');
        $query->joinWith('personinch');
        $query->joinWith("manager");

        return $dataProvider;
    }
}
