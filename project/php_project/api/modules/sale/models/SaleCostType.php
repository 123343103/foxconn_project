<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;
use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "sale_costtype".
 *
 * @property string $scost_id
 * @property string $scost_code
 * @property string $scost_sname
 * @property string $scost_status
 * @property string $scost_remark
 * @property string $scost_vdef1
 * @property string $scost_vdef2
 */
class SaleCostType extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_costtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scost_id','create_by','update_by'], 'integer'],
            [['scost_code', 'scost_sname'], 'string', 'max' => 20],
            [['scost_status'], 'string', 'max' => 2],
            [['scost_remark','scost_description', 'scost_vdef1', 'scost_vdef2'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scost_id' => 'ID',
            'scost_code' => '类型编码',
            'scost_sname' => '类型名称',
            'scost_status' => '费用类别状态',
            'scost_remark' => '费用备注',
            'scost_description'=>'费用描述',
            'scost_vdef1' => 'Scost Vdef1',
            'scost_vdef2' => 'Scost Vdef2',
        ];
    }

    public function getCreatorStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
    }
    public function getUpdateStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'update_by']);
    }

    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']            //更新
                ],
                'value'=>function(){
                    return date("Y-m-d",time());          //賦值的值來源,如不同複寫
                }
            ],
        ];
    }
}
