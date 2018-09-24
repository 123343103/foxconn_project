<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "rcp_goods_dt".
 *
 * @property string $detail_id
 * @property string $rcpg_no
 * @property string $rcpdt_id
 * @property string $part_no
 * @property string $rcpg_num
 * @property string $rcpg_date
 * @property integer $operator
 * @property string $operate_date
 * @property string $operate_ip
 */
class RcpGoodsDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rcp_goods_dt';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rcpdt_id', 'operator'], 'integer'],
            [['rcpg_num'], 'number'],
            [['rcpg_date', 'operate_date'], 'safe'],
            [['rcpg_no'], 'string', 'max' => 30],
            [['part_no', 'operate_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'detail_id' => '詳情ID',
            'rcpg_no' => '收貨單號',
            'rcpdt_id' => '收货通知詳情id',
            'part_no' => '料號',
            'rcpg_num' => '收貨數量',
            'rcpg_date' => '收貨時間',
            'operator' => '操作人',
            'operate_date' => '操作時間',
            'operate_ip' => '操作IP',
        ];
    }
}
