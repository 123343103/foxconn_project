<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsCurrency;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * This is the model class for table "bs_partno".
 *
 * @property string $prt_pkid
 * @property string $pdt_PKID
 * @property string $part_no
 * @property string $pdt_origin
 * @property integer $warranty_period
 * @property string $min_order
 * @property integer $part_status
 * @property integer $yn_inquiry
 * @property string $min_inquirynum
 * @property integer $machine_type
 * @property integer $yn_tax
 * @property string $tp_spec
 * @property integer $yn_free_delivery
 * @property integer $yn_discuss
 * @property integer $isselftake
 * @property integer $isactivity
 * @property string $supplier_no
 * @property string $sale_dpt
 * @property string $wh_id
 * @property integer $cm_pos
 * @property string $l/t
 * @property integer $leg_lv
 * @property integer $is_agent
 * @property integer $is_batch
 * @property integer $is_first
 * @property string $marks
 * @property string $crter
 * @property string $crt_date
 * @property string $crt_ip
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 * @property integer $yn_pa_fjj
 * @property integer $yn_pallet
 *
 * @property BsProduct $pdtPK
 */
class BsPartno extends Common
{
    const CHECK_CHECKING = 1;//审核中
    const CHECK_REJECT = 2;//审核驳回
    const CHECK_PASS = 3;//审核完成

    const STATUS_UNCOMMIT = 1;//未提交
    const STATUS_UPSHELF = 2;//上架
    const STATUS_DOWNSHELF = 3;//下架
    const STATUS_MODIFY = 4;//修改
    const STATUS_REUPSHELF = 5;//重新上架

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdt.bs_partno';
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
            [['pdt_pkid', 'part_no', 'pdt_origin', 'warranty_period', 'min_order', 'part_status', 'yn_inquiry'], 'required'],
            [['pdt_pkid', 'warranty_period', 'part_status', 'yn_inquiry', 'machine_type', 'yn_tax', 'yn_free_delivery', 'yn_discuss', 'isselftake', 'isactivity', 'supplier_no', 'sale_dpt', 'wh_id', 'cm_pos', 'leg_lv', 'is_agent', 'is_batch', 'is_first', 'crter', 'opper', 'yn_pa_fjj', 'yn_pallet'], 'integer'],
            [['min_order', 'min_inquirynum'], 'number'],
            [['crt_date', 'opp_date','off_date','file_old','file_new','rs_id','other_reason'], 'safe'],
            [['part_no', 'pdt_origin', 'l/t'], 'string', 'max' => 20],
            [['part_no'], 'exist', 'targetAttribute' => 'part_no', 'message' => '料号不存在!', 'on' => ["exist"]],
            [['tp_spec'], 'string', 'max' => 100],
            [['marks'], 'string', 'max' => 255],
            [['crt_ip', 'opp_ip','off_ip'], 'string', 'max' => 16],
            [['part_no'], 'unique', 'on' => ["default"]],
            [['pdt_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsProduct::className(), 'targetAttribute' => ['pdt_pkid' => 'pdt_pkid']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        //各个场景的活动属性
        $scenarios['exist'] = ['part_no'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_pkid' => 'Prt Pkid',
            'pdt_pkid' => 'Pdt  Pkid',
            'part_no' => 'Part No',
            'pdt_origin' => 'Pdt Origin',
            'warranty_period' => 'Warranty Period',
            'min_order' => 'Min Order',
            'part_status' => 'Part Status',
            'yn_inquiry' => 'Yn Inquiry',
            'min_inquirynum' => 'Min Inquirynum',
            'machine_type' => 'Machine Type',
            'yn_tax' => 'Yn Tax',
            'tp_spec' => 'Tp Spec',
            'yn_free_delivery' => 'Yn Free Delivery',
            'yn_discuss' => 'Yn Discuss',
            'isselftake' => 'Isselftake',
            'isactivity' => 'Isactivity',
            'supplier_no' => 'Supplier No',
            'sale_dpt' => 'Sale Dpt',
            'wh_id' => 'Wh ID',
            'cm_pos' => 'Cm Pos',
            'l/t' => 'L/t',
            'leg_lv' => 'Leg Lv',
            'is_agent' => 'Is Agent',
            'is_batch' => 'Is Batch',
            'is_first' => 'Is First',
            'marks' => 'Marks',
            'crter' => 'Crter',
            'crt_date' => 'Crt Date',
            'crt_ip' => 'Crt Ip',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
            'yn_pa_fjj' => 'Yn Pa Fjj',
            'yn_pallet' => 'Yn Pallet',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdtPK()
    {
        return $this->hasOne(BsProduct::className(), ['pdt_pkid' => 'pdt_pkid']);
    }

    public function getBsPack()
    {
        return $this->hasOne(BsPack::className(), ['prt_pkid' => 'prt_pkid'])->where(['pck_type' => 2]);
    }


    //单个料号及其相关数据
    public static function getPartInfo($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = self::findOne($id);
        $result = ArrayHelper::toArray($model);
        $result["warehouse"] = self::getDb()->createCommand("SELECT bs_wh.wh_name,bs_wh.wh_addr FROM pdt.r_prt_wh LEFT JOIN wms.bs_wh ON bs_wh.wh_id=r_prt_wh.wh_id  WHERE r_prt_wh.prt_pkid=:id", [":id" => $id])->queryAll();
        $result["stock"] = self::getDb()->createCommand("SELECT * FROM bs_stock WHERE prt_pkid=:id", [":id" => $id])->queryAll();
        $result["ship"] = self::getDb()->createCommand("SELECT * FROM bs_ship WHERE prt_pkid=:id", [":id" => $id])->queryAll();
        $result["deliv"] = self::getDb()->createCommand("SELECT * FROM bs_deliv WHERE prt_pkid=:id", [":id" => $id])->queryAll();
        $result["pack"] = self::getDb()->createCommand("SELECT * FROM bs_ship WHERE prt_pkid=:id", [":id" => $id])->queryAll();
        $result["usage"] = self::getDb()->createCommand("SELECT * FROM bs_machine WHERE prt_pkid=:id", [":id" => $id])->queryOne();
        $result["warr"] = self::getDb()->createCommand("SELECT * FROM bs_warr WHERE prt_pkid=:id", [":id" => $id])->queryAll();
        return $result;
    }

    public static function search($params = "")
    {
        $query = self::find();
        $provider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10
            ]
        ]);
        return [
            "rows" => $provider->models,
            "total" => $provider->totalCount
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(BsProduct::className(), ["pdt_pkid" => "pdt_pkid"]);
    }

    public function getPrice()
    {
        return $this->hasMany(BsPrice::className(), ["prt_pkid" => "prt_pkid"]);
    }

    public function getOpper()
    {
        return $this->hasOne(HrStaff::className(), ["staff_id" => "opper"]);
    }


    public static function getPratno($id)
    {
        $query = self::find()->select(['yn_free_delivery', 'pdt_pkid'])->where(['prt_pkid' => $id])->one();
        return $query;
    }

}
