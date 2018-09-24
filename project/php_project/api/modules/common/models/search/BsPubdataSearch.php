<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\common\models\search;
use app\modules\common\models\BsPubdata;
use yii\data\ActiveDataProvider;
//公共数据搜索模型
class BsPubdataSearch extends BsPubdata
{
    //搜索公共数据类型
    public function searchType($params)
    {
        $query=self::find()->groupBy('bsp_stype')->orderBy(['bsp_id'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ],
        ]);
        if(isset($params['searchKeyword'])){
            $query->andFilterWhere(['like','bsp_sname',$params['searchKeyword']])
                ->orFilterWhere(['like','bsp_stype',$params['searchKeyword']]);
        }
        return $dataProvider;
    }

    //搜索公共数据名称
    public function searchName($params)
    {
        $query=self::find()->where(['bsp_status'=>[self::STATUS_DEFAULT,self::STATUS_DISABLE],'bsp_stype'=>$params['val']])->orderBy(['bsp_id'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ],
        ]);
        return $dataProvider;
    }
}