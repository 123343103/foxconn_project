<?php
/**
 * 商品类别模型
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2017/02/16
 * Time: 下午 11:36
 */

namespace app\modules\common\models;


use yii\db\ActiveRecord;

class BsBrand extends ActiveRecord
{
    public static function tableName()
    {
        return 'bs_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BRAND_NAME_CN'], 'required']
        ];
    }
}