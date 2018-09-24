<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/11/24
 * Time: 9:21
 */
namespace app\modules\system\controllers;

use app\controllers\BaseController;
use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class SyncCheckController extends BaseController{
    public $_url="system/sync-check/";

    public function actionCheck(){
        if(Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
//            $sql = $post['sql'];
//            $arr = explode(';',$sql);
//            dumpE(array_filter($arr));
//            $res = Yii::$app->db->createCommand($sql)->queryAll();
//            $columns=Yii::$app->db->getSchema()->getTableSchema('user')->columns;
            $url = $this->findApiUrl() . $this->_url . "check";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            $res = '';
            $reserr = '';
            foreach ($data as $key => $val){
                if(!empty($val['rows'])){
                    $res[$key] = $val['rows'];
                }else{
                    $reserr = $val;
                }
            }
            return $this->render('index',[
                'post' => $post,
                'res' => $res,
                'reserr' => $reserr
            ]);
        }
        $data = Yii::$app->ftpPath;
        $arr = [];
        foreach($data as $key => $val){
            if(is_array($val)){
                $arr[$key] = $val;
            }else{
                $arr[$key]['val'] = $val;
            }
        }
        return $this->render('index',['data'=>$arr]);
    }

}