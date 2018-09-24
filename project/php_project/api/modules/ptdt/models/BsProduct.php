<?php

namespace app\modules\ptdt\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsCategoryUnit;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\BsBrand;
use app\modules\system\models\Verifyrecord;
use app\modules\system\models\VerifyrecordChild;
use app\modules\warehouse\models\BsWh;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\data\SqlDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "bs_product".
 *
 * @property string $pdt_PKID
 * @property string $pdt_no
 * @property string $catg_id
 * @property string $pdt_name
 * @property string $brand_id
 * @property string $unit
 * @property string $pdt_attribute
 * @property string $pdt_form
 * @property integer $pdt_status
 * @property string $pdt_keyword
 * @property string $pdt_label
 * @property string $pdt_title
 * @property string $crt_date
 * @property string $crter
 * @property string $crt_ip
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property BsPartno[] $bsPartnos
 * @property BsCategory $catg
 * @property BsBrand $brand
 */
class BsProduct extends Common
{

    const STATUS_REJECT=-1;//驳回
    const STATUS_CHECKING=0;//审核中
    const STATUS_SELLING=1;//销售中
    const STATUS_DOWNSHELF=2;//已下架
    const STATUS_UPSHELFING=3;//未上架

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdt.bs_product';
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
            [['pdt_name', 'brand_id', 'unit', 'pdt_attribute', 'pdt_form'], 'required'],
            [['catg_id', 'brand_id', 'unit', 'pdt_attribute', 'pdt_form', 'pdt_status', 'crter', 'opper'], 'integer'],
            [['crt_date', 'opp_date'], 'safe'],
            [['pdt_no'], 'string', 'max' => 30],
            [['pdt_name', 'pdt_label'], 'string', 'max' => 100],
            [['pdt_keyword', 'pdt_title'], 'string', 'max' => 200],
            [['crt_ip', 'opp_ip'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdt_pkid' => 'Pdt  Pkid',
            'pdt_no' => 'Pdt No',
            'catg_id' => 'Catg ID',
            'pdt_name' => 'Pdt Name',
            'brand_id' => 'Brand ID',
            'unit' => 'Unit',
            'pdt_attribute' => 'Pdt Attribute',
            'pdt_form' => 'Pdt Form',
            'pdt_status' => 'Pdt Status',
            'pdt_keyword' => 'Pdt Keyword',
            'pdt_label' => 'Pdt Label',
            'pdt_title' => 'Pdt Title',
            'crt_date' => 'Crt Date',
            'crter' => 'Crter',
            'crt_ip' => 'Crt Ip',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    public function getPartno(){
        return $this->hasMany(BsPartno::className(),["pdt_pkid"=>"pdt_pkid"]);
    }

    public function getProductType()
    {
        return $this->hasOne(BsCategory::className(), ['catg_id' => "catg_id"]);
    }
    public function getCompany(){
        return $this->hasOne(BsCompany::className(),['company_id'=>'company_id']);
    }

    public function getBrand(){
        return $this->hasOne(BsBrand::className(),['brand_id'=>'brand_id']);
    }
    //商品计量单位
    public function getUnite(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'unit']);
    }
    //商品属性
    public function getAttr(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdt_attribute']);
    }
    //商品形态
    public function getForm(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdt_form']);
    }
    public function getCategory(){
        return $this->hasOne(BsCategory::className(),["catg_id"=>"catg_id"]);
    }

    public function getSupplier(){
        return $this->hasOne(PdSupplier::className(),["supplier_code"=>"supplier_code"]);
    }

    public function getImage(){
        return $this->hasMany(BsImages::className(),["pdt_pkid"=>"pdt_pkid"]);
    }

    public static function search($params=""){
        if(isset($params["status"])){
            switch($params["status"]){
                case "selling":
                    $query=BsPartno::find()->select([
                        "a.pdt_pkid",
                        "a.prt_pkid",
                        "a.part_no",
                        "a.part_status",
                        "a.check_status",
                        "a.opper",
                        "convert(a.publish_date,date) upshelf_date",
                        "a.opp_ip",
                        "a.crter",
                        "convert(a.crt_date,date) crt_date",
                        "a.crt_ip",
                        "bs_product.pdt_name",
                        "bs_product.pdt_no"
                    ])->alias("a")->joinWith(["product"=>function($query){
                            return $query->with(["brand","category.parent.parent","attr","unite","form","image"]);
                        }])
                        ->asArray();
                    $query->andFilterWhere([
                        "or",
                        ["a.part_status"=>2,"a.check_status"=>3],//上架完成
                        ["a.part_status"=>3,"a.check_status"=>2],//下架驳回
                        ["a.part_status"=>4]//商品修改
                    ]);
                    $query->orderBy("bs_product.opp_date desc,bs_product.pdt_pkid,a.opp_date desc");
                    break;
                case "notupshelf":
                    $query=BsPartno::find()->select([
                        "a.pdt_pkid",
                        "a.prt_pkid",
                        "a.part_no",
                        "a.part_status",
                        "a.check_status",
                        "a.opper",
                        "a.opp_ip",
                        "a.crter",
                        "a.crt_ip",
                        "convert(a.crt_date,date) create_date",
                        "bs_product.pdt_name",
                        "bs_product.pdt_no"
                    ])->alias("a")->joinWith(["product"=>function($query){
                        return $query->with(["brand","category.parent.parent","attr","unite","form","image"]);
                    }])
                        ->asArray();
                    $query->andFilterWhere([
                        "or",
                        ["a.part_status"=>1],//未上架
                        ["a.part_status"=>2,"a.check_status"=>2]//上架驳回
                    ]);
                    $query->orderBy("bs_product.opp_date desc,bs_product.pdt_pkid,a.opp_date desc");
                    break;
                case "checking":
                    $query=BsPartno::find()->select([
                        "a.pdt_pkid",
                        "a.prt_pkid",
                        "a.part_no",
                        "a.part_status",
                        "a.check_status",
                        "a.opper",
                        "convert(a.opp_date,date) check_date",
                        "a.opp_ip",
                        "a.crter",
                        "a.crt_ip",
                        "bs_product.pdt_name",
                        "bs_product.pdt_no",
                    ])->alias("a")->joinWith(["product"=>function($query){
                        return $query->with(["brand","category.parent.parent","attr","unite","form","image"]);
                    }])
                        ->asArray();
                    $query->andFilterWhere([
                        "and",
                        ["in","a.part_status",[2,3,5]],
                        ["a.check_status"=>1]
                    ]);
                    $query->orderBy("bs_product.opp_date desc,bs_product.pdt_pkid,a.opp_date desc");
                    break;
                case "downshelf":
                    $query=BsPartno::find()->select([
                        "a.pdt_pkid",
                        "a.prt_pkid",
                        "a.part_no",
                        "a.part_status",
                        "a.check_status",
                        "a.opper",
                        "a.rs_id",
                        "a.file_new",
                        "a.file_old",
                        "a.other_reason",
                        "convert(a.off_date,date) downshelf_date",
                        "convert(a.publish_date,date) upshelf_date",
                        "a.opp_ip",
                        "bs_product.pdt_name",
                        "bs_product.pdt_no"
                    ])->alias("a")->joinWith(["product"=>function($query){
                        return $query->with(["brand","category.parent.parent","attr","unite","form","image"]);
                    }])
                        ->asArray();
                    $query->andFilterWhere([
                        "or",
                        ["a.part_status"=>3,"a.check_status"=>3],//下架完成
                        ["a.part_status"=>5,"a.check_status"=>2]//重新上架驳回
                    ]);
                    $query->orderBy("bs_product.opp_date desc,bs_product.pdt_pkid,a.opp_date desc");
                    break;
            }
        }

        if(isset($params["pdt_name"])){
            $query->andFilterWhere(["like","pdt_name",$params["pdt_name"]]);
        }
        if(isset($params["part_no"])){
            $query->andFilterWhere(["part_no"=>$params["part_no"]]);
        }
        if(isset($params["catg_id"])){
            $query->leftJoin(BsCategory::tableName()." c_1","c_1.catg_id=bs_product.catg_id");
            $query->leftJoin(BsCategory::tableName()." c_2","c_2.catg_id=c_1.p_catg_id");
            $query->leftJoin(BsCategory::tableName()." c_3","c_3.catg_id=c_2.p_catg_id");
            $query->andFilterWhere([
                "or",
                ["c_1.catg_id"=>$params["catg_id"]],
                ["c_2.catg_id"=>$params["catg_id"]],
                ["c_3.catg_id"=>$params["catg_id"]]
            ]);
        }

        if(isset($params["export"])){
            $models=$query->all();
            $total=count($models);
        }else{
            $provider=new ActiveDataProvider([
                "query"=>$query,
                "pagination"=>[
                    "pageParam"=>"page",
                    "pageSizeParam"=>"rows"
                ]
            ]);
            $models=$provider->models;
            $total=$provider->totalCount;
        }

        $models=array_map(function($model) use($params){
            isset($model["product"]["image"][0]["fl_new"])&&$model["pdt_img"]=$model["product"]["image"][0]["fl_new"];
            isset($model["product"]["attr"]["bsp_svalue"])&&$model["pdt_attribute_name"]=$model["product"]["attr"]["bsp_svalue"];
            isset($model["product"]["unite"]["bsp_svalue"])&&$model["pdt_unit_name"]=$model["product"]["unite"]["bsp_svalue"];
            isset($model["product"]["form"]["bsp_svalue"])&&$model["pdt_form_name"]=$model["product"]["form"]["bsp_svalue"];
            isset($model["product"]["brand"]["brand_name_cn"])&&$model["pdt_brand_name"]=$model["product"]["brand"]["brand_name_cn"];
            isset($model["product"]["category"]["parent"]["parent"]["catg_name"])&&$model["cat_three_level"]=$model["product"]["category"]["parent"]["parent"]["catg_name"]."->".$model["product"]["category"]["parent"]["catg_name"]."->".$model["product"]["category"]["catg_name"];
            $LPrtModel=LPartno::find()->where(["part_no"=>$model["part_no"]])->orderBy("l_prt_pkid desc")->one();
            $model["l_prt_pkid"]=isset($LPrtModel->primaryKey)?$LPrtModel->primaryKey:"";
            if($params["status"]=="notupshelf"){
                $model["reject_reason"]=(String)\Yii::$app->db->createCommand("select vcoc_remark from system_verifyrecord a left join system_verifyrecord_child b on a.vco_id=b.vco_id and b.vcoc_status=40 where a.vco_busid=:busid and a.bus_code='pdtsel' order by b.vcoc_id desc limit 1",[":busid"=>$model["l_prt_pkid"]])->queryScalar();
                $model["reject_reason"] && $model["reject_reason"]=Html::encode($model["reject_reason"]);
            }
            if($params["status"]=="downshelf"){
                if($model["rs_id"]){
                    $model["downshelf_reason"]=OffReason::find()->select("rs_mark")->where(["rs_id"=>$model["rs_id"]])->scalar();
                }else{
                    $model["downshelf_reason"]=$model["other_reason"];
                }
                $model["downshelf_attachment"]=[
                    "file_new"=>$model['file_new'],
                    "file_old"=>$model['file_old']
                ];

            }
            $staff=HrStaff::findOne($model["product"]["opper"]);
            $model["upshelf_person"]=isset($staff)?$staff->staff_name:"";
            unset($model["product"]);
            return $model;
        },$models);
        return [
            "rows"=>$models,
            "total"=>$total
        ];
    }


    public static function getProductInfo($id){
        $result=self::getDb()->createCommand("select
            bs_product.pdt_pkid,
            bs_product.catg_id,
            bs_product.pdt_no,
            bs_product.pdt_name,
            bs_product.unit,
            bs_product.pdt_keyword,
            bs_product.pdt_label,
            bs_product.pdt_title,
            bs_product.brand_id,            
            bs_brand.brand_name_cn pdt_brand_name,
            bs_product.pdt_attribute,
            bs_product.pdt_form,
            bs_product.pdt_status,
            bs_details.details,
            bs_images.fl_new pdt_img,
            if(cat_3.catg_no='EQ',1,0) isDevice,
            concat_ws(
            '->',
            cat_3.catg_name,
            cat_2.catg_name,
            cat_1.catg_name
        ) cat_three_level from pdt.bs_product
        left join pdt.bs_images on bs_product.pdt_pkid=bs_images.pdt_pkid and bs_images.img_type=1
        left join pdt.bs_brand on bs_product.brand_id = bs_brand.brand_id
        left join pdt.bs_category cat_1 on cat_1.catg_id=bs_product.catg_id
        left join pdt.bs_category cat_2 on cat_2.catg_id=cat_1.p_catg_id
        left join pdt.bs_category cat_3 on cat_3.catg_id=cat_2.p_catg_id
        left join pdt.bs_details on bs_details.pdt_pkid=bs_product.pdt_pkid and bs_details.prt_pkid is null 
          where bs_product.pdt_pkid=:id limit 1",[":id"=>$id])->queryOne();
        $result["pdt_img"]=BsImages::find()->select(['fl_new'])->where(["pdt_PKID"=>$id,"img_type"=>0])->orderBy("orderby asc")->asArray()->column();
        $result["upload3D"]=BsImages::find()->select("fl_new")->where(["pdt_PKID"=>$id,"img_type"=>1])->scalar();
        $related_product=self::getDb()->createCommand("select distinct bs_product.pdt_pkid,bs_product.pdt_name,bs_product.pdt_no,bs_category.catg_name,IF(r_pdt_pdt.pdt_pkid,1,0) selected from bs_product
  join bs_partno on bs_partno.pdt_pkid=bs_product.pdt_pkid
  left join pdt.bs_category on bs_product.catg_id = bs_category.catg_id
  left join r_pdt_pdt on bs_product.pdt_pkid=r_pdt_pdt.r_pdt_pkid and r_pdt_pdt.pdt_pkid=:id
where bs_product.catg_id in(
  select catg_r_id from bs_product left join r_catg on bs_product.catg_id=r_catg.catg_id where pdt_pkid=:id
) and ((part_status=2 and check_status=3) or (part_status=3 and check_status=2) or (part_status=4))",[":id"=>$id])->queryAll();
        $result["related_product"]=$related_product;
        return $result;
    }

    public static function partnoSearch($params){
        $result=[];
        $query=BsPartno::find()->with(["product.category.parent.parent","price"=>function($query){
            return $query->select(["prt_pkid,truncate(minqty,2) minqty","IF(maxqty,truncate(maxqty,2),'') maxqty","price","currency"])->with(["currency"]);
        },"opper"])->asArray();
        if(!empty($params["id"])){
            $query->filterWhere(["pdt_pkid"=>$params["id"]]);
        }
        if(isset($params["status"])){
            switch($params["status"]){
                case "selling":
                    $query->andFilterWhere([
                        "or",
                        [BsPartno::tableName().".part_status"=>2,BsPartno::tableName().".check_status"=>3],//上架完成
                        [BsPartno::tableName().".part_status"=>3,BsPartno::tableName().".check_status"=>2],//下架驳回
                        [BsPartno::tableName().".part_status"=>4]//商品修改
                    ]);
                    break;
                case "notupshelf":
                    $query->andFilterWhere([
                        "or",
                        [BsPartno::tableName().".part_status"=>1],//未上架
                        [BsPartno::tableName().".part_status"=>2,BsPartno::tableName().".check_status"=>2],//上架驳回
                    ]);
                    break;
                case "checking":
                    $query->andFilterWhere([BsPartno::tableName().".check_status"=>1]);
                    break;
                case "downshelf":
                    $query->andFilterWhere([
                        "or",
                        [BsPartno::tableName().".part_status"=>3,BsPartno::tableName().".check_status"=>3],//下架完成
                        [BsPartno::tableName().".part_status"=>5,BsPartno::tableName().".check_status"=>2]//重新上架驳回
                    ]);
                    break;
            }
        }
        if(isset($params["rows"])){
            $provider=new ActiveDataProvider([
                "query"=>$query,
                "pagination"=>[
                    "pageParam"=>"page",
                    "pageSizeParam"=>"rows"
                ]
            ]);
            $result["rows"]=$provider->getModels();
            $result["total"]=$provider->totalCount;
            $result["rows"]=array_map(function($model){
                $model["pdt_no"]=$model["product"]["pdt_no"];
                $model["pdt_name"]=$model["product"]["pdt_name"];
                $cats=[];
                isset($model["product"]["category"]["parent"]["parent"]["catg_name"]) && $cats[]=$model["product"]["category"]["parent"]["parent"]["catg_name"];
                isset($model["product"]["category"]["parent"]["catg_name"]) && $cats[]=$model["product"]["category"]["parent"]["catg_name"];
                isset($model["product"]["category"]["catg_name"]) && $cats[]=$model["product"]["category"]["catg_name"];
                $model["cat_three_level"]=implode("->",$cats);
                $prices=array_map(function($price){
                    ((int)$price["price"]==-1 && $price["price"]="面议") || $price["price"]=$price["price"]."&nbsp;".$price["currency"]["bsp_svalue"];
                    return $price["minqty"]."~".$price["maxqty"]."&nbsp;&nbsp;&nbsp;".$price["price"]."<p>";
                },$model["price"]);
                $model["price"]=implode("",$prices);
                $model["upshelf_person"]=isset($model["opper"]["staff_name"])?$model["opper"]["staff_name"]:"/";
                $model["upshelf_date"]=$model["opp_date"];

                $LPrtModel=LPartno::find()->where(["part_no"=>$model["part_no"]])->orderBy("l_prt_pkid desc")->one();
                $model["l_prt_pkid"]=isset($LPrtModel->primaryKey)?$LPrtModel->primaryKey:"";
                if(isset($LPrtModel->primaryKey)){
                    $data=self::getDb()->createCommand("select IF(a.rs_id,c.rs_mark,a.other_reason) downshelf_reason,a.opp_date downshelf_date,d.file_old,d.file_new from pdt.off_apply a
  join (select * from pdt.off_apply_dt where l_pdt_pkid=:pkid order by off_app_id desc limit 1) b on a.off_app_id=b.off_app_id
  left join off_reason c on a.rs_id=c.rs_id left join off_file d on d.off_app_id=a.off_app_id;",[":pkid"=>$LPrtModel->primaryKey])->queryOne();
                    $model["downshelf_reason"]=$data["downshelf_reason"];
                    $model["downshelf_date"]=$data["downshelf_date"];
                    $model["downshelf_attachment"]=$data;
                }
                return $model;
            },$result["rows"]);
        }else{
            $result=$query->all();
            $result=array_map(function($model){
                $model["pdt_name"]=$model["product"]["pdt_name"];
                $prices=array_map(function($price){
                    return $price["minqty"]."~".$price["maxqty"]."&nbsp;&nbsp;&nbsp;".$price["price"].$price["currency"]["bsp_svalue"]."<p>";
                },$model["price"]);
                $model["price"]=implode("",$prices);
                $model["upshelf_person"]=isset($model["opper"]["staff_name"])?$model["opper"]["staff_name"]:"/";
                $model["upshelf_date"]=$model["opp_date"];
                return $model;
            },$result);
        }
        return $result;
    }



    public static function getPartnoInfo($id){
        $result=[];
        $result["partno"]=self::getDb()->createCommand("select bs_partno.*,bs_details.details,bs_details.details from pdt.bs_partno left join pdt.bs_details on bs_details.prt_pkid=bs_partno.prt_pkid  where bs_partno.prt_pkid=:id",[":id"=>$id])->queryOne();
        $LPrtModel=LPartno::find()->where(["part_no"=>$result["partno"]["part_no"]])->orderBy("l_prt_pkid desc")->one();
        $result["partno"]["l_prt_pkid"]=isset($LPrtModel->primaryKey)?$LPrtModel->primaryKey:"";
        $result["pas"]=self::getDb()->createCommand("select payment_terms,trading_terms,supplier_code,supplier_name_shot,delivery_address,unite,min_order,currency,limit_day,num_area,truncate(buy_price,5) buy_price,expiration_date from pdtprice_pas where part_no=:part_no",[":part_no"=>$result["partno"]["part_no"]])->queryAll();
        $result["price"]=BsPrice::find()->select(["prt_pkid","truncate(minqty,2) minqty","IF(maxqty,truncate(maxqty,2),'') maxqty","price","currency"])->with("currency")->where(["prt_pkid"=>$id])->asArray()->all();
        $result["price"]=array_map(function($model){
            (int)$model["price"]==-1 && $model["price"]=(int)$model["price"];
            $model["currency_id"]=isset($model["currency"]["bsp_id"])?$model["currency"]["bsp_id"]:"";
            $model["currency_name"]=isset($model["currency"]["bsp_svalue"])?$model["currency"]["bsp_svalue"]:"";;
            unset($model["currency"]);
            return $model;
        },$result["price"]);
        $result["partno"]["spp"]=self::getDb()->createCommand("select group_concat(distinct supplier_name) from pdtprice_pas where part_no=:partno and supplier_name!='' and  effective_date<NOW() and expiration_date>NOW()",[":partno"=>$result["partno"]["part_no"]])->queryScalar();
        $result["ship"]=self::getDb()->createCommand("select * from pdt.bs_ship where prt_pkid=:id",[":id"=>$id])->queryAll();
        $result["stock"]=self::getDb()->createCommand("select truncate(min_qty,2) min_qty,truncate(max_qty,2) max_qty,stock_time from pdt.bs_stock where prt_pkid=:id",[":id"=>$id])->queryAll();
        $result["deliv"]=self::getDb()->createCommand("select * from pdt.bs_deliv where prt_pkid=:id",[":id"=>$id])->queryAll();
        $result["pack"]=self::getDb()->createCommand("select prt_pkid,pck_type,pdt_length,pdt_width,pdt_height,pdt_weight,net_weight,pdt_mater,pdt_qty,plate_num from pdt.bs_pack where prt_pkid=:id order by pck_type asc",[":id"=>$id])->queryAll();
        $result["warr"]=self::getDb()->createCommand("select * from pdt.bs_warr where prt_pkid=:id",[":id"=>$id])->queryAll();
        $result["wh"]=BsWh::find()->with(["whAdms.hrStaff"])->where(["yn_deliv"=>1])->asArray()->all();
        $rprtWhArr=RPrtWh::find()->select("wh_id")->where(["prt_pkid"=>$id])->asArray()->column();
        $result["wh"]=array_map(function($row) use ($rprtWhArr){
            $row["staff_name"]=isset($row["whAdms"]["hrStaff"]["staff_name"])?$row["whAdms"]["hrStaff"]["staff_name"]:"";
            $row["staff_mobile"]=isset($row["whAdms"]["hrStaff"]["staff_mobile"])?$row["whAdms"]["hrStaff"]["staff_mobile"]:"";
            $row["wh_address"]=$row["wh_addr"];
            $row["selected"]=in_array($row["wh_id"],$rprtWhArr);
            unset($row["whAdms"]);
            return $row;
        },$result["wh"]);
        $attrs=self::getDb()->createCommand("select
  bs_catg_attr.catg_id,
  bs_catg_attr.isrequired,
  bs_catg_attr.catg_attr_id,
  bs_catg_attr.attr_name,
  bs_catg_attr.attr_type,
  r_attr_value.attr_val_id,
  r_attr_value.attr_value
from
  bs_catg_attr
  left join r_attr_value on r_attr_value.catg_attr_id = bs_catg_attr.catg_attr_id
  left join  bs_product on bs_catg_attr.catg_id=bs_product.catg_id
  left join bs_partno on bs_product.pdt_pkid = bs_partno.pdt_pkid
where bs_catg_attr.status=1 and bs_partno.prt_pkid=:id order by catg_attr_id",[":id"=>$id])->queryAll();
        $data=[];
        foreach ($attrs as $item){
            $data[$item["catg_attr_id"]]["name"]=$item["attr_name"];
            $data[$item["catg_attr_id"]]["type"]=$item["attr_type"];
            $data[$item["catg_attr_id"]]["isrequired"]=$item["isrequired"];
            if($item["attr_type"]==3){
                $data[$item["catg_attr_id"]]["val"]=(String)RPrtAttr::find()->select("attr_value")->where(["catg_attr_id"=>$item["catg_attr_id"],"prt_pkid"=>$id])->scalar();
            }else{
                $data[$item["catg_attr_id"]]["sel"]=RPrtAttr::find()->select("attr_val_id")->where(["catg_attr_id"=>$item["catg_attr_id"],"prt_pkid"=>$id])->column();
            }
            isset($data[$item["catg_attr_id"]]["items"])?:$data[$item["catg_attr_id"]]["items"]=[];
            if($item["attr_val_id"]){
                $data[$item["catg_attr_id"]]["items"][$item["attr_val_id"]]=$item["attr_value"];
            }
        }
        $result["attrs"]=$data;
        $result["machine"]=self::getDb()->createCommand("select * from pdt.bs_machine where prt_pkid=:id",[":id"=>$id])->queryOne();
        return $result;
    }

    public static function getOptions(){
        $result["pdt_attribute"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>"SPSX","bsp_status"=>10])->asArray()->indexBy("bsp_id")->orderBy("bsp_id asc")->column();
        $result["pdt_form"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>"SPXT","bsp_status"=>10])->asArray()->indexBy("bsp_id")->orderBy("bsp_id asc")->column();
        $result["pdt_unit"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>"jydw","bsp_status"=>10])->asArray()->indexBy("bsp_id")->orderBy("bsp_id asc")->column();
        $result["category"]=BsCategory::find()->select('catg_name')->where(["catg_level"=>1,"isvalid"=>1])->indexBy('catg_id')->asArray()->column();
        $result["brands"]=BsBrand::find()->select('brand_name_cn brand_name')->indexBy('brand_id')->asArray()->column();
        $result["supplier"]=PdSupplier::find()->select(["supplier_sname"])->where("supplier_sname!=''")->indexBy("supplier_id")->column();
        $result["currency"]=BsPubdata::find()->select("bsp_svalue")->where(["bsp_stype"=>"jybb","bsp_status"=>10])->asArray()->indexBy("bsp_id")->orderBy("bsp_id asc")->column();
        return $result;
    }


    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'pdt_no',
                "formName" => 'bs_product',
                "model" => $this,
            ]
        ];
    }


    public static  function getProduct($id)
    {
        return self::find()->select(['pdt_form','pdt_attribute'])->where(['pdt_pkid'=>$id])->one();
    }

}
