<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "v_user_id".
 *
 * @property string $user_id
 * @property string $msg
 */
class VUserId extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_user_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'string', 'max' => 64],
            [['msg'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'msg' => 'Msg',
        ];
    }

    /**
     * 判断用户是否被引用
     * return array
     */
    public static function isUsed($id)
    {
        $used = self::find()->where(['user_id'=>$id])->one();
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
