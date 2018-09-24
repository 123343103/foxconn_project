<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;
use app\behaviors\StaffBehavior;
use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "sale_tripcost_list".
 *
 * @property string $stcl_id
 * @property string $scost_id
 * @property string $stcl_code
 * @property string $stcl_sname
 * @property string $stcl_description
 * @property string $stcl_status
 * @property string $stcl_remark
 * @property string $vdef1
 * @property string $vder2
 * @property string $vdef3
 * @property string $vdef4
 * @property string $vdef5
 */
class SaleCostList extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_tripcost_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stcl_id', 'scost_id','create_by','update_by' ], 'integer'],
            [['stcl_code'], 'string', 'max' => 20],
            [['stcl_sname'], 'string', 'max' => 60],
            [['stcl_description', 'stcl_remark', 'vdef1', 'vder2', 'vdef3', 'vdef4', 'vdef5'], 'string', 'max' => 120],
            [['stcl_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stcl_id' => '费用分类ID',
            'scost_id' => '费用类别ID',
            'stcl_code' => '代码',
            'stcl_sname' => '名称',
            'stcl_description' => '描述',
            'stcl_status' => '状态',
            'stcl_remark' => '备注',
            'vdef1' => 'Vdef1',
            'vder2' => 'Vder2',
            'vdef3' => 'Vdef3',
            'vdef4' => 'Vdef4',
            'vdef5' => 'Vdef5',
        ];
    }
    public  static function getOne($id,$select=null){
        return self::find()->where(['stcl_id'=>$id])->select($select)->one();
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
