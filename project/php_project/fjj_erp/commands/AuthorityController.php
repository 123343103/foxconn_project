<?php
namespace app\commands;

use app\modules\system\models\AuthItem;
use app\modules\system\models\AuthItemChild;
use app\modules\system\models\AuthTitle;
use Yii;
use yii\rbac\Permission;

/**
 * 命令行权限初始化
 * F3858995
 * 2016/11/1
 */
class AuthorityController extends \yii\console\Controller
{
    public function actionInit()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
        $auth = Yii::$app->authManager;
        AuthItem::deleteAll(['type' => 2]);
        AuthItemChild::deleteAll();
        AuthTitle::deleteAll();

        $file = Yii::$app->basePath ."/file/authority.php";
        $authorityArr = include $file;
        if(empty($authorityArr)){
            echo '出现异常';
            $transaction->rollBack();
        }
        foreach ($authorityArr as $key => $val) {
            foreach ($val as $k => $v) {
                if(empty($v['url'])){
                    continue;
                }
                $permission = $auth->getPermission($v['url']);
                $authority = AuthTitle::find()->where(['action_url' => $v['url']])->one();
                if (!$authority) {
                    $authority = new AuthTitle();
                    $authority->action_url = $v['url'];
                    $authority->action_title = $v['title'];
                    $authority->action_parent = $key;
                    if (!$authority->save()) {
                        throw new \Exception($authority->errors);
                    }
                }
                if (!$permission) {
                    $permission = new Permission(["name" => $v['url']]);
                    $auth->add($permission);
                }
                $auth->removeChildren($permission);
                if (isset($v['children'])) {
                    foreach ($v['children'] as $ck => $cv) {
                        if (!$child = $auth->getPermission($cv)) {
                            $child = new Permission(['name' => $cv]);
                            $auth->add($child);
                        }
                        $auth->addChild($permission, $child);
                    }
                }
            }
        }
        echo 'true';
        $transaction->commit();
        } catch (\Exception $e) {
        echo $e;
            $transaction->rollBack();
        }
    }
}