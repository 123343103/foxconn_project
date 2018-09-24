<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsPubdata;
use Yii;

/**
 * This is the model class for table "crm_bs_cust_purchase".
 *
 * @property string $cpurch_id
 * @property string $cust_id
 * @property string $category_id
 * @property string $pdt_no
 * @property string $purchaseqty
 * @property string $itemname
 * @property string $pruchasecost
 * @property string $pruchasetype
 * @property string $status
 * @property string $description
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCustPurchase extends Common
{
    /**
     * @inheritdoc
     */
    const STATUS_DELETE = '0';          //删除
    const STATUS_DEFAULT = '10';        //正常

    public static function tableName()
    {
        return 'crm_bs_customer_purchase';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'create_by', 'update_by'], 'integer'],
            [['purchaseqty', 'pruchasecost'], 'number'],
            [['create_at', 'update_at'], 'safe'],
            [['category_id', 'pdt_no','pruchaseqty_cur'], 'string', 'max' => 20],
            [['itemname', 'pruchasetype', 'description'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 2],
            ['status','default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cpurch_id' => 'ID',
            'cust_id' => '客户信息ID',
            'category_id' => '商品大类',
            'pdt_no' => '商品料号',
            'purchaseqty' => '月采购数量',
            'itemname' => '商品名称',
            'pruchasecost' => '年采购额',
            'pruchasetype' => '采购渠道',
            'status' => '状态',
            'description' => '描述',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
        ];
    }

    public function getPruchaseqty(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pruchaseqty_cur']);
    }
}
