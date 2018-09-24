<?php

namespace app\modules\warehouse\models;

use app\modules\common\models\BsPubdata;
use Yii;

/**
 * This is the model class for table "wh_price".
 *
 * @property string $whp_id
 * @property string $op_id
 * @property string $whp_code
 * @property string $whp_sname
 * @property string $wh_id
 * @property integer $whp_status
 * @property integer $create_by
 * @property string $cdate
 * @property integer $update_by
 * @property string $udate
 * @property string $whp_remark
 * @property string $whp_vdef1
 */
class WhPrice extends \yii\db\ActiveRecord
{
    const OUT_WH = 100904;//出仓操作id

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.wh_price';
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
            [['op_id', 'wh_id', 'whp_status', 'create_by', 'update_by'], 'integer'],
            [['cdate', 'udate'], 'safe'],
            [['whp_code', 'whp_sname'], 'string', 'max' => 20],
            [['whp_remark', 'whp_vdef1'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'whp_id' => 'Whp ID',
            'op_id' => 'Op ID',
            'whp_code' => 'Whp Code',
            'whp_sname' => 'Whp Sname',
            'wh_id' => 'Wh ID',
            'whp_status' => 'Whp Status',
            'create_by' => 'Create By',
            'cdate' => 'Cdate',
            'update_by' => 'Update By',
            'udate' => 'Udate',
            'whp_remark' => 'Whp Remark',
            'whp_vdef1' => 'Whp Vdef1',
        ];
    }

    //仓库名称 仓库代码
    public function getBsWh()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => 'wh_id']);
    }

    //仓库标准操作
    public function getBsPubData()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'op_id']);
    }
}
