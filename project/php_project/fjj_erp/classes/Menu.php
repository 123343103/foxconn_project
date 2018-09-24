<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2017/1/6
 * Time: 13:47
 */
namespace app\classes;

use yii;
use yii\helpers\Url;

class Menu
{
    //菜单
    public static function generateMenu()
    {
        $menu = "../file/menu.php";
        $menu = include $menu;
        $str = '<ul id="menu" style="" class="first-menu">';
        $user = Yii::$app->user->identity->user_id;
        if (empty($user)) {
            return false;
        }
        foreach ($menu as $keys => $value) {
            $three = '';
            foreach ($value as $key => $val) {
                $threeMenu = '';
                foreach ($val as $k => $v) {
                    $url = '';
                    foreach ($v as $j => $i) {
                        if (empty(Yii::$app->user->identity->is_supper) && (empty($i['url']) || (!Yii::$app->authManager->checkAccess($user, $i['url']) && !self::isAllow($i['url'])))) {
                            unset($menu[$keys][$key][$k][$j]);
                            continue;
                        }
                        $url .= '<li><i class=\'icon-stop\'></i><a href=' . Url::to([$i['url']]) . '>' . $i['title'] . '</a></li>';
                    }
                    if (empty($menu[$keys][$key][$k])) {
                        unset($menu[$keys][$key][$k]);
                        continue;
                    }
                    $threeMenu .= '<ul class="three-menu">' . $url . '</ul>';
                }
                if (empty($menu[$keys][$key])) {
                    unset($menu[$keys][$key]);
                    continue;
                }
                $three .= '<li>' . $key . $threeMenu . '</li>';
            }
            if (empty($menu[$keys])) {
                unset($menu[$keys]);
                continue;
            }
            $str .= '<li>' . $keys . '<ul class="second-menu" style="display: none;">' . $three . '</ul></li>';
        }
        $str .= '</ul>';
        return $str;
//        $view = YII::$app->view;
//        $view->params['menu'] = $str;
    }

    public static function isAction($url)
    {
        if (Yii::$app->user->identity->is_supper == 1) {
            return true;
        }
        $user = Yii::$app->user->identity->user_id;
        return Yii::$app->authManager->checkAccess($user, $url);
    }

    /**
     * @param $url
     * @param $btn
     * @return bool
     * 检查按钮是否有权限
     */
    public static function isAllow($url, $btn = null)
    {
        if (Yii::$app->user->identity->is_supper == 1) {
            return true;
        }
        $user = Yii::$app->user->identity->user_id;
        return Yii::$app->rbac->checkAllow($user, $url, $btn);
    }
}