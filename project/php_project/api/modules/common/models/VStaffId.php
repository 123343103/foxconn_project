<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "v_staff_id".
 *
 * @property string $staff_id
 * @property string $msg
 */
class VStaffId extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_staff_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id'], 'integer'],
            [['msg'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => 'Staff ID',
            'msg' => 'Msg',
        ];
    }

    /**
     * 判断员工是否被引用
     * return array
     */
    public static function isUsed($id)
    {
        $used = self::find()->where(['staff_id'=>$id])->one();
        if (empty($used)) {
            $res['status'] = 1;
            $res['msg'] = '没有引用,可删除';
        } else {
            $res['status'] = 0;
            $res['msg'] = $used['msg'];
        }
        return $res;
    }
}
