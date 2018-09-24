<?php
/**
 * User: F1677929
 * Date: 2017/11/16
 */
namespace app\modules\spp\controllers;
use app\controllers\BaseActiveController;
use Yii;
use yii\data\SqlDataProvider;
use app\modules\common\models\BsCompany;
use app\classes\Trans;

/**
 * 供应商弹窗模板API控制器
 */
class SupplierPopTplController extends BaseActiveController
{
    public $modelClass='x';

    public function actionSelectSupplier()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.spp_id,
                     a.spp_code,
                     a.group_code,
                     a.spp_fname,
                     a.spp_sname
              from spp.bs_supplier a
              where a.spp_status = 3
              and a.group_code is not null
              and a.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $sql.=(int)$val.',';
        }
        $sql=trim($sql,',').')';
        //排除已选中的数据
        if(!empty($params['filters'])){
            $arr=explode('-',$params['filters']);
            $sql.=" and a.group_code not in (";
            foreach($arr as $key=>$val){
                $sql.="'".$val."',";
            }
            $sql=trim($sql,',').')';
        }
        //查询
        if(!empty($params['keyword'])){
            $trans=new Trans();//处理简体繁体
            $params['keyword']=str_replace(['%','_'],['\%','\_'],$params['keyword']);
            $queryParams[':keyword1']='%'.$params['keyword'].'%';
            $queryParams[':keyword2']='%'.$trans->c2t($params['keyword']).'%';
            $queryParams[':keyword3']='%'.$trans->t2c($params['keyword']).'%';
            $sql.=" and (a.spp_fname like :keyword1 or a.spp_fname like :keyword2 or a.spp_fname like :keyword3)";
        }
        $sql.=" order by a.spp_id desc";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>empty($queryParams)?[]:$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?false:$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }
}