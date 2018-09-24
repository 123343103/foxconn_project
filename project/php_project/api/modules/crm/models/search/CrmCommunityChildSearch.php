<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/19
 * Time: 上午 08:35
 */
namespace app\modules\crm\models\search;

use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCommunityChild;
use app\modules\crm\models\show\CrmCommunityShow;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCommunity;
class CrmCommunityChildSearch extends CrmCommunityChild {
    public function fields()
    {
        $fields=parent::fields();
        $fields["commu_source"]=function(){
            $data=CrmCommunity::getPlanFroms();
            return isset($data[$this->commu->commu_source])?$data[$this->commu->commu_source]:"";
        };
        $fields["cust_cmp_name"]=function(){
            return $this->commu->cust_cmp_name;
        };
        $fields["cust_name"]=function(){
            return $this->commu->cust_name;
        };
        $fields["cust_contcats"]=function(){
            return $this->commu->cust_cmp_name;
        };
        return $fields;
    }

    public static function search($params=""){
        $query=self::find()->where(["commu_ID"=>$params["id"]]);
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);
        return $dataProvider;
    }

}
?>