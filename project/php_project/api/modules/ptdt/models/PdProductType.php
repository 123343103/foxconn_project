<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\ptdt\controllers\ProductTypeMaintainController;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
use yii\behaviors\TimestampBehavior;
use app\behaviors\StaffBehavior;
use yii\web\NotFoundHttpException;

/**
 *
 * 商品類別模型
 * F3858995
 * 2016.9.22
 *
 * @property integer $type_id
 * @property integer $type_pid
 * @property string $type_name
 * @property integer $type_index
 * @property integer $type_level
 * @property string $type_picture
 * @property integer $is_special
 * @property string $create_at
 * @property integer $creator
 * @property string $update_at
 * @property integer $updater
 */
class
PdProductType extends Common
{
    const LEVEL_ONE = 1;          //一级
    const LEVEL_TWO = 2;          //二级
    const LEVEL_THREE = 3;        //三级
    const LEVEL_FOUR = 4;         //四级
    const LEVEL_FIVE = 5;         //五级
    const LEVEL_SIX = 6;          //六级
    const ONE_PID = 0;           //一级父ID
    const STATUS_DISUSE = 0;     //废弃状态
    const STATUS_NORMAL = 1;     //正常状态
    const STATUS_CHECKING = 2;   //审核状态
    const NOT_SPECIAL = 0;       //不属于设备专区
    const SPECIAL = 1;           //属于设备专区

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_product_type';
    }

    /**
     * 獲取一階分類
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLevelOne($where = [], $select = 'type_id,type_pid,type_name', $asArray = true)
    {
        return static::find()->select($select)->asArray($asArray)->where($where)->orderBy('type_index')->andWhere(['type_pid' => 0, 'is_valid' => 1])->all();
    }

    /**
     * 获取一阶分类
     */
    public static function getLevelOneValue($where = [], $select = 'type_id,type_pid,type_name', $asArray = true)
    {
        $levelOne = static::find()->select($select)->asArray($asArray)->where($where)->orderBy('type_index')->andWhere(['type_pid' => 0, 'is_valid' => 1])->all();
        if (!empty($levelOne)) {
            foreach ($levelOne as $key => $val){
                $levelOneType[$val['type_id']] = $val['type_name'];
            }
        }
        return isset($levelOneType) ? $levelOneType : null;
    }

    /**
     * 新增生成新ID
     * @param int $id 为 父级id
     */
    public function getNewId($id)
    {
        //獲取所有本階分類
        $levelAll = $this->find()->where(['type_pid' => $id]);
        $idArr = $levelAll->select('type_id')->asArray()->column();
        if ($id == 0) {
            //一级类别id从21开始  生成新id为最大id加1
            if (count($idArr) == 0) {
                $new_id = 21;
            } else {
                $new_id = max($idArr) + 1;
            }
        } else {
            if (count($idArr) == 0) {
                if (self::getNewIndex($id) < 10) {
                    //不足两位补零
                    $new_id = $id . '0' . self::getNewIndex($id);
                } else {

                    $new_id = $id . self::getNewIndex($id);
                }
            } else {
                $new_id = max($idArr) + 1;
            }
        }
        return $new_id;
    }

    /**
     * 新增生成新type_index
     * @param int $id 为 父级id
     */
    public function getNewIndex($id)
    {
        //獲取所有本階分類
        $levelAll = $this->find()->where(['type_pid' => $id]);
        $indexArr = $levelAll->select('type_index')->asArray()->column();

        ////从21开始 得到一级类别id数组  生成新id为最大id加1
        if ($id == 0) {
            if (count($indexArr) == 0) {
                $index = 21;
            } else {
                $index = max($indexArr) + 1;
            }
        } else {
            if (count($indexArr) == 0) {
                $index = 1;
            } else {
                $index = max($indexArr) + 1;
            }
        }
        return $index;
    }

    /**
     * 新增生成新type_no
     * @param int $id 为 父级id
     */
    public function getNewNo($id)
    {

        if ($id == 0) {
            $no = null;
        } else {
            if ($this::getNewIndex($id) < 10) {
                $no = $this::getModel($id)->type_no . "0" . $this::getNewIndex($id);
            } else {
                $no = $this::getModel($id)->type_no . $this::getNewIndex($id);
            }
        }
        return $no;
    }

    /**
     * 根據分類ID獲取子分類
     * @param $id
     * @param string $select
     * @param bool $asArray
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getChildrenByParentId($id, $select = 'type_id,type_pid,type_name', $asArray = true)
    {
        return static::find()->select($select)->asArray($asArray)->orderBy('type_index')->where(['type_pid' => $id, 'is_valid' => 1])->all();
    }

    public function getParent()
    {
        return $this->hasOne(PdProductType::className(), ['type_id' => "type_pid"]);
    }

    /**
     * 關聯創建人
     */
    public function getBuildStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
    }

    /**
     * 關聯更新人
     */
    public function getUpdateStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'update_by']);
    }

    /**
     * 獲取模型
     * @param $id
     * @return null|static
     * @throws \Exception
     */
    public static function getModel($id)
    {
        if ($model = PdProductType::findOne($id)) {
            return $model;
        } else {
            throw new NotFoundHttpException("页面未找到");
        }
    }

    public function behaviors()
    {
        return [
    
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ],
                'value' => function () {
                    return date("Y-m-d H:i:s", time());          //賦值的值來源,如不同複寫
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_pid', 'type_name', 'type_level', 'type_no', 'type_index', 'is_valid', 'is_special', 'status'], 'required'],
            [['type_id', 'type_pid', 'type_index', 'type_level', 'is_valid', 'is_special', 'create_by', 'update_by', 'status'], 'integer'],
            [['create_at', 'update_at', 'type_description', 'type_no'], 'safe'],
            [['type_name', 'type_keyword', 'type_title'], 'string', 'max' => 20],
            [['type_picture'], 'string', 'max' => 255],
        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type_id' => 'ID',
            'type_pid' => '父类id',
            'type_name' => '类别名',
            'type_index' => '序号',
            'type_level' => '级别',
            'type_picture' => '图片URL',
            'is_valid' => '是否有效',
            'status' => '状态',
            'is_special' => '设备专区',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'update_at' => '修改时间',
            'update_by' => '修改人',
            'type_title' => '标题',
            'type_no' => '编码',
            'type_keyword' => '关键词',
            'type_description' => '描述',
        ];
    }
}
