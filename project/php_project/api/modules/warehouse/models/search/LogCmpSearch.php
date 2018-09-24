<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/10
 * Time: 上午 10:06
 */
namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\BsLogCmp;
use app\modules\warehouse\models\show\LogCmpShow;
use yii\data\ActiveDataProvider;

class LogCmpSearch extends BsLogCmp
{
    const STATUS_DELETE = 1;

    public function search($post)
    {
        $data = $data = LogCmpShow::find()->andWhere(['log_status'=>0]);

        if (isset($post['rows'])) {
            $pageSize = $post['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $data,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        if (!\Yii::$app->request->get('sort')) {//默认排序
            $data->orderBy("OPP_DATE desc");
        }
//        if (!$this->validate()) {
//            return $dataProvider;
//        }

        $this->load($post);

        if (!$this->validate()) {
            return $dataProvider;
        }
//        $query->joinWith('firmType');

        $data->andFilterWhere([        //根据字段搜索
            'log_cmp_name' => $this->log_cmp_name,
            'log_type' => $this->log_type,
        ]);
        //根据字段模糊查询
        $data->andFilterWhere(['like', 'log_code', $this->log_code]);

        return $dataProvider;

    }

    //根据id获取物流公司数据
    public function getById($id)
    {
        $data = $data = LogCmpShow::find()->andWhere(["log_cmp_id"=>$id,'log_status'=>0])->one();
        return $data;
    }
}