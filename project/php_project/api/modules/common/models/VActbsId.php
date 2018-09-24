<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "v_actbs_id".
 *
 * @property integer $actbs_id
 * @property string $msg
 */
class VActbsId extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_actbs_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['actbs_id'], 'integer'],
            [['msg'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'actbs_id' => 'Actbs ID',
            'msg' => 'Msg',
        ];
    }

    /**
     * 判断用户是否被引用
     * return array
     */
    public static function isUsed($id)
    {
        $used = self::find()->where(['actbs_id'=>$id])->one();
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