<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_catg_attr".
 *
 * @property string $catg_attr_id
 * @property string $catg_id
 * @property string $attr_name
 * @property string $attr_remark
 * @property integer $isrequired
 * @property integer $attr_type
 * @property string $opp_date
 * @property string $opper
 * @property string $opp_ip
 * @property integer $status
 * @property BsCategory $catg
 * @property RAttrValue[] $rAttrValues
 */
class BsCatgAttr extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_catg_attr';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catg_id', 'attr_name', 'isrequired'], 'required'],
            [['catg_id', 'isrequired', 'attr_type', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['attr_name'], 'string', 'max' => 30],
            [['attr_remark'], 'string', 'max' => 200],
            [['opp_ip'], 'string', 'max' => 16],
            [['catg_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsCategory::className(), 'targetAttribute' => ['catg_id' => 'catg_id']],
            //属性名称唯一
            ['attr_name', 'unique', 'targetAttribute' => ['catg_id', 'attr_name']],
            //设置默认值
            ['status', 'default', 'value'=>1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'catg_attr_id' => '類別屬性PKID',
            'catg_id' => '類別PKID，來自bs_category.catg_id',
            'attr_name' => '屬性名稱',
            'attr_remark' => '屬性備註',
            'isrequired' => '是否必需.1,是;0,否',
            'attr_type' => '0多項選擇;1平舖選擇;2下拉選擇;3文字輸入',
            'opp_date' => '操作時間',
            'opper' => '操作人',
            'opp_ip' => '操作IP',
        ];
    }
}
