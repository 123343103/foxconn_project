<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/20
 * Time: 下午 03:08
 */

namespace app\modules\system\models\search;


use app\classes\Trans;
use app\modules\system\models\BsRole;
use yii\data\ActiveDataProvider;

class BsRoleSearch extends BsRole
{
    public function rules()
    {
        return [
            [['role_name'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = BsRole::find();
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $tran = new Trans();

        if (isset($params['BsRole']['role_name'])) {
            $query->orFilterWhere(['like', 'role_name', $tran->t2c($params['BsRole']['role_name'])]);
            $query->orFilterWhere(['like', 'role_no', $tran->t2c($params['BsRole']['role_name'])]);
        }
        if (isset($params['BsRole']['role_state'])) {
            $query->andFilterWhere(['role_state' => $params['BsRole']['role_state']]);
        }

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'role_name' => '用户角色名称',
        ];
    }

}