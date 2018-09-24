<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/5
 * Time: 上午 09:09
 */

namespace app\classes;

use Yii;

class GetUserPermissions
{
    //获取用户所对应的商品类别
    public function GetUserCategory($staff)
    {
        $s="select u.user_id from `user` u where u.staff_id={$staff}";
        $userid= Yii::$app->getDb('db')->createCommand($s)->queryAll();
        $sql = "select ca.catg_no from pdt.bs_category ca WHERE ca.catg_id in(
                select d.ctg_pkid from r_user_ctg c 
                RIGHT JOIN r_user_ctg_dt d ON c.user_id=d.user_id 
                where c.user_id={$userid[0]['user_id']})";
        return Yii::$app->getDb('db')->createCommand($sql)->queryAll();
    }
    //获取用户所对应的厂区信息
    public function GetUserFactory($staff)
    {
        $s="select u.user_id from `user` u where u.staff_id={$staff}";
        $userid= Yii::$app->getDb('db')->createCommand($s)->queryAll();
        $sql = "select d.area_pkid from r_user_area c RIGHT JOIN r_user_area_dt d ON c.user_id=d.user_id where c.user_id={$userid[0]['user_id']}";
        return Yii::$app->getDb('db')->createCommand($sql)->queryAll();
    }

}