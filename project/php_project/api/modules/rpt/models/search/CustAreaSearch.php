<?php
namespace app\modules\rpt\models\search;
use app\modules\common\models\BsDistrict;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\ptdt\models\PdRequirement;
use app\modules\rpt\models\show\PrdRequireShow;
use app\modules\rpt\models\show\TemplateShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * 商品需求报表
 */
class CustAreaSearch extends ActiveRecord
{

    /**
     * 场景
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 报表内置模板列表
     */
    public static function search($params)
    {
        $query=CrmCustomerInfo::find()->select([
            BsDistrict::tableName().'.district_name name',
            'count(*) `客户数量`'
        ])
            ->leftJoin(BsDistrict::tableName(),
                CrmCustomerInfo::tableName().".cust_area=".BsDistrict::tableName().".district_id")
            ->groupBy(CrmCustomerInfo::tableName().".cust_area")
            ->where(CrmCustomerInfo::tableName().".cust_area is not null")
            ->orderBy("`客户数量` desc")
            ->limit(20);

        if(isset($params["cust_ismember"])){
            $query->andFilterWhere([CrmCustomerInfo::tableName().".cust_ismember"=>$params["cust_ismember"]]);
        }
        return $query->asArray()->all();
    }
}
