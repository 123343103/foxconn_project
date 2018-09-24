<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/23
 * Time: 下午 04:18
 */

namespace app\modules\sync\models\ptdt;
use yii\db\ActiveRecord;

class FpCategoryMargin extends ActiveRecord
{
    public static function tableName()
    {
        return 'fp_category_margin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['category_id', 'brand', 'min_margin'], 'safe']
        ];
    }

}