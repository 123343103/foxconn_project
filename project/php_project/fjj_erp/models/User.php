<?php

namespace app\models;

use app\modules\common\models\BsCompany;
use Yii;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\HrOrganization;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\web\IdentityInterface;

/**
 * user 模型
 * F3858995
 * 2016.9.12
 *
 * @property integer $user_id
 * @property string $user_account
 * @property string $user_pwd
 * @property string $security_level
 * @property integer $staff_id
 * @property integer $user_status
 * @property string $start_at
 * @property string $end_at
 * @property string $user_type
 * @property string $creater
 * @property string $create_at
 * @property string $updater
 * @property string $update_at
 * @property integer $is_login
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 0;
    const STATUS_SEALED = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_account', 'user_pwd', 'start_at', 'end_at','company_id','username_describe'], 'required'],
            [['staff_id', 'user_status', 'is_login','is_supper'], 'integer'],
            [['first_login'], 'string', 'max' => 2],
            [['user_account'], 'unique', 'message' => '{attribute}已存在'],
            [['user_account'],'match','not'=>true,'pattern'=>'/[\x{4e00}-\x{9fa5}]+/u','message'=>'用户名不能包含中文'],
            [['start_at', 'end_at', 'create_at', 'update_at', 'username_describe'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '主键ID',
            'user_account' => '帐号',
            'user_pwd' => '密码',
            'security_level' => '安全等级',
            'staff_id' => '员工',
            'user_status' => '用户状态',
            'user_type' => '用户类型',
            'first_login' => '是否第一次登陆',
            'creater' => '创建人',
            'start_at' => '启用日期',
            'end_at' => '结束日期',
            'create_at' => '创建时间',
            'updater' => '更新人',
            'update_at' => '更新时间',
            'is_login' => '是否已登录',
            'username_describe' => '用户描述',
            'company_id'=>'公司',
            'is_supper'=>'是否超级管理员',
        ];
    }

    /**
     * 验证密码
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->user_pwd);
    }
    /**
     * 验证用户名
     * @param $password
     * @return bool
     */
    public function validateString()
    {
        $user_account = User::findByUsername($this->user_account);
        return preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$user_account);
    }

    /**
     * 根据账号查找用户
     * @param $username
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUsername($username)
    {

        return self::findOne(['user_account' => $username]);
    }

    /**
     * 根据ID查找
     * @param int|string $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findIdentity($id)
    {

        return static::findOne(['user_id' => $id, 'user_status' => self::STATUS_ACTIVE]);
    }

    /**
    * 是否管理员
    * @param int|string $id
    * @return boolean
    */
    public static function isSupper($id)
    {

        $model=static::findOne(['user_id' => $id]);
        return !empty($model->is_supper)?true:false;

    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * 取得主键
     * @return mixed
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function saveUser($roles)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $auth = Yii::$app->authManager;
        if ($this->save() !== false) {
            $auth->revokeAll($this->user_id);
            foreach ($roles as $key => $val) {
                $role = $auth->getRole($val);
                if (!$auth->assign($role, $this->user_id)) {
                    $transaction->rollBack();
                    return false;
                };
            }
            $transaction->commit();
            return true;
        }
        return false;

    }


    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 关联员工
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => "staff_id"]);
    }

    //关联公司
    public function getCompany()
    {
        return $this->hasOne(BsCompany::className(), ['company_id' => "company_id"]);
    }

    public function behaviors()
    {
        return [
            "StaffBehavior" => [
                "class" => StaffBehavior::className(),
                'attributes'=>[
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_by'],   //插入时自动赋值字段
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_by']  //更新时自动赋值字段
                ]
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => 'create_at',
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'update_at'
                ],
                'value' => function () {
                    return date("Y-m-d H:i:s", time());
                }
            ]
        ];
    }

}
