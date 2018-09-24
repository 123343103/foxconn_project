<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;

class RptTemplateController extends BaseController
{
    private $_url = "rpt/rpt-template/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $dataProvider = current(Json::decode($this->findCurl()->get($url)));
        foreach ($dataProvider as $k=>$v) {
            $tree[$k]['text'] = $v['rptc_name'];
            if (!empty($v['template'])) {
                foreach ($v['template'] as $kk=>$vv) {
                    $tree[$k]['nodes'][$kk]['text'] = $vv['rptt_name'];
                    if (!empty($v['rpt'])) {
                        foreach ($v['rpt'] as $rk=>$rv) {
                            if ($vv['rptt_id'] == $rv['rptt_id']) {
                                $tree[$k]['nodes'][$kk]['nodes'][]['text'] = $rv['rptd_head'].'<span  style="display:none;">'.$rv['rptd_id'].'</span>';
                            }
                        }
                    }
                }
            }
        }
        $tree = json_encode($tree);
        $tree = substr($tree,0,strlen($tree)-1);
        $tree = substr($tree,-strlen($tree)+1);
//        dumpE($tree);
        $tree = '{text:"系统所有报表",href:"test-1",nodes:['.$tree.']}';
//        $tree = '{text:"系统所有报表",nodes:[{text:"aaaa",badge:"test-1"}]}';
        return $this->render('index', [
            'tree'  =>$tree
        ]);
    }

    public function actionTest()
    {
        $connection = Yii::$app->db;
        $command = @$connection->createCommand('SELECT a FROM auth_title limit 1') or die('语法错误');
        $list = $command->queryAll();
        dumpE($list);
//        dumpE(Yii::$app->db);
        return $this->render('index', [
//            'list' => $list,
//            'stores' => $stores
        ]);


//        $lexer = new Lexer('SELECT * FROM db..tbl $');
//        $parser = new Parser($lexer->list);
//        $this->assertEquals(
//            array(
//                array('Unexpected character.', 0, '$', 22),
//                array('Unexpected dot.', 0, '.', 17),
//            ),
//            Error::get(array($lexer, $parser))
//        );

        $aa = Yii::$app->authManager->checkAccess(Yii::$app->user->identity->user_id, '/ptdt/product-dvlp/index');
            dumpE($aa);
    }
}

