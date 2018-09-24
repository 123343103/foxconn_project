<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseController;
use app\modules\common\tools\SimpleTradition;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class RptManageController extends BaseController
{
    private $_url = "rpt/rpt-manage/";

    public function actionIndex()
    {
        $tree = $this->getTree();
        $tree = json_encode($tree);
        $tree = substr($tree,0,strlen($tree)-1);
        $tree = substr($tree,-strlen($tree)+1);
//        dumpE($tree);
        $tree = '{text:"系统所有报表",nodes:['.$tree.']}';
//        $tree = '{text:"系统所有报表",nodes:[{text:"aaaa",badge:"test-1"}]}';
        $template = $this->getTemplate();
        $category = json_decode($this->getCategory(),true);
        $roles = Yii::$app->authManager->getRoles();
//        dumpE($category);
        return $this->render('index', [
            'tree'  => $tree,
            'template' => $template,
            'category' => $category,
            'roles' => $roles
        ]);
    }

    // 保存模板和参数
    public function actionSaveAll() {
        $post = Yii::$app->request->post();
//        dumpE($post);
        $url = $this->findApiUrl().$this->_url."save-all";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $res = json_decode($curl->post($url),true);
//        dumpE($curl->post($url));
        if ($res['status']==0) {
            return Json::encode(["msg" => $res['msg'], "flag" => 0]);
        } else {
            return Json::encode(["msg" => $res['msg'], "flag" => 1, "url" => Url::to(['index'])]);
        }
    }

    // 纯sql校验解析
    public function actionCheckSql()
    {
        $sqlStr = Yii::$app->request->get();
//        return json_encode($sqlStr);
        if (!empty($sqlStr)) {
            // 正则验证  ^(\s*\bselect\b).*
            $filter = json_decode($this->sqlFilter($sqlStr['sql']),true);
//            return json_encode($filter);
            if ($filter['flag']) {
                $parser = new \PHPSQLParser();
                $parsed = $parser->parse($sqlStr['sql'],true);
//                dumpE($parsed['WHERE']);
                // 合并having子句中参数
//                if (!empty($parsed['HAVING']))
                // 暂时只支持两层 按顺序： 参数 操作符 常量
                if (!empty($parsed)) {
                    if (!empty($parsed['WHERE'])){
                        $j = 0;
                        foreach ($parsed['WHERE'] as $k=>$v) {
                            if (mb_eregi('.*function.*',$v['expr_type'])) {
                                $column = '';
                                foreach ($v['sub_tree'] as $kk=>$vv) {
                                    if ($vv['expr_type']=='colref') {
                                        $column = !empty($column) ? $column.',' : $column;
                                        $column = $column.$vv['base_expr'];
                                    }
                                }
                                $param[$j]['key'] = $v['base_expr'].'('.$column.')';
                                $param[$j]['type'] = $v['base_expr'];
//                                $j++;
                                continue;
                            } elseif(mb_eregi('.*expression.*',$v['expr_type'])){
                                foreach ($v['sub_tree'] as $kk=>$vv) {
                                    if ($vv['expr_type']=='colref') {
                                        $param[$j]['key'] = $vv['base_expr'];
                                    }
                                    if (!empty($param[$j]['key'] || !empty($param[$j]['value'])) && $vv['expr_type']=='operator') {
                                        $param[$j]['operator'] = $vv['base_expr'];
                                        $param[$j]['opt_len'] = strlen($vv['base_expr']);
                                        $param[$j]['opt_position'] = $vv['position'];
                                    }
                                    if ($vv['expr_type']=='const') {
                                        $param[$j]['value'] = $vv['base_expr'];
                                        $param[$j]['val_len'] = strlen($vv['base_expr']);
                                        $param[$j]['val_position'] = $vv['position'];
                                        $j++;
                                        continue;
                                    }
                                }
//                                $j++;
                                continue;
                            } elseif ($v['expr_type']=='colref') {
                                $param[$j]['key'] = $v['base_expr'];
                            }
                            if (!empty($param[$j]['key']) && $v['expr_type']=='operator') {
                                $param[$j]['operator'] = $v['base_expr'];
                                $param[$j]['opt_len'] = strlen($v['base_expr']);
                                $param[$j]['opt_position'] = $v['position'];
                            }
                            if ($v['expr_type']=='const') {
                                $param[$j]['value'] = $v['base_expr'];
                                $param[$j]['val_len'] = strlen($v['base_expr']);
                                $param[$j]['val_position'] = $v['position'];
                                $j++;
                            }
                        }
//                        dumpE($param);
                        return json_encode($param);
                    } else {
//                        return json_encode('缺少参数');
                        $res['flag'] = 0;
                        $res['msg'] = '缺少参数！';
                        return json_encode($res);
                    }
                } else {
//                    return json_encode('無法解析的sql語句！');
                    $res['flag'] = 0;
                    $res['msg'] = '無法解析的sql語句！';
                    return json_encode($res);
                }
            } else {
                return json_encode($filter);
            }
        }
    }

    // 内置对象或者内置对象生成的子报表预览
    public function actionPreview()
    {
        $params = Yii::$app->request->post();
        $url = $this->findApiUrl().$this->_url."preview";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($params));
        $data = json_decode($curl->post($url),true);
        $rptType=$params["RptTemplate"]["rptt_dtype"];
        switch($rptType){
            case "pie":
                $result=[
                    "series"=>[
                        [
                            "type"=>"pie",
                            "name"=>"xx",
                            "data"=>array_map(function($v){
                                $tmp=[$v['name']];
                                foreach($v as $kk=>$vv){
                                    if($kk!="name"){
                                        $tmp[]=(float)$vv;
                                        break;
                                    }
                                }
                                return $tmp;
                            },$data)
                        ]
                    ]
                ];
                return json_encode($result,JSON_UNESCAPED_UNICODE);
                break;
            case "column":
            case "line":
            case "area":
            case "scatter":
            case "bubble":
                $result=[];
                $result["xAxis"]=array_column($data,"name");
                if(count($data)>0){
                    $row=$data[0];
                    foreach($row as $k=>$v){
                        if($k!="name"){
                            $result["series"][]=array_merge(
                                ["name"=>$k],
                                [
                                    "data"=>array_map(function($v){
                                        return (float)$v;
                                    },array_column($data,$k))
                                ]
                            );
                        }
                    }
                }
                return json_encode($result,JSON_UNESCAPED_UNICODE);
                break;
        }
    }

    // 编码是否存在
    public function actionIsCodeExist()
    {
//        return json_encode('test ok');
        $code = Yii::$app->request->get();
//        return json_encode($code);
        $url = $this->findApiUrl().$this->_url.'is-code-exist';
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($code));
        $res = $curl->post($url);
        return json_encode($res);
    }

    // 获取分配用户/角色
    public function actionGetAssignList()
    {
        $url = $this->findApiUrl() . $this->_url . 'get-assign-list';
        $queryParam = Yii::$app->request->queryParams;
//        return json_encode($queryParam);
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
//        return $url;
        $list = $this->findCurl()->get($url);
//        dumpE($list);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $list;
        }
        return json_encode($list);
    }

    // 删除分配用户/角色
    public function actionDeleteAssign()
    {
        $postData = Yii::$app->request->post();
        $postData = json_decode($postData['postData']);
//        return json_encode($postData[0]);
//        return json_encode($postData);
        $url = $this->findApiUrl().$this->_url."delete-assign";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//        return $curl->post($url);
        $res = json_decode($curl->post($url),true);
//        dumpE($curl->post($url));
        if ($res['status']==0) {
            return Json::encode(["msg" => $res['msg'], "flag" => 0]);
        } else {
            return Json::encode(["msg" => $res['msg'], "flag" => 1]);
        }
    }

    // 删除报表 同时删除报表参数和分配关系
    public function actionDeleteRpt()
    {
        $id = Yii::$app->request->post();
        $url = $this->findApiUrl().$this->_url."delete-rpt";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($id));
        $res = json_decode($curl->post($url),true);
//        return $curl->post($url);
        if ($res['status']==0) {
            return Json::encode(["msg" => $res['msg'], "status" => 0]);
        } else {
            return Json::encode(["msg" => $res['msg'], "status" => 1, "url" => Url::to(['index'])]);
        }
    }

    public function getTemplate() {
//        return 'return template';
        $url = $this->findApiUrl() . $this->_url . 'get-template';
        $list = $this->findCurl()->get($url);
        $list = Json::decode($list,true);
        foreach ($list as $item) {
            $template[$item['rptt_id']] = $item['rptt_name'];
        }
        return $template;
    }

    // 获取模板/报表信息
    public function actionGetRpt() {
        $id = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . 'get-rpt?id='.$id['id'];
        $list = json_decode($this->findCurl()->get($url),true);
        $list[0]['rptt_tempsql'] = htmlspecialchars_decode($list[0]['rptt_tempsql'],ENT_QUOTES);
//        return json_encode($list[0]['rptt_tempsql']);
//
//        $list = $this->findCurl()->get($url);
        return json_encode($list);
    }

    // 获取模板参数
    public function actionGetTemplateParams()
    {
        $id = Yii::$app->request->get();
        $url = $this->findApiUrl() . $this->_url . 'get-template-params?tp_id='.$id['id'];
        $list = $this->findCurl()->get($url);
//        dumpE(json_decode($list));
        return $list;
    }

    // 分配报表
//    public function actionRptAssign()
//    {
//        $roles = Yii::$app->authManager->getRoles();
//        dumpE($roles);
//        return $this->render('rpt-assign', [
//            'roles'  => $roles,
//        ]);
//    }

    // 获取用户
    public function actionUserSearch()
    {
        $url = $this->findApiUrl() . $this->_url . 'user-search';
        $queryParam = Yii::$app->request->queryParams;
//        return json_encode($queryParam);
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
//        return $url;
        $list = $this->findCurl()->get($url);
//        dumpE($list);
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $list;
        }
        return json_encode($list);
    }

    // 保存分配用户
    public function actionAssignSave()
    {
        $post = Yii::$app->request->post();
        $post['uid'] = Yii::$app->user->id;
        //        dumpE($post);
        $url = $this->findApiUrl().$this->_url."assign-save";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
        $res = json_decode($curl->post($url),true);
//        dumpE($res);
        if ($res['status']==0) {
            return Json::encode(["msg" => $res['msg'], "flag" => 0]);
        } else {
            return Json::encode(["msg" => $res['msg'], "flag" => 1, "url" => Url::to(['index'])]);
        }
    }

    public function sqlFilter($sqlStr)
    {
        $isChPunctuation = mb_eregi('[、，？；。！‘’“”]',$sqlStr);
        $isSelect = mb_eregi('^(\s*\bselect\b).*',$sqlStr);
        $pattern = '\bdelete\b|\bupdate\b|\bdrop\b|\binsert\b|\bcreate\b|\btruncate\b|\badd\b|\balter\b|\bmodify\b|\bchange\b|\bgrant\b|\brevoke\b|\bshow\b|\bset\b|\bdescribe\b';
        $danger = mb_eregi($pattern,$sqlStr);
        if ($isSelect && !$isChPunctuation && !$danger) {
            $res['flag'] = 1;
            $res['msg'] = '通过过滤验证';
            return json_encode($res);
        } else {
            $res['flag'] = 0;
            $res['msg'] = '禁止非法操作！';
            return json_encode($res);
        }
    }

    // 判断数组维度
    function getDimension($arr)
    {
        if(is_array($arr)) {
            $maxDms = 0;
            foreach($arr as $k=>$v)
            {
                $dms = $this->getDimension($v);
                if( $dms > $maxDms) $maxDms = $dms;
            }
            return $maxDms + 1;
        } else {
            return 0;
        }
    }

    // 获取报表树
    public function getTree()
    {
        $url = $this->findApiUrl() . $this->_url . "get-tree";
        $list = Json::decode($this->findCurl()->get($url));
//        dumpE($list);
        $tree = [];
        foreach ($list as $k=>$v) {
            $tree[$k]['text'] = $v['rptc_name'];
            if (!empty($v['template'])) {
                foreach ($v['template'] as $kk=>$vv) {
                    if ($vv['rptt_type']==10) {
                        $tree[$k]['nodes'][$kk]['text'] = $vv['rptt_name'].'<span  style="display:none;">templateId:'.$vv['rptt_id'].'</span>';
                    } elseif ($vv['rptt_type']==12) {
                        $tree[$k]['nodes'][$kk]['text'] = $vv['rptt_name'].'<span  style="display:none;">SqlTplId:'.$vv['rptt_id'].'</span>';
                    }
                    if (!empty($vv['rpt'])) {
                        foreach ($vv['rpt'] as $rk=>$rv) {
                            if ($vv['rptt_id'] == $rv['rptt_pid']) {
                                $tree[$k]['nodes'][$kk]['nodes'][]['text'] = $rv['rptt_name'].'<span  style="display:none;">rptId:'.$rv['rptt_id'].'</span>';
                            }
                        }
                    }
                }
            }
        }
        return $tree;
//        dumpE($tree);
    }

    // 将数组处理成highchart能够显示的数据格式
    public function arrToChartData($arr)
    {
        $data = [];
        if (!empty($arr) && is_array($arr)) {
            $dimension = $this->getDimension($arr);
            if ($dimension==2) {
                foreach ($arr as $k=>$v) {
                    if (!empty($v['name'])) {
                        $series[$k]['name'] = $v['name'];
                        $val_arr = [];
                        $i = 0;
                        foreach ($v as $kk=>$vv) {
                            if ($kk != 'name') {
                                $val_arr[] = floatval($vv);
                                $xAxis[$i] = $kk;
                                $i += 1;
                            }
                        }
                        $series[$k]['data'] = $val_arr;
                    } else {
                        $val_arr = [];
                        $i = 0;
                        foreach ($v as $kk=>$vv) {
                            $val_arr[] = floatval($vv);
                            $xAxis[$i] = $kk;
                            $i += 1;
                        }
                        $series[$k]['data'] = $val_arr;
                    }
                }
            }
        }
        $data['series'] = $series;
        $data['xAxis'] = $xAxis;
        return $data;
    }

    // 获取分类
    public function getCategory()
    {
        $url = $this->findApiUrl() . $this->_url . 'get-category';
        $list = $this->findCurl()->get($url);
        return $list;
    }

    public function actionTest()
    {
        $s2t_dict = json_decode( file_get_contents(YII::$app->basePath.'/file/s2t.json') , true);
        $t2s_dict = json_decode( file_get_contents(YII::$app->basePath.'/file/t2s.json') , true);
        $str[0] = '测试简体中文转轉化為簡體换为繁体0';
        $str[1][0] = '测试简体中文转轉化為簡體换为繁体1';
        $str[1][1] = '测试简体中文转轉化為簡體换为繁体';
//        echo str_replace( array_keys($s2t_dict), array_values($s2t_dict), $str);
//        echo '<br/>';
        $str[2] = '繁體中文轉fsa啊飞飞飞化為簡體中文,測試0';
        $str[2][0] = '繁體中文轉fsa啊飞飞飞化為簡體中文,測試1';
//        echo str_replace( array_keys($t2s_dict), array_values($t2s_dict), $str);
//        echo '<br/>';
        $obj = new SimpleTradition();
//        echo $obj->t2s($str);
//        echo $obj->isSimple($str);
//        echo '<br/>';
        dumpE($obj->t2s($str));
    }
}

