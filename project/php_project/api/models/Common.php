<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/4/21
 * Time: 上午 11:03
 */
namespace app\models;

use app\classes\Trans;
use \yii\db\ActiveRecord;
use yii\helpers\Html;

class Common extends ActiveRecord
{
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $langFlag = isset(\Yii::$app->params['langFlag']) ? \Yii::$app->params['langFlag'] : 0;
            if ($langFlag == 0) { // 不转换
                $attrs = $this->attributes;
                foreach ($attrs as $attr => $val) {
                    if (is_string($this->$attr)) {
                        $this->$attr = trim($this->$attr);
                    }
                }
                return true;
            }
            $trans = new Trans();
            $attrs = $this->attributes;
            foreach ($attrs as $attr => $val) {
                if (is_string($this->$attr)) {
                    if ($langFlag == 1) { // 转换成简体存储
                        $this->$attr = trim($trans->t2c($this->$attr));
                    } else { // 转换繁体体存储
                        $this->$attr = trim($trans->c2t($this->$attr));
                    }
                }
            }
        }
        return true;
    }
}

?>