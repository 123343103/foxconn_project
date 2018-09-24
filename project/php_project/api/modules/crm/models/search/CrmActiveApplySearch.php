<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\search;
use app\classes\Trans;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveApply;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\show\CrmActiveApplyShow;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use app\modules\common\models\BsCompany;
//活动报名搜索模型
class CrmActiveApplySearch extends CrmActiveApply
{
    //搜索活动报名-郭文聪
    public function searchApply($params)
    {
        $query=(new Query())->select([
            CrmActiveApply::tableName().'.acth_id',//活动报名id
            CrmActiveApply::tableName().'.acth_code',//编码
            CrmActiveApply::tableName().'.acth_name',//报名人姓名
            CrmActiveApply::tableName().'.acth_date',//报名日期
            CrmActiveApply::tableName().'.acth_position',//职位
            CrmActiveApply::tableName().'.acth_phone',//手机号
            CrmActiveApply::tableName().'.acth_email',//邮箱
            CrmActiveApply::tableName().'.acth_remark',//备注
            "(CASE ".CrmActiveApply::tableName().".acth_ismeal WHEN '0' THEN '否' WHEN '1' THEN '是' ELSE '否' END) AS isEat",//是否用餐
            "(CASE ".CrmActiveApply::tableName().".acth_ispay WHEN '0' THEN '否' WHEN '1' THEN '是' ELSE '否' END) AS isPay",//是否需缴费
            "(CASE ".CrmActiveApply::tableName().".acth_payflag WHEN '0' THEN '未缴费' WHEN '1' THEN '已缴费' ELSE '否' END) AS isYetPay",//是否已缴费
            "(CASE ".CrmActiveApply::tableName().".acth_isbill WHEN '0' THEN '否' WHEN '1' THEN '是' ELSE '否' END) AS isBill",//是否开票
            "(CASE ".CrmActiveApply::tableName().".acth_ischeckin WHEN '0' THEN '未签到' WHEN '1' THEN '已签到' ELSE '否' END) AS isCheckIn",//是否已签到
            CrmActiveName::tableName().'.actbs_id',//活动名称id
            CrmActiveName::tableName().'.actbs_start_time',//活动开始时间
            CrmActiveName::tableName().'.actbs_end_time',//活动结束时间
            CrmActiveName::tableName().'.actbs_name',//活动名称
            'bp2.bsp_svalue acttype_name',//类型名
            CrmCustomerInfo::tableName().'.cust_id',//客户ID
            CrmCustomerInfo::tableName().'.cust_sname',//客户全称
            CrmCustomerInfo::tableName().'.cust_shortname',//公司简称-发送邮件时使用
            CrmCustomerInfo::tableName().'.member_name',//姓名-发送邮件时使用
            CrmCustomerInfo::tableName().'.cust_contacts',//姓名-发送邮件时使用
            CrmCustomerInfo::tableName().'.cust_position',//职位-发送邮件时使用
            CrmCustomerInfo::tableName().'.cust_email',//邮箱-发送邮件时使用
            CrmCustomerInfo::tableName().'.cust_tel2',//电话-发送邮件时使用
            'bp1.bsp_svalue AS joinIdentity',//参会身份
            'IFNULL('.CrmActiveApply::tableName().'.update_at,'.CrmActiveApply::tableName().'.create_at) AS sort_at',
        ])->from(CrmActiveApply::tableName())
            ->leftJoin(CrmActiveName::tableName(),CrmActiveName::tableName().'.actbs_id='.CrmActiveApply::tableName().'.actbs_id')//活动名称表
            ->leftJoin(CrmActiveType::tableName(),CrmActiveType::tableName().'.acttype_id='.CrmActiveName::tableName().'.acttype_id')//活动类型表
            ->leftJoin(CrmCustomerInfo::tableName(),CrmCustomerInfo::tableName().'.cust_id='.CrmActiveApply::tableName().'.cust_id')//客戶信息表
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmActiveApply::tableName().'.acth_identity')//参会身份
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.CrmActiveType::tableName().'.acttype_name')
            ->where(['!=',CrmActiveApply::tableName().'.acth_status',CrmActiveApply::DELETE_STATUS])
            ->andWhere(['in',CrmActiveApply::tableName().'.company_id',BsCompany::getIdsArr($params['companyId'])])
            ->orderBy(['sort_at'=>SORT_DESC,CrmActiveApply::tableName().'.acth_id'=>SORT_DESC]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?'':$params['rows']
            ],
        ]);
        $query->andFilterWhere([
            CrmActiveType::tableName().'.acttype_name'=>isset($params['acttype_name'])?$params['acttype_name']:'',
            CrmActiveName::tableName().'.actbs_id'=>isset($params['actbs_id'])?$params['actbs_id']:'',
            CrmActiveApply::tableName().'.acth_ischeckin'=>isset($params['acth_ischeckin'])?$params['acth_ischeckin']:'',
        ]);
        $trans=new Trans();
        if(!empty($params['cust_sname'])){
            $query->andFilterWhere([
                'or',
                ['like',CrmCustomerInfo::tableName().'.cust_sname',$params['cust_sname']],
                ['like',CrmCustomerInfo::tableName().'.cust_sname',$trans->t2c($params['cust_sname'])],
                ['like',CrmCustomerInfo::tableName().'.cust_sname',$trans->c2t($params['cust_sname'])]
            ]);
        }
        if(!empty($params['acth_name'])){
            $query->andFilterWhere([
                'or',
                ['like',CrmActiveApply::tableName().'.acth_name',$params['acth_name']],
                ['like',CrmActiveApply::tableName().'.acth_name',$trans->t2c($params['acth_name'])],
                ['like',CrmActiveApply::tableName().'.acth_name',$trans->c2t($params['acth_name'])]
            ]);
        }
        if(!empty($params['actbs_start_time'])){
            $query->andFilterWhere(['>=',CrmActiveName::tableName().'.actbs_start_time',date('Y-m-d H:i:s',strtotime($params['actbs_start_time']))]);
        }
        if(!empty($params['actbs_end_time'])){
            $query->andFilterWhere(['<=',CrmActiveName::tableName().'.actbs_end_time',date('Y-m-d H:i:s',strtotime($params['actbs_end_time'].'+1 day'))]);
        }
        //发信息、发邮件在继续添加中过滤已选中的客户
        if(isset($params["customers"])){
            $query->andFilterWhere(["not",["in",CrmCustomerInfo::tableName().".cust_id",explode(",",$params["customers"])]]);
        }
        if(isset($params['keywords'])){
            $query->andFilterWhere([
                "or",
                ["like",CrmCustomerInfo::tableName().".cust_sname",$params['keywords']],
                ["like",CrmCustomerInfo::tableName().".cust_sname",$trans->t2c($params['keywords'])],
                ["like",CrmCustomerInfo::tableName().".cust_sname",$trans->c2t($params['keywords'])],
                ["like",CrmCustomerInfo::tableName().".cust_shortname",$params['keywords']],
                ["like",CrmCustomerInfo::tableName().".cust_shortname",$trans->t2c($params['keywords'])],
                ["like",CrmCustomerInfo::tableName().".cust_shortname",$trans->c2t($params['keywords'])],
                ["like",CrmCustomerInfo::tableName().".cust_contacts",$params['keywords']],
                ["like",CrmCustomerInfo::tableName().".cust_contacts",$trans->t2c($params['keywords'])],
                ["like",CrmCustomerInfo::tableName().".cust_contacts",$trans->c2t($params['keywords'])],
                ["like",CrmCustomerInfo::tableName().".cust_email",$params['keywords']],
            ]);
        }
//        file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 各个列表页下活动信息列表
     */
    public function search($params)
    {
        $query=CrmActiveApplyShow::find()->where(['!=','acth_status',CrmActiveApply::DELETE_STATUS])->andWhere(['cust_id'=>$params['id']]);
        if(isset($params['rows'])){
            $pageSize=$params['rows'];
        }else{
            $pageSize=10;
        }
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'pagination'=>[
                'pageSize'=>$pageSize
            ],
            'sort'=>[
                'defaultOrder'=>[
                    'create_at'=>SORT_DESC
                ]
            ]
        ]);
//        $query->joinWith('customer');
        return $dataProvider;
    }
}