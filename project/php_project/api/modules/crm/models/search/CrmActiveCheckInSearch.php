<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\search;
use app\modules\crm\models\CrmActiveCheckIn;
use app\modules\crm\models\show\CrmActiveCheckInShow;
use yii\data\ActiveDataProvider;
use yii\db\Query;
//活动签到搜索模型
class CrmActiveCheckInSearch extends CrmActiveCheckIn
{
    public function searchCheckInInfo($params)
    {
        $query=CrmActiveCheckInShow::find()->where(['acth_id'=>$params['applyId'],'actcin_status'=>self::DEFAULT_STATUS]);
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