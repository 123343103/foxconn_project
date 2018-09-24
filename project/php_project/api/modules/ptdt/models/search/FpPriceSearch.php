<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/12
 * Time: 9:46
 */
namespace app\modules\ptdt\models\search;

use app\classes\Trans;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\BsBrand;
use app\modules\ptdt\models\BsCategory;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\ptdt\models\FpPrice;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;


class FpPriceSearch extends FpPrice
{

    public $category;

    public function rules()
    {
        [['category'], 'safe'];
        return parent::rules(); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * 新商品列表
     */
    public function search($params)
    {
        $trans = new Trans();
        $query = (new Query())->select([
            'fpprice.pdt_name',
            'fpprice.tp_spec',
            'IF(brand.brand_id,brand.brand_name_cn,fpprice.brand) brand',
            'fpprice.unit',
            'fpprice.part_no',
            'fpprice.category_id',
            'concat_ws("->",c_3.catg_name,c_2.catg_name,c_1.catg_name) category_name',
            'product.unit pdt_unit',
            'product.pdt_status',
            'partno.part_status',
            'partno.check_status'
        ])->from(FpPrice::tableName() . ' fpprice')
            ->leftJoin(BsPartno::tableName() . ' partno', 'partno.part_no=fpprice.part_no')
            ->leftJoin(BsProduct::tableName() . ' product', 'product.pdt_name = fpprice.pdt_name')
            ->leftJoin(BsBrand::tableName().' brand','brand.brand_id=product.brand_id')
            ->leftJoin(BsCategory::tableName() . ' c_1', 'c_1.catg_id=fpprice.category_id')
            ->leftJoin(BsCategory::tableName() . ' c_2', 'c_2.catg_id=c_1.p_catg_id')
            ->leftJoin(BsCategory::tableName() . ' c_3', 'c_3.catg_id=c_2.p_catg_id')
            ->orderBy('fpprice.pdt_name desc')
            ->distinct()
//            ->orderBy('BRAND DESC')
            ->where(['=', 'fpprice.status', FpPrice::STATUS_DEFAULT]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }



        if(!empty($params['FpPriceSearch']['status'])){
            if($params['FpPriceSearch']['status']==10){
                $query->andWhere("part_status>1");
            }else{
                $query->andWhere("part_status is null or part_status=1");
            }
        }

        $category="";
        empty($params['FpPriceSearch']['levelOne']) || $category=$params['FpPriceSearch']['levelOne'];
        empty($params['FpPriceSearch']['levelTwo']) || $category=$params['FpPriceSearch']['levelTwo'];
        empty($params['FpPriceSearch']['levelThree']) || $category=$params['FpPriceSearch']['levelThree'];
        if ($category) {
            $query->andFilterWhere([
                "or",
                ['c_1.catg_id'=>$category],
                ['c_2.catg_id'=>$category],
                ['c_3.catg_id'=>$category]
            ]);
        }
        isset($params['FpPriceSearch']['pdt_name']) && $query->andFilterWhere(['or', ['like', 'fpprice.pdt_name', $trans->c2t(trim($params['FpPriceSearch']['pdt_name']))], ['like', 'fpprice.pdt_name', $trans->t2c(trim($params['FpPriceSearch']['pdt_name']))]]);

        isset($params['FpPriceSearch']['part_no']) && $query->andFilterWhere(['or', ['like', 'fpprice.part_no', $trans->c2t(trim($params['FpPriceSearch']['part_no']))], ['like', 'fpprice.part_no', $trans->t2c(trim($params['FpPriceSearch']['part_no']))]]);

        $models=$dataProvider->getModels();
        $models=array_map(function($model){
            if(!empty($model["pdt_unit"])){
                $pubModel=BsPubdata::findOne($model["pdt_unit"]);
                if($pubModel){
                    $model["unit"]=$pubModel->bsp_svalue;
                }
                unset($model["pdt_unit"]);
            }
            return $model;
        },$models);
        $total=$dataProvider->totalCount;
        return [
            "rows"=>$models,
            "total"=>$total
        ];
    }
}