<?php
namespace app\modules\rpt\models\search;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
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
class CustReqSearch extends ActiveRecord
{

    /**
     * 场景
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public static function search($params)
    {
        $query=CrmCustomerInfo::find()->select([
            BsPubdata::tableName().'.bsp_svalue name',
            'count(*) `客户数量`'
        ])
            ->leftJoin(BsPubdata::tableName(),
                CrmCustomerInfo::tableName().".member_reqflag=".BsPubdata::tableName().".bsp_id")
            ->groupBy(CrmCustomerInfo::tableName().".member_reqflag")
            ->where(CrmCustomerInfo::tableName().".member_reqflag is not null")
            ->orderBy("`客户数量` desc");
        return $query->asArray()->all();
    }
}
