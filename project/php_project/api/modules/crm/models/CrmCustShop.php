<?php


namespace app\modules\crm\models;

use app\modules\common\models\BsCompany;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\BsCategory;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * 开店信息模型
 * User: F3859386
 * Date: 2017/2/27
 * Time: 14:08
 * @property integer $shop_id
 * @property integer $cust_id
 * @property string $member_type
 * @property string $shop_date
 * @property string $shop_name
 * @property integer $shop_qty
 * @property string $shop_flag
 * @property string $shop_pdtype
 * @property string $shop_otherpd
 * @property string $shop_isbail
 * @property string $shop_bailqty
 * @property string $shop_bailcurr
 * @property string $shop_chaman
 * @property string $shop_tel
 * @property string $shop_svrtel
 * @property string $shop_status
 * @property string $shop_remark
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCustShop extends Common
{
    const STATUS_DELETE = '0';
    const STATUS_DEFAULT = '10';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_cust_shop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'shop_id'], 'integer'],
            [['shop_flag', 'shop_isbail', 'shop_otherpd', 'shop_remark', 'shop_status', 'shop_date', 'create_by', 'create_at', 'update_by', 'update_at', 'shop_bailqty', 'shop_qty', 'member_type', 'shop_pdtype', 'shop_bailcurr', 'shop_chaman', 'shop_tel', 'shop_svrtel'], 'safe'],
//            [[], 'number'],
//            [[], 'string', 'max' => 20],
            [['shop_name'], 'string', 'max' => 60],
            [['shop_status'], 'default', 'value' => self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shop_id' => 'ID',
            'cust_id' => 'Cust ID',
            'member_type' => 'Member Type',
            'shop_date' => 'Shop Date',
            'shop_name' => 'Shop Name',
            'shop_qty' => 'Shop Qty',
            'shop_flag' => 'Shop Flag',
            'shop_pdtype' => 'Shop Pdtype',
            'shop_otherpd' => 'Shop Otherpd',
            'shop_isbail' => 'Shop Isbail',
            'shop_bailqty' => 'Shop Bailqty',
            'shop_bailcurr' => 'Shop Bailcurr',
            'shop_chaman' => 'Shop Chaman',
            'shop_tel' => 'Shop Tel',
            'shop_svrtel' => 'Shop Svrtel',
            'shop_status' => 'Shop Status',
            'shop_remark' => 'Shop Remark',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
    }

    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }

    public static function getByAll($condition)
    {
        return self::find()->where($condition)->andWhere(['!=', 'shop_status', CrmCustShop::STATUS_DELETE])->orderBy('create_at DESC')->all();
    }


    public function fields()
    {
        $field = parent::fields();
        $field['shop_bailqty'] = function () {
            return bcadd($this->shop_bailqty, 0, 0);
        };
        $field['shop_bailcurr'] = function () {
            return BsPubdata::find()->where(['bsp_id' => $this->shop_bailcurr])->one()['bsp_svalue'];
        };
        return $field;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 招商店铺信息
     */
    public function searchShopInfo($params = null)
    {
        $query = self::find()->where(['cust_id' => $params['id']])->andWhere(['!=', 'shop_status', CrmCustShop::STATUS_DELETE]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 5;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query
//        ]);
        $query->orderBy("create_at desc");

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}