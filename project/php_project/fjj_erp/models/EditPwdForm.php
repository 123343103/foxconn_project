<?php
namespace app\models;
use yii\base\Model;
/**
 *  F3858995
 *  2016/10/20
 */
class EditPwdForm extends  Model
{
    public $username;
    public $oldPwd;
    public $newPwd;
    public $newPwdV;

    public function rules()
    {
        return [
            [['username','oldPwd','newPwd','newPwdV'],'required'],
            ['newPwdV', "theSame"],
            ['oldPwd', "validatePassword"],
            [['newPwd','newPwdV'], 'match','pattern'=>"/^(?!(\d+|[a-zA-Z]+)$)[\da-zA-Z]{8,16}$/",'message'=>'8~16位数字和字母组合']
        ];
    }

    public function theSame($attribute, $params)
    {
        if ($this->newPwdV != $this->newPwd) {
            $this->addError($attribute, "两次输出密码必须相同");
        }
    }
    public function validatePassword($attribute,$params)
    {
            $user=User::findByUsername($this->username);
            if (!$user->validatePassword($this->oldPwd)) {
                $this->addError($attribute, '旧密碼錯誤');
            }
    }

    public function editPwd(){
        $user=User::findByUsername($this->username);
        $user->user_pwd=\Yii::$app->security->generatePasswordHash($this->newPwd);
        $user->first_login='1';
        if($user->validate() && $user->save()){
            return true;
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'oldPwd' => '旧密码',
            'newPwd' =>"新密碼",
            'newPwdV'=>"重复新密码"
        ];
    }
}