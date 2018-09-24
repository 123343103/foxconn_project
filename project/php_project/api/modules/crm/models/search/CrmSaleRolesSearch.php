<?php

namespace app\modules\crm\models\search;

use app\classes\Trans;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\common\tools\SimpleTradition;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmMchpdtype;
use app\modules\crm\models\show\CrmMemberShow;
use app\modules\crm\models\show\CrmReturnVisitShow;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmSaleRoles;
use app\modules\crm\models\show\CrmSaleRolesShow;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CustomerExportShow;
use app\modules\common\models\BsCompany;

/**
 * 销售角色查询
 */
class CrmSaleRolesSearch extends CrmSaleRoles
{

    // 规则
    public function rules () {
        return [
            [['other_amount', 'sarole_qty'], 'number'],
            [['create_by', 'update_by'], 'integer'],
            [['cdate', 'udate'], 'safe'],
            [['sarole_code', 'sarole_sname', 'sarole_type', 'sarole_status'], 'string', 'max' => 20],
            [['isdeduct_salary'], 'string', 'max' => 4],
            [['sarole_desription', 'sarole_remark', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'string', 'max' => 120],
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
     * 查询角色
     */
    public function search($params)
    {
        $trans = new Trans();
        $query = CrmSaleRolesShow::find()->where(['!=','sarole_status',CrmSaleRoles::STATUS_DEL]);

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
            ],
        ]);
        $this->load($params);
        if(empty($this->sarole_status)){
            $query->andFilterWhere(['=', "sarole_status", 20]);
        }
        if (!empty($params['CrmSaleRolesSearch']['sarole_status'])) {
            if ($params['CrmSaleRolesSearch']['sarole_status'] == 30) {
                $csarea_status = "";
            }
            if ($params['CrmSaleRolesSearch']['sarole_status'] == 20) {
                $csarea_status = 20;
            }
            if ($params['CrmSaleRolesSearch']['sarole_status'] == 10) {
                $csarea_status = 10;
            }
        }else{
            $csarea_status="";
        }
        $query->andFilterWhere(['or',['like', "sarole_sname", $trans->t2c(trim($this->sarole_sname))],['like', "sarole_code", $trans->c2t(trim($this->sarole_sname))]])
            ->andFilterWhere(['=', "sarole_status", $csarea_status])
            ->andFilterWhere(['=', "sarole_type", $this->sarole_type]);
        return $dataProvider;
    }
}
