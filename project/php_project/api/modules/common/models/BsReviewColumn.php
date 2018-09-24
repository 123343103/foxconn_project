<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * F3858995
 * 2016.11.21
 * 审批条件选择列
 *
 * @property integer $condition_id
 * @property string $business_code
 * @property string $form_column_name
 * @property string $form_column_desc
 */
class BsReviewColumn extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_review_column';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_code', 'form_column_name', 'form_column_desc'], 'required'],
            [['business_code'], 'string', 'max' => 20],
            [['form_column_name'], 'string', 'max' => 64],
            [['form_column_desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'condition_id' => '主键ID',
            'business_code' => '业务代码',
            'form_column_name' => '表单栏位名',
            'form_column_desc' => '栏位说明',
        ];
    }

    public static function getColumnById($id){
        return self::find()->where(['condition_id'=>$id])->one();
    }

    public function getCondition(){
       $this->hasMany(BsReviewCondition::className(),['condition_id'=>'column']);
    }
}
