<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "bs_qst_answ".
 *
 * @property string $answ_id
 * @property string $invst_id
 * @property string $staff_id
 * @property string $staff_code
 * @property string $staff_name
 * @property string $dpt_id
 * @property string $dpt_no
 * @property string $dpt_name
 * @property integer $answ_datetime
 * @property integer $yn_log
 * @property string $answ_in
 * @property string $invst_state
 * @property string $crt_date
 */
class BsQstAnsw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_qst_answ';
    }


    public static function getBsQstAnswInfoOne($id)
    {
        return self::find()->where(['answ_id' => $id])->one();
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_code','staff_name','answ_datetime','dpt_id','dpt_no','dpt_name','yn_log'], 'integer'],
            [['answ_datetime'], 'string'],
            [['answ_datetime'], 'safe'],
            [['answ_in'], 'string', 'max' => 1],
            [['staff_id', 'invst_id', 'answ_id','dpt_id'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'answ_id' => '答题人pkid',
            'staff_id' => '答题目人.erp.hr_staff',
            'invst_id' => '问卷调查pkid.BS_QST_INVST.invst_id',
            'dpt_id' => '部门pkid.erp.hr_organization',
            'staff_code' => '工号',
            'staff_name'=>'姓名',
            'dpt_no' => '部门代码',
            'dpt_name' => '部门名称',
            'answ_datetime' => '答题目时间',
            'yn_log' => '是否登录答题',
            'answ_in' => '答題目入口(a:app,w:網站，e:郵箱.....)',
        ];
    }
}
