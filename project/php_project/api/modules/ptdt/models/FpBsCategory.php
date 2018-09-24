<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/23
 * Time: 下午 02:49
 */

namespace app\modules\ptdt\models;


use app\models\Common;
use yii\db\ActiveRecord;

class FpBsCategory extends Common
{
    public static function tableName()
    {
        return 'fp_bs_category';
    }

    /**
     * 獲取一階分類
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLevelOne($where = [], $asArray = true)
    {
        return static::find()->asArray($asArray)->where($where)->orderBy('orderby')->andWhere(['category_level' => 1, 'isvalid' => 1])->all();
    }

    /**
     * 獲取子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getChildrenByParentId($id,$select = 'category_id,category_name,p_category_id', $asArray = true)
    {
        return static::find()->select($select)->asArray(true)->where(['p_category_id' =>$id])->all();
    }
    public function getParent()
    {
        return $this->hasOne(FpBsCategory::className(), ['category_id' => 'p_category_id']);
    }
    /**
     * 獲取子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTypeOption($id,$select = 'category_id,category_name', $asArray = true)
    {
        $child =  static::find()->select($select)->asArray(true)->where(['p_category_id' =>$id])->all();
        foreach ($child as $key=>$val){
            $newList[$val['category_id']]=$val['category_name'];
        }
        return $newList;
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
    
      /**
     * 新增生成新ID
     * @param int $id 为 父级id
     */
    public function getNewId($id)
    {
        //獲取所有本階分類
        $levelAll = $this->find()->where(['category_id' => $id]);
        $idArr = $levelAll->select('p_category_id')->asArray()->column();
        if ($id === 0) {
            //一级类别id从21开始  生成新id为最大id加1
            if (count($idArr) == 0) {
                $new_id = 21;
            } else {
                $new_id = max($idArr) + 1;
            }
        } else {
            $new_id = $id;
            $new_id .= "01";
        } 
        return $new_id;
    }

    /**
     * 獲取模型
     * @param $id
     * @return null|static
     * @throws \Exception
     */
    public static function getModel($id)
    {
        if ($model = FpBsCategory::find()->where(['category_id' => $id])->one()) {
            return $model;
        } else {
            throw new NotFoundHttpException("页面未找到");
        }
    }

}