<?php
namespace app\controllers;
use app\models\EditPwdForm;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii\web\Controller;
use Yii;
use app\models\LoginForm;

/**
 * 登錄控制器
 * User: F3858995
 * Date: 2016/9/20
 */

class LoginController extends Controller
{
    /**
     * 登录
     * @return string|\yii\web\Response
     */
    public function actionLogin(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (!Yii::$app->user->identity->first_login) {
                $model_pwd = new EditPwdForm();
                if( $model_pwd->load(Yii::$app->request->post())){
                    Yii::$app->user->identity->user_pwd = Yii::$app->security->generatePasswordHash($model_pwd->newPwd);
                    Yii::$app->user->identity->first_login = '1';
                    if (Yii::$app->user->identity->save()){
                        return \yii\helpers\Json::encode(['msg'=>"修改密码成功","flag"=>1,"url"=>\yii\helpers\Url::to(["index"])]);
                    }
                    return \yii\helpers\Json::encode(['msg'=>"修改密码失败","flag"=>0]);
                }
                $this->layout = '@app/views/layouts/ajax';
                return $this->render('login',['model'=>$model,'model_pwd'=>$model_pwd,'fancybox'=>1]);
            }
            SystemLog::addLog('登录');
            return $this->redirect(['/index/index']);
        }

        return $this->renderPartial('login', [
            'model' => $model,
            'fancybox'=>0
        ]);

    }

    /**
     * 登出
     * @return \yii\web\Response
     */
    public function actionLoginOut(){
        if( Yii::$app->user->logout() ){
            return $this->redirect(['/login/login']);
        }
    }

    /**
     * 驗證碼
     * @return array
     */
    public function actions()
    {
        return  [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'backColor'=>0xffffff,//背景颜色
                'maxLength' => 6, //最大显示个数
                'minLength' => 6,//最少显示个数
                'padding' => 5,//间距
                'height'=>40,//高度
                'width' => 140,  //宽度
                'foreColor'=>0x1F7ED0,     //字体颜色
                'offset'=>0.5,        //设置字符偏移量 有效果
                //'controller'=>'login',        //拥有这个动作的controller
            ],
        ];
    }

}
