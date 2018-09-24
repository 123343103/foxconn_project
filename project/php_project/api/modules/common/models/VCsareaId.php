<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "v_csarea_id".
 *
 * @property integer $csarea_id
 * @property string $msg
 */
class VCsareaId extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_csarea_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['csarea_id'], 'integer'],
            [['msg'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csarea_id' => 'Csarea ID',
            'msg' => 'Msg',
        ];
    }

    /**
     * 判断销售区域是否被引用
     * return array
     */
    public static function isUsed($id)
    {
        $used = self::find()->where(['csarea_id'=>$id])->one();
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
