<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/21
 * Time: 下午 04:58
 */

namespace app\modules\system\models\search;


use app\modules\system\models\BsMenu;
use app\classes\Trans;

class MenuPowerSearch extends BsMenu
{


    public function Search($param)
    {
        $p_menu_pkid = 0;
        $i = 0;
        $dataProvider = [];
        $trans = new Trans();//繁简转换
        if (empty($param)) {

            $parent = BsMenu::find()->where(['p_menu_pkid' => $p_menu_pkid])->asArray()->all();
            foreach ($parent as $value) {
                $menuData['parent'] = $value;
                $child = BsMenu::find()->where(['p_menu_pkid' => $value['menu_pkid']])->asArray()->all();
                $menuData['childe'] = $child;
                $dataProvider[$i] = $menuData;
                $i++;
            }
        } else {
            $parent = BsMenu::find()->where(['p_menu_pkid' => $p_menu_pkid])->andFilterWhere(['menu_name' => $trans->t2c(trim($param['MenuSearch']['menu_name']))])
                ->andFilterWhere(['yn' => $param['MenuSearch']['yn']])->asArray()->all();
            if (count($parent) > 0) {
                foreach ($parent as $value) {
                    $menuData['parent'] = $value;
                    $child = BsMenu::find()->where(['p_menu_pkid' => $value['menu_pkid']])->asArray()->all();
                    $menuData['childe'] = $child;
                    $dataProvider[$i] = $menuData;
                    $i++;
                }

            } else {

                $child = BsMenu::find()->where(['menu_level' => 2])->andFilterWhere(['menu_name' => $trans->t2c(trim($param['MenuSearch']['menu_name']))])->andFilterWhere(['yn' => $param['MenuSearch']['yn']])->asArray()->all();
                if (count($child) > 0) {
                    $dataProvider[]['parent'] = "";
                    $dataProvider[]['childe'] = $child;
                }
            }

        }
        return $dataProvider;
    }

    public function TreeTable($param)
    {
        $trans = new Trans();
        $Html = '<table class="table" id="treeTable">';
        $Html .= '<thead>';
        $Html .= '<tr level="1" pid=""><th>菜单名称</th><th>URL</th><th>有效否</th><th>操作</th></tr>';
        $Html .= '</thead>';
        $Html .= '<tbody>';
        if (isset($param['MenuSearch']['menu_name']) && $param['MenuSearch']['menu_name'] == "0") {

            $Html .= '<tr><td style=\'color:red\' colspan=\'4\'>没有相关数据!</td></tr>';
        } else {
            if (!empty($param['MenuSearch']['menu_name']) || (isset($param['MenuSearch']['yn']) && !empty($param['MenuSearch']['menu_name']))) {
                $data = BsMenu::find()->andFilterWhere(['menu_name' => trim($trans->t2c($param['MenuSearch']['menu_name'])), 'yn' => $param['MenuSearch']['yn']])->all();
                if ($data) {
                    foreach ($data as $value) {
                        $isChilde = BsMenu::find()->andWhere(['p_menu_pkid' => $value->attributes['menu_pkid']])->all();
                        if ($isChilde) {
                            $Html .= '<tr pid=\' ' . $value->attributes['p_menu_pkid'] . '\' id=\'' . $value->attributes['menu_pkid'] . '\' level=\'' . $value->attributes['menu_level'] . '\'><td style=\'width:40%\'><img src=\'../../img/icon/111_u821.png\' class=\'showChilde\' style=\'width:14px;height:14px\'>&nbsp' . $trans->t2c($value->attributes['menu_name']) . '</td><td style=\'width:30%\'>' . $value->attributes['menu_url'] . '</td><td style=\'width:10%\'>' . $value->attributes['yn'] . '</td><td style=\'width:20%\'><a class=\'update\'>修改</a></td></tr>';
                            $Html .= self::getTree($value->attributes['menu_pkid']);
                        } else {
                            $Html .= '<tr pid=\' ' . $value->attributes['p_menu_pkid'] . '\' id=\'' . $value->attributes['menu_pkid'] . '\' level=\'' . $value->attributes['menu_level'] . '\'><td style=\'width:40%\'>' . $trans->t2c($value->attributes['menu_name']) . '</td><td style=\'width:30%\'>' . $value->attributes['menu_url'] . '</td><td style=\'width:10%\'>' . $value->attributes['yn'] . '</td><td style=\'width:20%\'><a class=\'update\'>修改</a>&nbsp&nbsp&nbsp<a class=\'set\'>操作设置</a></td></tr>';
                        }
                    }
                } else {
                    $Html .= '<tr><td style=\'color:red\' colspan=\'4\'>没有相关数据!</td></tr>';
                }
            } else if (empty($param['MenuSearch']['menu_name']) && (isset($param['MenuSearch']['yn']) && $param['MenuSearch']['yn'] != "")) {
                $treeHtml = self::getTreeTwo(0, $param['MenuSearch']['yn']);
                if (empty($treeHtml)) {
                    $Html .= '<tr><td style=\'color:red\' colspan=\'4\'>没有相关数据!</td></tr>';
                } else {
                    $Html .= $treeHtml;
                }
            } else {

                $Html .= self::getTree();
            }
        }
        $Html .= '</tbody>';
        $Html .= '</table>';
        return $Html;
    }

    //頁面初始化
    public function getTree($pid = 0)
    {
        $trans = new Trans();
        $tree = self::find()->andWhere(['p_menu_pkid' => $pid])->all();
        static $str = "";

        foreach ($tree as $value) {
            $left = 130 + 30 * $value['menu_level'];
            $childs = self::find()->where(['p_menu_pkid' => $value['menu_pkid']])->one();
            if ($value['menu_level'] == 1) {
                $str .= "<tr style=\"display:none\" pid=\" " . $value['p_menu_pkid'] . " \" id=\" " . $value['menu_pkid'] . " \" level=\" " . $value['menu_level'] . "\"><td style=\"padding-left: " . $left . "px;width: 40%;text-align: left;\"><img src=\"../../img/icon/111_u821.png\" class=\"showChilde\" style=\"width:14px;height:14px\">&nbsp" . $trans->t2c($value['menu_name']) . "</td><td style=\"width:30%\">" . $value['menu_url'] . "</td><td style=\"width:10%\">" . $value['yn'] . "</td><td style=\"width:20%\"><a class=\"update\">修改</a></td></tr>";
            } else {
                if ($childs) {
                    $str .= "<tr style=\"display:none\" pid=\" " . $value['p_menu_pkid'] . " \" id=\" " . $value['menu_pkid'] . " \" level=\" " . $value['menu_level'] . "\"><td style=\"padding-left: " . $left . "px;width: 40%;text-align: left;\"><img src=\"../../img/icon/111_u821.png\" class=\"showChilde\" style=\"width:14px;height:14px\">&nbsp" . $trans->t2c($value['menu_name']) . "</td><td style=\"width:30%\">" . $value['menu_url'] . "</td><td style=\"width:10%\">" . $value['yn'] . "</td><td style=\"width:20%\"><a class=\"update\">修改</a></td></tr>";
                } else {
                    $str .= "<tr style=\"display:none\" pid=\" " . $value['p_menu_pkid'] . " \" level=\" " . $value['menu_level'] . "\" id=\" " . $value['menu_pkid'] . "\" updatype=\"0\" name=\" " . $value['menu_name'] . "\"><td style=\"padding-left: " . $left . "px;width: 40%;text-align: left;\">" . $trans->t2c($value['menu_name']) . "</td><td style=\"width:30%\">" . $value['menu_url'] . "</td><td style=\"width:10%\">" . $value['yn'] . "</td><td style=\"width:20%\"><a class=\"update\">修改</a>&nbsp&nbsp&nbsp<a class=\"set\">操作设置</a></td></tr>";
                }
            }
            if ($childs) {
                self::getTree($value['menu_pkid']);
            }
        }
        return $str;
    }

    public function getTreeTwo($pid = 0, $yn = "")
    {
        $trans = new Trans();

            $tree = self::find()->andWhere(['p_menu_pkid' => $pid])->andFilterWhere(['yn' => $yn])->all();
        static $str = "";

        foreach ($tree as $value) {
            $left = 130 + 30 * $value['menu_level'];
            $childs = self::find()->where(['p_menu_pkid' => $value['menu_pkid']])->one();
            if ($value['menu_level'] == 1) {
                $str .= "<tr style=\"display:none\" pid=\" " . $value['p_menu_pkid'] . " \" id=\" " . $value['menu_pkid'] . " \" level=\" " . $value['menu_level'] . "\"><td style=\"padding-left: " . $left . "px;width: 40%;text-align: left;\"><img src=\"../../img/icon/111_u821.png\" class=\"showChilde\" style=\"width:14px;height:14px\">&nbsp" . $trans->t2c($value['menu_name']) . "</td><td style=\"width:30%\">" . $value['menu_url'] . "</td><td style=\"width:10%\">" . $value['yn'] . "</td><td style=\"width:20%\"><a class=\"update\">修改</a></td></tr>";
            } else {
                if ($childs) {
                    $str .= "<tr style=\"display:none\" pid=\" " . $value['p_menu_pkid'] . " \" id=\" " . $value['menu_pkid'] . " \" level=\" " . $value['menu_level'] . "\"><td style=\"padding-left: " . $left . "px;width: 40%;text-align: left;\"><img src=\"../../img/icon/111_u821.png\" class=\"showChilde\" style=\"width:14px;height:14px\">&nbsp" . $trans->t2c($value['menu_name']) . "</td><td style=\"width:30%\">" . $value['menu_url'] . "</td><td style=\"width:10%\">" . $value['yn'] . "</td><td style=\"width:20%\"><a class=\"update\">修改</a></td></tr>";
                } else {
                    $str .= "<tr style=\"display:none\" pid=\" " . $value['p_menu_pkid'] . " \" level=\" " . $value['menu_level'] . "\" id=\" " . $value['menu_pkid'] . "\" updatype=\"0\" name=\" " . $value['menu_name'] . "\"><td style=\"padding-left: " . $left . "px;width: 40%;text-align: left;\">" . $trans->t2c($value['menu_name']) . "</td><td style=\"width:30%\">" . $value['menu_url'] . "</td><td style=\"width:10%\">" . $value['yn'] . "</td><td style=\"width:20%\"><a class=\"update\">修改</a>&nbsp&nbsp&nbsp<a class=\"set\">操作设置</a></td></tr>";
                }
            }
            if ($childs) {
                self::getTreeTwo($value['menu_pkid']);
            }
        }
        return $str;
    }
}