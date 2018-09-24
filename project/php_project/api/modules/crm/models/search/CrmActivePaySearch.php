<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\search;
use app\modules\crm\models\CrmActivePay;
use app\modules\crm\models\show\CrmActivePayShow;
use yii\data\ActiveDataProvider;
//活动缴费搜索模型
class CrmActivePaySearch extends CrmActivePay
{
    public function searchPayInfo($params)
    {
        $query=CrmActivePayShow::find()->where(['acth_id'=>$params['applyId'],'actpaym_status'=>self::DEFAULT_STATUS]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ],
            'sort'=>[
                'defaultOrder'=>[
                    'create_at'=>SORT_DESC
                ]
            ]
        ]);
        return $dataProvider;
    }
}