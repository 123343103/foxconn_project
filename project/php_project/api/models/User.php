<?php

namespace app\models;

use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use Yii;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\HrOrganization;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property string $user_account
 * @property string $username_describe
 * @property string $user_pwd
 * @property string $security_level
 * @property integer $staff_id
 * @property integer $company_id
 * @property integer $user_status
 * @property string $start_at
 * @property string $end_at
 * @property string $user_type
 * @property string $first_login
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 * @property integer $is_login
 * @property integer $is_supper
 * @property string $auth_key
 * @property string $access_token
 * @property string $user_mobile
 * @property string $user_email
 * @property string $other_tel
 * @property string $remarks
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
            [['user_account', 'user_pwd', 'staff_id', 'user_status', 'create_by', 'create_at'], 'required'],
            [['staff_id', 'company_id', 'user_status', 'user_type', 'is_login', 'is_supper'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['user_account', 'security_level', 'create_by', 'update_by', 'user_mobile'], 'string', 'max' => 20],
            [['username_describe', 'user_pwd', 'auth_key', 'access_token', 'remarks'], 'string', 'max' => 255],
            [['start_at', 'end_at'], 'string', 'max' => 10],
            [['first_login'], 'string', 'max' => 2],
            [['user_email', 'other_tel'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '主键ID',
            'user_account' => '帳號',
            'username_describe' => '用戶名描述',
            'user_pwd' => '密碼',
            'security_level' => '安全等级',
            'staff_id' => '关联员工ID',
            'company_id' => '公司ID',
            'user_status' => '狀態 (10:默認  20:封存 0:刪除)',
            'start_at' => '开始使用时间',
            'end_at' => '结束使用时间',
            'user_type' => '用戶類型erp.bs_pubdata.bsp_stype=&#039;YHLX&#039;，若為超級管理員，is_supper=1.',
            'first_login' => '第一次登陆（非第一次登陆 1  默认0）',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
            'is_login' => '是否已登录',
            'is_supper' => '是否超级管理员',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'user_mobile' => '手机',
            'user_email' => '邮箱',
            'other_tel' => '其他联系方式',
            'remarks' => '备注',
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
     * 根据账号查找用户
     * @param $username
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByUsername($username)
    {

        return self::findOne(['user_account' => strtolower($username)]);//用戶名不区分大小写
    }
    public static function isSupper($id)
    {

        $model=static::findOne(['user_id' => $id]);
        return !empty($model->is_supper)?true:false;

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


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    //这个是我们进行yii\filters\auth\QueryParamAuth调用认证的函数
    public function loginByAccessToken($accessToken, $type) {
        //查询数据库中有没有存在这个token
        return static::findIdentityByAccessToken($accessToken, $type);
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
    public function getPubdata()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => "user_type"]);
    }

//    public function behaviors()
//    {
//        return [
//            "StaffBehavior" => [
//                "class" => StaffBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_by'],   //插入时自动赋值字段
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_by']  //更新时自动赋值字段
//                ]
//            ],
//            'timeStamp' => [
//                'class' => TimestampBehavior::className(),
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => 'create_at',
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE => 'update_at'
//                ],
//                'value' => function () {
//                    return date("Y-m-d H:i:s", time());
//                }
//            ]
//        ];
//    }

}
