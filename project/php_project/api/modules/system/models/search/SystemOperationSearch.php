<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/11/20
 * Time: 上午 10:51
 */
namespace app\modules\system\models\search;

use app\modules\system\models\BsBtn;
use yii;
use yii\base\Model;

class SystemOperationSearch extends BsBtn{
    public $btn_name;
    public $btn_yn;
    public $btn_no;

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['btn_pkid','btn_name', 'btn_yn', 'btn_no'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $sql = "SELECT btn_pkid,btn_no,btn_name,case when btn_yn=0 then '否' else '是' end as btn_yn FROM erp.bs_btn WHERE 1=1 ";
        $this->load($params);
        if (!$this->validate()) {
            return $sql;
        }
        if (!empty($params["btn_name"])) {
            $btn_name = str_replace(' ', '', $params["btn_name"]);
            $sql = $sql . "and (btn_name like '%$btn_name%' or btn_no like '%$btn_name%') ";
        }
        if (!empty($params["btn_yn"])) {
            $btn_yn=$params["btn_yn"];
            if($btn_yn==3){
                $sql = $sql . "and btn_yn=0 ";
            }
            if ($btn_yn==1) {
                $sql = $sql . "and btn_yn=1 ";
            }
        }
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

}