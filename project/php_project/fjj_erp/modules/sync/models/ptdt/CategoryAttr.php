<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "category_attr".
 *
 * @property integer $CATEGORY_ATTR_ID
 * @property string $ATTR_NAME
 * @property string $ATTR_UNIT
 * @property string $ATTR_REMARK
 * @property integer $ISREQUIRED
 * @property integer $ISFILTER
 * @property integer $ATTRIBUTE_TYPE
 * @property integer $ISVALID
 * @property integer $ATTR_SORT
 * @property string $CREATEDDT
 * @property string $CREATEDBY
 * @property string $LASTEDITDT
 * @property string $LASTEDITBY
 */
class CategoryAttr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_attr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ATTR_NAME'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CATEGORY_ATTR_ID' => 'Category  Attr  ID',
            'ATTR_NAME' => 'Attr  Name',
            'ATTR_UNIT' => 'Attr  Unit',
            'ATTR_REMARK' => 'Attr  Remark',
            'ISREQUIRED' => 'Isrequired',
            'ISFILTER' => 'Isfilter',
            'ATTRIBUTE_TYPE' => 'Attribute  Type',
            'ISVALID' => 'Isvalid',
            'ATTR_SORT' => 'Attr  Sort',
            'CREATEDDT' => 'Createddt',
            'CREATEDBY' => 'Createdby',
            'LASTEDITDT' => 'Lasteditdt',
            'LASTEDITBY' => 'Lasteditby',
        ];
    }
}
