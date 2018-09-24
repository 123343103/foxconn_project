<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_product".
 *
 * @property string $l_pdt_pkid
 * @property string $pdt_pkid
 * @property string $category_id
 * @property string $pdt_name
 * @property string $brand_id
 * @property string $unit
 * @property string $pdt_title
 * @property string $pdt_keyword
 * @property string $pdt_label
 * @property string $pdt_attribute
 * @property string $pdt_form
 * @property integer $yn_show
 * @property integer $is_valid
 * @property string $crt_date
 * @property string $crter
 * @property integer $yn
 */
class LProduct extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_product';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pdt_pkid', 'category_id', 'pdt_name', 'brand_id', 'unit', 'pdt_attribute', 'pdt_form'], 'required'],
            [['pdt_pkid', 'category_id', 'brand_id', 'unit', 'pdt_attribute', 'pdt_form', 'yn_show', 'is_valid', 'crter', 'yn'], 'integer'],
            [['crt_date'], 'safe'],
            [['pdt_name', 'pdt_label'], 'string', 'max' => 100],
            [['pdt_title', 'pdt_keyword'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_pdt_pkid' => 'L Pdt Pkid',
            'pdt_pkid' => 'Pdt Pkid',
            'category_id' => 'Category ID',
            'pdt_name' => 'Pdt Name',
            'brand_id' => 'Brand ID',
            'unit' => 'Unit',
            'pdt_title' => 'Pdt Title',
            'pdt_keyword' => 'Pdt Keyword',
            'pdt_label' => 'Pdt Label',
            'pdt_attribute' => 'Pdt Attribute',
            'pdt_form' => 'Pdt Form',
            'yn_show' => 'Yn Show',
            'is_valid' => 'Is Valid',
            'crt_date' => 'Crt Date',
            'crter' => 'Crter',
            'yn' => 'Yn',
        ];
    }


    public static function getProductInfo($id){
        $result=self::getDb()->createCommand("select
            bs_product.pdt_PKID,
            bs_product.catg_id,
            bs_product.pdt_no,
            bs_product.pdt_name,
            bs_product.unit,
            bs_product.pdt_keyword,
            bs_product.pdt_label,
            bs_product.pdt_title,
            bs_product.brand_id,            
            bs_brand.BRAND_NAME_CN pdt_brand_name,
            bs_product.pdt_attribute,
            bs_product.pdt_form,
            bs_details.details,
            bs_images.fl_new pdt_img,
            if(cat_3.catg_no='EQ',1,0) isDevice,
            concat_ws(
            '->',
            cat_3.catg_name,
            cat_2.catg_name,
            cat_1.catg_name
        ) cat_three_level from pdt.bs_product
        left join pdt.bs_images on bs_product.pdt_PKID=bs_images.pdt_PKID and bs_images.img_type=1
        left join pdt.bs_brand on bs_product.brand_id = bs_brand.BRAND_ID
        left join pdt.bs_category cat_1 on cat_1.catg_id=bs_product.catg_id
        left join pdt.bs_category cat_2 on cat_2.catg_id=cat_1.p_catg_id
        left join pdt.bs_category cat_3 on cat_3.catg_id=cat_2.p_catg_id
        left join pdt.bs_details on bs_details.pdt_PKID=bs_product.pdt_PKID and bs_details.prt_pkid is null 
          where bs_product.pdt_PKID=:id limit 1",[":id"=>$id])->queryOne();
        $result["pdt_img"]=BsImages::find()->select(['fl_new'])->where(["pdt_PKID"=>$id,"img_type"=>0])->orderBy("orderby asc")->asArray()->column();
        $result["upload3D"]=BsImages::find()->select("fl_new")->where(["pdt_PKID"=>$id,"img_type"=>1])->scalar();
        $result["related_product"]=self::getDb()->createCommand("select p2.pdt_PKID pdt_pkid,p2.pdt_no,p2.pdt_name,c2.catg_name,IF(r_pdt_pkid,1,0) selected from pdt.bs_product p1
  left join pdt.bs_category c1 on p1.catg_id = c1.catg_id
  left join pdt.r_catg on r_catg.catg_id=c1.catg_id
  left join pdt.bs_product p2 on p2.catg_id=r_catg.catg_r_id
  left join r_pdt_pdt on r_pdt_pdt.pdt_pkid=p1.pdt_PKID and r_pdt_pdt.r_pdt_pkid=p2.pdt_PKID
  join pdt.bs_category c2 on c2.catg_id=p2.catg_id
where p1.pdt_PKID=:id",[":id"=>$id])->queryAll();
        return $result;
    }
}
