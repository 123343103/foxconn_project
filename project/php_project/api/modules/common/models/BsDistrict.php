<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * 地址联动信息表
 * This is the model class for table "bs_district".
 *
 * @property integer $district_id
 * @property string $district_name
 * @property string $district_code
 * @property integer $district_pid
 * @property integer $district_level
 * @property integer $order_no
 * @property integer $is_valid
 * @property integer $zip_code
 * @property string $district_path
 * @property string $extracted_flag
 * @property string $rma-flag
 * @property integer $fwd-district_id
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 * @property string $pywords
 * @property integer $iszsdservice
 * @property string $distinct_enname
 */
class BsDistrict extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_district';
    }

    /**
     * 获取地址一级
     * @param array $where
     * @param string $select
     * @param bool $asArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDisLeveOne($where=[],$select='district_id,district_pid,district_name',$asArray=true){
        return static::find()->select($select)->asArray($asArray)->where($where)->orderBy('district_id')->andWhere(['district_pid'=>0,'is_valid'=>1])->all();
    }

    /**
     * 获取省份
     * @param array $where
     * @param string $select
     * @param bool $asArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDisProvince($where=[],$select='district_id,district_pid,district_name',$asArray=true){
        return static::find()->select($select)->asArray($asArray)->where($where)->orderBy('district_id')->andWhere(['district_pid'=>1,'is_valid'=>1])->all();
    }

    /**
     * 根据父类地址获取子类地址
     * @param $id
     * @param string $select
     * @param bool $asArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getChildByParentId($id,$select='district_id,district_pid,district_name',$asArray=true){
        return static::find()->select($select)->asArray($asArray)->orderBy('district_id')->where(['district_pid'=>$id,'is_valid'=>1])->all();
    }

    /**
     * 根据子类地址递归获取所有父类地址
     * @param integer $id 子类地址
     * @return array
     */
    public function getParentsById($id)
    {
        static $arr = [];
        $district = BsDistrict::find()->where(['district_id'=>$id])->one();
        $arr[] = $district;
        if ($district['district_pid'] != 0) {
            $this->getParentsById($district['district_pid']);
        }
        array_multisort($arr,SORT_ASC); // 父级排前
        return $arr;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['district_id'], 'required'],
            [['district_id', 'district_pid', 'district_level', 'order_no', 'is_valid', 'zip_code', 'fwd-district_id', 'iszsdservice'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['district_name', 'fwd-district_name', 'create_by', 'update_by'], 'string', 'max' => 20],
            [['district_code'], 'string', 'max' => 255],
            [['district_path', 'pywords', 'distinct_enname'], 'string', 'max' => 64],
            [['extracted_flag', 'rma-flag'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'district_id' => 'District ID',
            'district_name' => 'District Name',
            'district_code' => 'District Code',
            'district_pid' => 'District Pid',
            'district_level' => 'District Level',
            'order_no' => 'Order No',
            'is_valid' => 'Is Valid',
            'zip_code' => 'Zip Code',
            'district_path' => 'District Path',
            'extracted_flag' => 'Extracted Flag',
            'rma-flag' => 'Rma Flag',
            'fwd-district_id' => 'Fwd District ID',
            'fwd-district_name' => 'Fwd District Name',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
            'pywords' => 'Pywords',
            'iszsdservice' => 'Iszsdservice',
            'distinct_enname' => 'Distinct Enname',
        ];
    }
    //获取地址的拼音(用于运费计算)
    public  static  function getPYTo($id)
    {
        $sql="SELECT distinct_enname FROM bs_district WHERE district_id = {$id}";
        return Yii::$app->getDb('db')->createCommand($sql)->queryOne();
    }
}
