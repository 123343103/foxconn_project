<?php

namespace app\modules\sync\models\member;

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
class TmpCustomerInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tmp_customer_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ["CMP_CODE","required"],
            [[
                "CREAT_DATE",
                "COM_TYPE",
                "CMP_NAME",
                "CMP_STORTNAME",
                "TELEPHONE",
                "ACCOUNT_CODE",
                "MOBILE_TELEPHONE",
                "EMAIL",
                "REG_PROVINCE",
                "REG_CITY",
                "REG_DISTRICT",
                "REG_ADDR",
                "ORGANIZATION_CODE",
                "REG_CURRENCY",
                "INVO_TYPE",
                "COM_TAX",
                "LICENSE_CODE",
                "TAX_CODE",
                "THREE_TO_ONE"],'safe']
        ];
    }
}
