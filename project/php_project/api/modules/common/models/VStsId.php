<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "v_sts_id".
 *
 * @property string $sts_id
 * @property string $msg
 */
class VStsId extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_sts_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sts_id'], 'integer'],
            [['msg'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sts_id' => 'Sts ID',
            'msg' => 'Msg',
        ];
    }

    /**
     * 判断销售点是否被引用
     * return array
     */
    public static function isUsed($id)
    {
        $used = self::find()->where(['sts_id'=>$id])->one();
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
