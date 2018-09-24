<?php

namespace app\modules\warehouse\models;

use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "bs_wh_price".
 *
 * @property string $whpb_id
 * @property string $whpb_code
 * @property string $whpb_sname
 * @property string $stcl_description
 * @property integer $stcl_status
 * @property string $stcl_remark
 * @property integer $create_by
 * @property string $cdate
 * @property integer $update_by
 * @property string $udate
 * @property string $vdef1
 * @property string $vder2
 */
class BsWhPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.bs_wh_price';
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
            [['stcl_status', 'create_by', 'update_by'], 'integer'],
            [['cdate', 'udate'], 'safe'],
            [['whpb_code'], 'string', 'max' => 20],
            [['whpb_sname'], 'string', 'max' => 60],
            [['stcl_description', 'stcl_remark', 'vdef1', 'vder2'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'whpb_id' => 'Whpb ID',
            'whpb_code' => 'Whpb Code',
            'whpb_sname' => 'Whpb Sname',
            'stcl_description' => 'Stcl Description',
            'stcl_status' => 'Stcl Status',
            'stcl_remark' => 'Stcl Remark',
            'create_by' => 'Create By',
            'cdate' => 'Cdate',
            'update_by' => 'Update By',
            'udate' => 'Udate',
            'vdef1' => 'Vdef1',
            'vder2' => 'Vder2',
        ];
    }

    //创建人
    public function getCHrStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
    }

    //修改人
    public function getUHrStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'update_by']);
    }

    public static function getList()
    {
        $list = static::find()->select("whpb_code,whpb_sname,whpb_id")->where(['stcl_status' => 1])->asArray()->all();
        return isset($list) ? $list : [];
    }
}
