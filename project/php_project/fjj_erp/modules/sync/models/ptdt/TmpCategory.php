<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "bs_category".
 *
 * @property integer $category_id
 * @property string $category_sname
 * @property integer $category_level
 * @property integer $p_category_id
 * @property integer $ordery
 * @property integer $isvalid
 * @property string $img_name
 * @property integer $yn_machine
 * @property string $create_at
 * @property string $create_by
 * @property string $update_at
 * @property string $update_by
 */
class TmpCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tmp_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CATEGORY_ID', 'CATEGORY_NAME'], 'required'],
            [["CATEGORY_LEVEL","P_CATEGORY_ID","ORDERBY","ISVALID","CENTER","MIN_MARGIN"],'safe']
        ];
    }
}
