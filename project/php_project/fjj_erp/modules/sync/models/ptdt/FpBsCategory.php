<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/23
 * Time: 下午 02:49
 */

namespace app\modules\sync\models\ptdt;


use yii\db\ActiveRecord;

class FpBsCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'fp_bs_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'required'],
            [['category_name', 'category_level', 'p_category_id', 'orderby', 'isvalid', 'center', 'min_margin'], 'safe'],
        ];
    }
}