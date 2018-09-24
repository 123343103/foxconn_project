<?php
namespace app\modules\ptdt\models\search;

use app\modules\ptdt\models\BsCategory;
use yii;
use yii\data\SqlDataProvider;

class BsCategorySearch extends BsCategory
{
    public $catg_no;

    public function rules()
    {
        return [
            [['catg_no'], 'safe'],
            [['catg_name'], 'safe'],
            [['catg_level'], 'safe'],
            [['isvalid'], 'safe'],
        ];
    }

    public function search($params)
    {
        //获取一阶数据
        $sql = 'SELECT IFNULL(a.catg_attr_id,0)catg_attr_id,IFNULL(r.r_ctg_id,0)r_ctg_id,c.* FROM pdt.bs_category c LEFT JOIN 
pdt.bs_catg_attr a on c.catg_id=a.catg_id LEFT JOIN pdt.r_catg r on c.catg_id=r.catg_id where 1=1 ';
        if (!empty($params['BsCategorySearch']['catg_id'])) {
            $sql = $sql . ' and c.catg_id=' . $params['BsCategorySearch']['catg_id'];
        }
        $dataProvider = new yii\data\SqlDataProvider([
            'sql' => $sql,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        $arry = $dataProvider->getModels();
        //获取二三阶的数据
        $arryChil = $this->GetChilder($params['BsCategorySearch']['catg_id']);
        $arry = array_merge($arry, $arryChil);
        return $arry;
    }


    public function GetChilder($p_cagt_id, $arryChil = "")
    {
        if ($arryChil == "") {
            $arryChil = array();
        }
        $childer = BsCategory::find()->andWhere(["p_catg_id" => $p_cagt_id])->all();
        foreach ($childer as $key => $val) {
            $sql = 'SELECT IFNULL(a.catg_attr_id,0)catg_attr_id,IFNULL(r.r_ctg_id,0)r_ctg_id,c.* FROM pdt.bs_category c LEFT JOIN 
pdt.bs_catg_attr a on c.catg_id=a.catg_id LEFT JOIN pdt.r_catg r on c.catg_id=r.catg_id where 1=1 ';
            $sql = $sql . ' and c.catg_id=' . $val['catg_id'] . ' GROUP BY  c.catg_id';
            $dataProvider = new SqlDataProvider([
                'sql' => $sql,
                'pagination' => [
                    'pageSize' => 10
                ]
            ]);
            $arry = $dataProvider->getModels();
//            dumpE($dataProvider);
            $arryChil = array_merge($arryChil, $arry);
            $count = BsCategory::find()->where(['p_catg_id' => $val['catg_id']])->count();
            if ($count > 0 && $val['catg_level'] != 3) {
                $arryChil = $this->GetChilder($val['catg_id'], $arryChil);

            } else {

            }

        }
        return $arryChil;

    }
}

