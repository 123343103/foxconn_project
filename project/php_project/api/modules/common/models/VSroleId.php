<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "v_srole_id".
 *
 * @property string $srole_id
 * @property string $msg
 */
class VSroleId extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_srole_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['srole_id'], 'integer'],
            [['msg'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'srole_id' => 'Srole ID',
            'msg' => 'Msg',
        ];
    }

    /**
     * 判断销售角色是否被引用
     * return array
     */
    public static function isUsed($id)
    {
        $used = self::find()->where(['srole_id'=>$id])->one();
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