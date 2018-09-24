<?php
/**
 * User: F1676624
 * Date: 2016/03/10
 */
namespace app\modules\app\models\search;

use app\modules\common\models\BsCompany;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\app\models\show\VisitInfoShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;
use app\classes\Trans;

/**
 * 拜访记录搜索模型
 */
class VisitInfoSearch extends CrmVisitRecord
{
    //搜索属性

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['sih_code', 'customerName', 'customerType', 'customerManager', 'contactPerson', 'cust_sname', 'cust_contacts', 'cust_tel2', 'cust_businesstype', 'member_source', 'member_reqflag', 'cust_ismember', 'member_type'], 'safe'],
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
     * 搜索拜访记录
     */
    public function searchChild($params)
    {
        $query = VisitInfoShow::find()->where(['!=', 'sih_status', self::STATUS_DELETE])
            ->andWhere(['in', 'crm_visit_info.company_id', BsCompany::getIdsArr($params['companyId'])])->groupBy(['sih_id', 'sih_code']);
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
        //默认排序
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $staff_code = $params['staffCode'];
        $content = $params['content'];
        //UTF8内简繁转换
        $go = new Trans;
        $content = $go->c2t($content);
        $ftcontent = $go->t2c($content);


        $query->joinWith('visitInfoChild');
        $query->joinWith('crmCustomer');
        $query->joinWith('customerType');
        $query->andFilterWhere(["crm_visit_info_child.sil_staff_code" => $staff_code]);
        $query->andFilterWhere(['or', ['like', 'sih_code', $content],
                ['bs_pubdata.bsp_id' => $content,],
                ['bs_pubdata.bsp_id' => $ftcontent,],
                ['like', "crm_bs_customer_info.cust_sname", $content],
                ['like', "crm_bs_customer_info.cust_sname", $ftcontent],
                ['like', "crm_bs_customer_info.cust_contacts", $ftcontent],
                ['like', "crm_bs_customer_info.cust_contacts", $content]]
        );
        return $dataProvider;
    }

    /**
     * 搜索拜访记录
     */
    public function searchTemp($params)
    {
        $query = VisitInfoShow::find()->where(['!=', 'sih_status', self::STATUS_DELETE])
            ->andWhere(['in', 'crm_visit_info.company_id', BsCompany::getIdsArr($params['companyId'])])->groupBy(['sih_id', 'sih_code']);
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
        //默认排序
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("create_at desc");
        }
        $staff_code = $params['staffCode'];
        $content = $params['content'];
        //UTF8内简繁转换
        $go = new Trans;
        $content = $go->c2t($content);
        $ftcontent = $go->t2c($content);

        $query->joinWith('visitInfoChild');
        $query->andFilterWhere(["crm_visit_info_child.sil_staff_code" => $staff_code]);
        $query->andFilterWhere(['crm_visit_info_child.type' => 30]);//临时记录

        $query->joinWith('crmCustomer');
        $query->joinWith('customerType');
        $query->andFilterWhere(['or', ['like', 'sih_code', $content],
                ['bs_pubdata.bsp_id' => $content,],
                ['bs_pubdata.bsp_id' => $ftcontent,],
                ['like', "crm_bs_customer_info.cust_sname", $content],
                ['like', "crm_bs_customer_info.cust_sname", $ftcontent],
                ['like', "crm_bs_customer_info.cust_contacts", $ftcontent],
                ['like', "crm_bs_customer_info.cust_contacts", $content]]
        );
        return $dataProvider;
    }
}