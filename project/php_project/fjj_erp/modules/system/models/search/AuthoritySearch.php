<?php
/**
 * User: F3859386
 * Date: 2017/3/3
 * Time: 15:07
 */
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\AuthItem;
use yii\data\ActiveDataProvider;
class AuthoritySearch extends AuthItem
{


    public function rules()
    {
        return [
            [['title'],'safe']
        ];
    }
    public function search($params)
    {
        $query = AuthItem::find()->asArray();

        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $trans = new Trans();
        $query->andFilterWhere([
            'or',
            ['like','title',$trans->c2t(trim($this->title))],
            ['like','title',$trans->t2c(trim($this->title))],
            ['like','title',trim($this->title)],
            ['like','code',$trans->c2t(trim($this->title))],
            ['like','code',$trans->t2c(trim($this->title))],
            ['like','code',trim($this->title)],
        ]);
//        $query->orFilterWhere([
//            'or',
//            ['like','code',$trans->c2t(trim($this->title))],
//            ['like','code',$trans->t2c(trim($this->title))],
//            ['like','code',trim($this->title)],
//        ]);
        $query->andFilterWhere(['=','type',1]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'title' => '用户角色名称',
        ];
    }
}