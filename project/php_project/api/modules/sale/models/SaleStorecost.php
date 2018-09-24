<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\crm\models\CrmStoresinfo;
use Yii;

/**
 * This is the model class for table "sale_storecost".
 *
 * @property integer $storc_id
 * @property string $sts_code
 * @property string $csarea_code
 * @property string $dep_id
 * @property string $storc_year
 * @property string $storc_month
 * @property string $rent
 * @property string $power
 * @property string $work
 * @property string $charge1
 * @property string $charge2
 * @property string $charge3
 * @property string $total
 */
class SaleStorecost extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_storecost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['storc_id'], 'integer'],
            [['sts_code', 'csarea_code', 'dep_id'], 'string', 'max' => 20],
            [['rent', 'power', 'work', 'charge1', 'charge2', 'charge3', 'total'], 'number'],
            [['storc_year', 'storc_month'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'storc_id' => 'ID',
            'sts_code' => '銷售點代碼',
            'csarea_code' => '軍區代碼 關聯銷售區域表',
            'dep_id' => '費用代碼',
            'storc_year' => '期間(年)',
            'storc_month' => '期間(月)',
            'rent' => '租金',
            'power' => '動力費',
            'work' => '辦公費',
            'charge1' => '費用1',
            'charge2' => '費用2',
            'charge3' => '費用3',
            'total' => '費用總和',
        ];
    }

    // 获取门店信息
    public function getStoreInfo()
    {
        return $this->hasOne(CrmStoresinfo::className(),['sts_code'=>'sts_code']);
    }

}
