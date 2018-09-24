<?php
/**
 * User: F3859386
 * Date: 2017/3/21
 * Time: 16:48
 */
namespace app\modules\system\controllers;

use app\models\User;
use app\modules\system\models\AuthItem;
use app\modules\system\models\search\AuthoritySearch;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class DisplayListController extends \app\controllers\BaseController
{
    private $_url = "system/display-list/";  //对应api控制器URL


    /**
     * 列表
     * @return string
     */
    public function actionIndex(){
        return $this->render('index',[
            'rule'=>Json::encode($this->getRules()),
            'tree'=>$this->getTree()
            ]);
    }


    /**
     * 编辑
     * @return string
     */
    public function actionEdit(){
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "edit";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('动态列设置:'.$data['msg']);
                return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
    }

    public function actionGetField($id='',$rule=''){
        $url = $this->findApiUrl() . $this->_url . "get-field?id=".$id."&rule=".$rule;
        $dataProvider = $this->findCurl()->get($url);
        return $dataProvider;
    }

    /**
     * 获取菜单树
     * @return mixed
     */
    private function getTree(){
        $url = $this->findApiUrl() . $this->_url . "get-tree";
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    /**
     * 获取所有角色
     * @return array|\yii\db\ActiveRecord[]
     */
    private function getRules(){
        $arr=AuthItem::find()->select('name,title')->where(['type'=>1])->asArray()->all();
        foreach($arr as $key=>$val){
            $arr[$key]['title']=htmlspecialchars($arr[$key]['title']);
        }
        return $arr;
    }

}