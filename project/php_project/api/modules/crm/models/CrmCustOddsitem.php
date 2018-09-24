<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\PdProductType;
use Yii;

/**
 * This is the model class for table "crm_cust_oddsitem".
 *
 * @property integer $odds_id
 * @property integer $cust_id
 * @property integer $category_id
 * @property integer $pdt_id
 * @property string $odds_sname
 * @property string $odds_model
 * @property string $brand
 * @property string $status
 */
class CrmCustOddsitem extends Common
{
    const STATUS_DELETE = '0';
    const STATUS_DEFAULT = '10';

    public static function tableName()
    {
        return 'crm_cust_oddsitem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['odds_id'], 'required'],
            [['odds_id', 'cust_id', 'pdt_id'], 'integer'],
            [['odds_sname', 'odds_model', 'brand'], 'string', 'max' => 60],
            [['status'], 'string', 'max' => 2],
            [['category_id'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 200],
            ['status', 'default', 'value' => self::STATUS_DEFAULT ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'odds_id' => 'id',
            'cust_id' => '客户id',
            'category_id' => '商品分类ID',
            'pdt_id' => '商品表',
            'odds_sname' => '商品名称',
            'odds_model' => '规格/类型',
            'brand' => '品牌',
            'status' => '状态',
        ];
    }
    public function getProductType(){
        return $this->hasOne(BsCategory::className(),['catg_id'=>'category_id']);
    }
}
