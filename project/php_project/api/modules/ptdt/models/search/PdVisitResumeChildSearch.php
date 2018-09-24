<?php
namespace app\modules\ptdt\models\search;
use app\modules\ptdt\models\PdVisitResumeChild;
use app\modules\ptdt\models\show\PdVisitResumeChildShow;
use yii\data\ActiveDataProvider;
use yii\db\Query;
//拜访履历子表搜索模型
class PdVisitResumeChildSearch extends PdVisitResumeChild
{
    //履历子表搜索
    public function searchResumeChild($params)
    {
        $query=(new Query())->select(['*','IFNULL(update_at,create_at) AS sort_at'])->from(PdVisitResumeChild::tableName())->where(['and',['vih_id'=>$params['mainId']],['!=','vil_status',self::STATUS_DELETE]])->orderBy(['sort_at'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows'],
            ],
        ]);
        return $dataProvider;
    }

    //查看所有拜访履历
    public function searchAllResume($mainId)
    {
        $allResume=PdVisitResumeChildShow::find()->select(['*','IFNULL(update_at,create_at) AS sort_at'])->where(['and',['vih_id'=>$mainId],['!=','vil_status',self::STATUS_DELETE]])->orderBy(['sort_at'=>SORT_DESC])->limit(5)->all();
        return $allResume;
    }
}
