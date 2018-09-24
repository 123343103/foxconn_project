<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * F3858995
 * 2016.10.27
 *
 *
 * @property integer $business_type_id
 * @property string $business_code
 * @property string $business_type_desc
 * @property string $business_value
 * @property integer $status
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsBusinessType extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_business_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_code'], 'required'],
            [['status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['business_code'], 'string', 'max' => 20],
            [['business_type_desc', 'business_value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business_type_id' => '主键ID',
            'business_code' => '业务代码',
            'business_type_desc' => '类别说明',
            'business_value' => '业务类别',
            'status' => '0,1',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
    }

    public static function getBusinessType($where=[],$asArray=true){
        return self::find()->select(['business_type_id','business_type_desc'])->asArray($asArray)->where($where)->all();
    }

    public static function getTree()
    {
        static $str = "";
        $tree = BsBusiness::find()->where(['flag' => BsBusiness::REVIEW_ENABLED])->all();
        foreach ($tree as $key => $val) {
            $childs = self::find()->where(['business_code' => $val['business_code']])->all();
            $str .= "
               {  
                id :\"". $val['business_code'] . "\",
                text :\"". $val['business_desc'] . "\",
                selectable :\"". false . "\",
            ";

            if ($childs) {

                $str .= "
                            nodes:[";
                foreach ($childs as $k => $v) {
                    $str .= "
               {
                id :\"". $v['business_type_id'] . "\",
                text :\"". $v['business_type_desc'] . "\",
                selectable :\"". 1 . "\",},
            ";

                }
                $str .= "
                            ]},";
            } else {
                $str .= "
                    },
                ";
            }
        }
        return $str;
    }
}
