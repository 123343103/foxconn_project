<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/23
 * Time: 下午 04:18
 */

namespace app\modules\ptdt\models;


use app\models\Common;
use app\modules\common\models\BsCategory;
use yii\db\ActiveRecord;

class FpCategoryMargin extends Common
{
    public static function tableName()
    {
        return 'fp_category_margin';
    }

    public function fields(){
        $fields=parent::fields();
        $fields["type_1"]=function(){
            return $this->category->parent->parent->parent->parent->parent->category_sname;
        };
        $fields["type_2"]=function(){
            return $this->category->parent->parent->parent->parent->category_sname;
        };
        $fields["type_3"]=function(){
            return $this->category->parent->parent->parent->category_sname;
        };
        $fields["type_4"]=function(){
            return $this->category->parent->parent->category_sname;
        };
        $fields["type_5"]=function(){
            return $this->category->parent->category_sname;
        };
        $fields["type_6"]=function(){
            return $this->category->category_sname;
        };
        $fields["min_margin"]=function(){
            return $this->min_margin."%";
        };
        return $fields;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'brand', 'min_margin'], 'required'],
            [['brand', 'min_margin'], 'safe']
        ];
    }

    public function getCategory(){
        return $this->hasOne(BsCategory::className(),['category_id'=>'category_id']);
    }

}