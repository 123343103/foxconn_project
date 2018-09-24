<?php

namespace app\modules\crm\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "crm_credit_maintain".
 *
 * @property integer $id
 * @property string $code
 * @property string $credit_name
 * @property string $remark
 * @property string $credit_status
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCreditMaintain extends Common
{
    const STATUS_DELETE = '0';//删除
    const STATUS_DEFAULT = '10';//启用
    const STATUS_FORBID= '20';//禁用
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_credit_maintain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['code', 'credit_name'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
            [['credit_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '编码',
            'credit_name' => '信用额度类型',
            'remark' => '备注',
            'credit_status' => '状态(0 删除 10 启用 20 禁用)',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
        ];
    }
    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'code',
                "formName" => self::tableName(),
                "model" => $this,
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }
}
