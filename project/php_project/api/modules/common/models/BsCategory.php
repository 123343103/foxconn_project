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
use Yii;

class BsCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'pdt.bs_category';
    }

    /**
     * 獲取一階分類
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLevelOne($where = [],$select='catg_id,catg_name')
    {
        return static::find()->where($where)->select($select)->orderBy('orderby')->andWhere(['catg_level' => 1, 'isvalid' => 1])->all();
    }

    /**
     * 獲取子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getChildrenByParentId($id,$select = 'catg_id,catg_name,p_catg_id', $asArray = true)
    {
        return static::find()->select($select)->asArray(true)->where(['p_catg_id' =>$id])->all();
    }

    public function getParent()
    {
        return static::find()->where(['catg_id' =>$this->p_category_id])->one();
    }
    public static function getOneCategory($id)
    {
        return static::find()->where(['catg_id' =>$id])->select('catg_id,catg_name')->asArray()->one();
    }
    /**
     * 獲取子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTypeOption($id,$select = 'catg_id,catg_name', $asArray = true)
    {
        $child =  static::find()->select($select)->asArray(true)->where(['p_catg_id' =>$id])->all();
        foreach ($child as $key=>$val){
            $newList[$val['catg_id']]=$val['catg_name'];
        }
        return $newList;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catg_id'], 'required'],
            [['catg_name', 'catg_level', 'p_catg_id', 'orderby', 'isvalid', 'center', 'min_margin'], 'safe'],
        ];
    }
    
      /**
     * 新增生成新ID
     * @param int $id 为 父级id
     */
    public function getNewId($id)
    {
        //獲取所有本階分類
        $levelAll = $this->find()->where(['catg_id' => $id]);
        $idArr = $levelAll->select('p_catg_id')->asArray()->column();
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
        if ($model = FpBsCategory::find()->where(['catg_id' => $id])->one()) {
            return $model;
        } else {
            throw new NotFoundHttpException("页面未找到");
        }
    }

    //根据父id获取子类
    public static function getChildCategory($id)
    {
        $data=self::findAll(['p_catg_id'=>$id]);
        $arr=array();
        foreach($data as $val){
            $arr[$val['catg_id']]=$val['catg_name'];
        }
        return $arr;
    }
    public static function getExcelData($type){
        $list = static::find()->select('catg_id')->where(['catg_name'=>$type])->one();
        return $list;
    }
}