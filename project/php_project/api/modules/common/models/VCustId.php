<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "v_cust_id".
 *
 * @property string $cust_id
 * @property string $msg
 */
class VCustId extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_cust_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id'], 'integer'],
            [['msg'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cust_id' => 'Cust ID',
            'msg' => 'Msg',
        ];
    }

    /**
     * 判断客户是否被引用
     * return array
     */
    public static function isUsed($id)
    {
        $used = self::find()->where(['cust_id'=>$id])->one();
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
