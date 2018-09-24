<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/30
 * Time: 上午 09:12
 */

namespace app\modules\warehouse\models\search;

use app\modules\warehouse\models\BsVeh;
use app\modules\warehouse\models\show\BsVehShow;
use yii\data\ActiveDataProvider;

class BsVehSearch extends BsVeh
{
    public $log_cmp_name;
    const STATUS_DELETE = 1;
    public function rules()
    {
        return [
            [['log_cmp_name','log_code','veh_type', 'veh_number', 'veh_brand', 'person_charge', 'person_phone', 'veh_contacts', 'contacts_phone', 'veh_color', 'OPPER','OPP_DATE','veh_ip'], 'safe']
        ];
    }
    public function search($post)
    {
        $data = BsVehShow::find()->where(['veh_status'=>0])->orderBy('OPP_DATE DESC');

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
//        if (!$this->validate()) {
//            return $dataProvider;
//        }

        $this->load($post);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $data->joinWith('logCode');
        $data->andFilterWhere([        //根据字段搜索
            'bs_log_cmp.log_cmp_name' => $this->log_cmp_name,
        ]);
        //根据字段模糊查询
        $data->andFilterWhere(['like', 'veh_number', $this->veh_number]);
        $data->andFilterWhere(['like', 'veh_brand', $this->veh_brand]);
        $data->andFilterWhere(['like', 'person_charge', $this->person_charge]);

        return $dataProvider;

    }

    //根据id查询数据
    public static function getBsVehById($id)
    {
        $data = BsVehShow::find()->andWhere(['veh_id'=>$id])->one();
        return $data;
    }


}