<?php
/**
 * Created by PhpStorm.
 * User: F3860942
 * Date: 2017/8/7
 * Time: 下午 05:29
 */

namespace app\modules\sale\models\search;


use app\modules\sale\models\OrdPay;
use app\modules\sale\models\show\BsBankInfoShow;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\BsBankInfo;
use yii\data\SqlDataProvider;

class BsBankInfoSearch extends BsBankInfo
{
    public $start_date;
    public $end_date;

    public $order_no;


    public function rules()
    {
        return [
            [['TXNAMT', 'TRANSID'], 'required'],
            [['TXNAMT'], 'number'],
            [['IN_DATE', 'start_date', 'end_date', 'order_no'], 'safe'],
            [['SYS_CDE', 'SYS_CN_DESC', 'FIN_NME', 'FIN_SUBAREA', 'ACCOUNTS', 'TRANSID'], 'string', 'max' => 50],
            [['FIN_CDE'], 'string', 'max' => 20],
            [['FIN_SUBAREA_CDE', 'BNK_CDE', 'TRDATE'], 'string', 'max' => 10],
            [['CORP_DESC', 'BNK_NME', 'BRANCH_NME', 'OPPACCNO', 'OPPNAME'], 'string', 'max' => 100],
            [['CUR_CDE'], 'string', 'max' => 5],
            [['INTERINFO'], 'string', 'max' => 1000],
            [['POSTSCRIPT'], 'string', 'max' => 500],
        ];
    }
//    public function scenarios()
//    {
//        // bypass scenarios() implementation in the parent class
//        return Model::scenarios();
//    }
//    public function search($params)   //搜索方法
//    {
//        $query = BsBankInfoShow::find();
//        if (isset($params['rows'])) {
//            $pageSize = $params['rows'];
//        } else {
//            if (isset($params['export'])) {
//                $pageSize = false;
//            } else {
//                $pageSize = 10;
//            }
//        }
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ]
//        ]);
//        if (empty($params['BsBankInfoSearch']['startDate'])) {
//        } else {
//            $params['BsBankInfoSearch']['startDate'] = date('Ymd', strtotime($params['BsBankInfoSearch']['startDate']));
//        }
//        if (empty($params['BsBankInfoSearch']['endDate'])) {
//        } else {
//            $params['BsBankInfoSearch']['endDate'] = date('Ymd', strtotime($params['BsBankInfoSearch']['endDate']));
//        }
//        // 从参数的数据中加载过滤条件，并验证
//        $this->load($params);
////        $query->joinWith('rBankOrders');
//        $query->andFilterWhere(['CORP_DESC' => $this->CORP_DESC])// 法人
//        ->andFilterWhere(['like', 'OPPNAME', $this->OPPNAME])// 对方账户名称
//        ->andFilterWhere(['ACCOUNTS' => $this->ACCOUNTS])// 收款账号
//        ->andFilterWhere(['like', 'TRANSID', $this->TRANSID])// 流水号
//        ->andFilterWhere(['>=', 'TRDATE', empty($params['BsBankInfoSearch']['startDate']) ? '' : $params['BsBankInfoSearch']['startDate']])
//        ->andFilterWhere(['<=', 'TRDATE', empty($params['BsBankInfoSearch']['endDate']) ? '' : $params['BsBankInfoSearch']['endDate']]);
//
////        return $query->createCommand()->getRawSql();
//        return $dataProvider;
//    }
    public function search($params)
    {
        if (!empty($params['BsBankInfoSearch']['startDate'])) {
            $params['BsBankInfoSearch']['startDate'] = date('Ymd', strtotime($params['BsBankInfoSearch']['startDate']));
        }
        if (!empty($params['BsBankInfoSearch']['endDate'])) {
            $params['BsBankInfoSearch']['endDate'] = date('Ymd', strtotime($params['BsBankInfoSearch']['endDate']));
        }
        $countsql = "select count(*) from(select distinct
    f.TRANSID,
	f.CORP_DESC,
	f.ACCOUNTS,
	f.BNK_NME,
	f.TXNAMT,
	f.TRDATE,
	f.OPPNAME,
	f.OPPACCNO,
	f.state,
	f.remark,
	f.rbo_id
 FROM (SELECT * FROM (select
	a.TRANSID,
	a.CORP_DESC,
	a.ACCOUNTS,
	a.BNK_NME,
	a.TXNAMT,
	a.TRDATE,
	a.OPPNAME,
	a.OPPACCNO,
	c.state,
	b.remark,
	b.rbo_id,
	e.ord_no,
	b.ord_pay_id
from
	oms.bs_bank_info a
left join oms.r_bank_order b
on a.TRANSID=b.TRANSID
left join oms.bs_bank_check c
on b.rbo_id=c.rbo_id
left join oms.ord_pay d
on b.ord_pay_id=d.ord_pay_id
left join oms.ord_info e
on d.ord_id=e.ord_id
WHERE 1 = 1 order BY b.rbo_id DESC ,a.TRDATE DESC ,a.TRANSID)y where 1=1";

        $sql = "select DISTINCT 
    f.TRANSID,
	f.CORP_DESC,
	f.ACCOUNTS,
	f.BNK_NME,
	f.TXNAMT,
	f.TRDATE,
	f.OPPNAME,
	f.OPPACCNO,
	f.state,
	f.remark,
	f.rbo_id
 from (select * from (select
	a.TRANSID,
	a.CORP_DESC,
	a.ACCOUNTS,
	a.BNK_NME,
	a.TXNAMT,
	a.TRDATE,
	a.OPPNAME,
	a.OPPACCNO,
	c.state,
	b.remark,
	b.rbo_id,
	e.ord_no,
	b.ord_pay_id
from
	oms.bs_bank_info a
left join oms.r_bank_order b
on a.TRANSID=b.TRANSID
left join oms.bs_bank_check c
on b.rbo_id=c.rbo_id
left join oms.ord_pay d
on b.ord_pay_id=d.ord_pay_id
left join oms.ord_info e
on d.ord_id=e.ord_id
WHERE 1 = 1 order BY b.rbo_id DESC ,a.TRDATE DESC ,a.TRANSID)y where 1=1";
        if (!empty($params['BsBankInfoSearch']['order_no'])) {
            $sql = $sql . ' and y.ord_no LIKE \'%' . $params['BsBankInfoSearch']['order_no'] . '%\'';
            $countsql = $countsql . ' and y.ord_no LIKE \'%' . $params['BsBankInfoSearch']['order_no'] . '%\'';
        }
        if (!empty($params['BsBankInfoSearch']['checkorder'])) {
            $sql = $sql . '  and y.ord_pay_id is null ';
            $countsql = $countsql . '  and y.ord_pay_id is null ';
        }
        $sql=$sql.')f where 1=1';
        $countsql=$countsql.')f where 1=1';
        if (!empty($params['BsBankInfoSearch']['state'])) {
            $sql = $sql . ' and f.state =\'' . $params['BsBankInfoSearch']['state'] . '\'';
            $countsql = $countsql . ' and f.state =\'' . $params['BsBankInfoSearch']['state'] . '\'';
        }
        if (!empty($params['BsBankInfoSearch']['ACCOUNTS'])) {
            $sql = $sql . '  and f.ACCOUNTS = \'' . $params['BsBankInfoSearch']['ACCOUNTS'] . '\'';
            $countsql = $countsql . '  and f.ACCOUNTS = \'' . $params['BsBankInfoSearch']['ACCOUNTS'] . '\'';
        }
        if (!empty($params['BsBankInfoSearch']['TRANSID'])) {
            $sql = $sql . '  and f.TRANSID LIKE \'' . $params['BsBankInfoSearch']['TRANSID'] . '%\'';
            $countsql = $countsql . '  and f.TRANSID LIKE \'' . $params['BsBankInfoSearch']['TRANSID'] . '%\'';
        }
        if (!empty($params['BsBankInfoSearch']['CORP_DESC'])) {
            $sql = $sql . '  and f.CORP_DESC = \'' . $params['BsBankInfoSearch']['CORP_DESC'] . '\'';
            $countsql = $countsql . '  and f.CORP_DESC = \'' . $params['BsBankInfoSearch']['CORP_DESC'] . '\'';
        }
        if (!empty($params['BsBankInfoSearch']['startDate'])) {
            $sql = $sql . '  and f.TRDATE>=  \'' . $params['BsBankInfoSearch']['startDate'] . '\'';
            $countsql = $countsql . '  and f.TRDATE>= \'' . $params['BsBankInfoSearch']['startDate'] . '\'';
        }
        if (!empty($params['BsBankInfoSearch']['endDate'])) {
            $sql = $sql . '  and f.TRDATE<=  \'' . $params['BsBankInfoSearch']['endDate'] . '\'';
            $countsql = $countsql . '  and f.TRDATE<= \'' . $params['BsBankInfoSearch']['endDate'] . '\'';
        }
        $countsql = $countsql . ")t";
        $totalCount = \Yii::$app->db->createCommand($countsql)->queryScalar();//总条数

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
        return $provider;
    }

    //查询单笔流水号信息
    public function GetTransInfo($transid)
    {
        $model = BsBankInfo::findOne(['TRANSID' => $transid]);
        return $model;
    }

    //获取未支付的订单
    public function GetUnpaidOrder($params)
    {
        $sql = "select a.*,c.ord_no,c.req_tax_amount  from oms.ord_pay a left join erp.bs_payment b on  a.pac_id=b.pac_id  left join oms.ord_info c on a.ord_id=c.ord_id where (b.pac_sname='预付款' and a.yn_pay=0) or (b.pac_sname='信用额度' and a.yn_pay=1)";
        $sqlcount = "select count(*) from oms.ord_pay a left join erp.bs_payment b on  a.pac_id=b.pac_id  left join oms.ord_info c on a.ord_id=c.ord_id where (b.pac_sname='预付款' and a.yn_pay=0) or (b.pac_sname='信用额度' and a.yn_pay=1)";
        $totalcount = \Yii::$app->db->createCommand($sqlcount)->queryScalar();
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 5;
        }
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $totalcount,
            'pagination' => [
                'pageSize' => $pageSize
            ]
        ]);
        return $provider;
    }

    //判斷訂單是否已綁定過流水()
    public function GetTransList()
    {
        $model = \Yii::$app->db->createCommand("select * from oms.r_bank_order a left join oms.bs_bank_check b on a.rbo_id=b.rbo_id where b.state=10 or b.state=20 or b.state=40")->queryAll();
        return $model;

    }

    //获取订单币别
    public function GetCurCode($order_no)
    {
        $model = \Yii::$app->db->createCommand("select b.bsp_svalue from oms.ord_info a  left join erp.bs_pubdata b on a.cur_id=b.bsp_id where a.ord_no=:ord_no", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //查询单笔退款金额
    public function GetRefMoney($order_no)
    {
        $model = \Yii::$app->db->createCommand("select a.tax_fee from oms.ord_refund a where a.ord_no=:ord_no", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //获取订单的交易法人
    public function GetCorporate($order_no)
    {
        $model = \Yii::$app->db->createCommand("select b.company_code from oms.ord_info a left join erp.bs_company b on a.corporate=b.company_id where a.ord_no=:ord_no", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //获取是收款還是還款訂單
    public function GetPocName($ord_pay_id)
    {

        $model = \Yii::$app->db->createCommand("select a.yn_pay,b.pac_sname from oms.ord_pay a left join erp.bs_payment b on a.pac_id=b.pac_id where a.ord_pay_id=:ord_pay_id", ['ord_pay_id' => $ord_pay_id])->queryAll();
        return $model;
    }

    //获取订单是否支付
    public function GetYnPay($order_no)
    {
        $model = \Yii::$app->db->createCommand("select b.yn_pay from oms.ord_info a left join oms.ord_pay b on a.ord_id=b.ord_id where a.ord_no=:ord_no", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //获取客户信用额度支付还款时间
    public function GetRepayDate($cust_code)
    {
        $model = \Yii::$app->db->createCommand("select a.repay_date from oms.repay_credit a where a.user_id in(select b.cust_id from erp.crm_bs_customer_info b where b.cust_code=:cust_code) and a.is_repay=0", ['cust_code' => $cust_code])->queryAll();
        return $model;
    }

    //查询是否有信用额度使用记录
    public function GetCreditRecord($ord_pay_id)
    {
        $model = \Yii::$app->db->createCommand("select * from oms.repay_credit a where a.ord_pay_id=:ord_pay_id ", ['ord_pay_id' => $ord_pay_id])->queryAll();
        return $model;
    }

    //查询流水是否有审核中的数据
    public function GetVerifyTrans($transid)
    {
        $model = \Yii::$app->db->createCommand("select a.* from oms.r_bank_order a  left join oms.bs_bank_check b on a.rbo_id=b.rbo_id where TRANSID=:transid and b.state=10", ['transid' => $transid])->queryAll();
        return $model;
    }

    //查询订单是否有审核中的数据
    public function GetVerifOrder($order_no)
    {
        $model = \Yii::$app->db->createCommand("select a.* from oms.r_bank_order a left join oms.bs_bank_check b on a.rbo_id=b.rbo_id left join oms.ord_pay c on a.ord_pay_id=c.ord_pay_id left join oms.ord_info d on c.ord_id=d.ord_id where d.ord_no=:ord_no and (b.state=10 or b.state=20)", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //查询ord_pay表
    public function GetOrdPay($ord_pay_id)
    {
        $model = \Yii::$app->db->createCommand("select a.*,b.ord_no,b.req_tax_amount from oms.ord_pay a left join oms.ord_info b on a.ord_id=b.ord_id where a.ord_pay_id=:ord_pay_id", ['ord_pay_id' => $ord_pay_id])->queryAll();
        return $model;
    }

    //查询ord_pay_id是否在r_bank_order存在
    public function GetRboinfo($ord_pay_id)
    {
        $model = \Yii::$app->db->createCommand("select a.* from oms.r_bank_order a where a.ord_pay_id=:ord_pay_id", ['ord_pay_id' => $ord_pay_id])->queryAll();
        return $model;
    }

    //判断是多笔还是单笔申请
    public function GetIsSign($rbo_id)
    {
        $model = \Yii::$app->db->createCommand("select distinct a.TRANSID from oms.r_bank_order a where a.rbo_id=:rbo_id", ['rbo_id' => $rbo_id])->queryAll();
        return $model;
    }

    //根据rbo_id获取数据
    public function GetDataByRboId($rbo_id)
    {
        $model = \Yii::$app->db->createCommand("select a.*,b.*,c.req_tax_amount,c.ord_no from oms.r_bank_order a left join oms.ord_pay b 
        on a.ord_pay_id=b.ord_pay_id left join oms.ord_info c on b.ord_id=c.ord_id where a.rbo_id=:rbo_id", ['rbo_id' => $rbo_id])->queryAll();
        return $model;
    }

    //根据流水号查询所有审核已完成的数据
    public function GetPassTrans($transid)
    {
        $model = \Yii::$app->db->createCommand("select SUM(b.stag_cost) totalmoney from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join oms.bs_bank_check c on a.rbo_id=c.rbo_id where TRANSID=:TRANSID and 
        c.state=20", ['TRANSID' => $transid])->queryAll();
        return $model;
    }

    //查询订单信息
    public function GetOrderInfo($order_no)
    {
        $model = \Yii::$app->db->createCommand("select a.* from oms.ord_info a where a.ord_no=:ord_no", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //判断是否是收还款订单
    public function IsRecvOrRepay($order_no)
    {
        $model = \Yii::$app->db->createCommand("select b.*,c.pac_sname from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
((c.pac_sname='预付款' and b.yn_pay=0) or (c.pac_sname='信用额度支付' and b.yn_pay=1))", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //获取所有可操作订单
    public function GetOperaOrder($order_no)
    {
        $model = \Yii::$app->db->createCommand("select b.*,c.pac_sname from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id left join oms.repay_credit d on b.ord_pay_id=d.ord_pay_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
((c.pac_sname='预付款' and b.yn_pay=0) or (c.pac_sname='信用额度支付' and b.yn_pay=1 and d.is_repay=0))", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //查询流水是否是第一次使用
    public function GetIsFirstUse($transid)
    {
        $model = \Yii::$app->db->createCommand("select a.* from oms.r_bank_order a left join oms.bs_bank_check b on a.rbo_id=b.rbo_id where a.TRANSID=:TRANSID and b.state=20", ['TRANSID' => $transid])->queryAll();
        return $model;
    }

    //通过订单号查询该笔订单时全额订单还是分期订单(包括帐信)
    public function GetIsFullAmount($order_no)
    {
        $model = \Yii::$app->db->createCommand("select a.* from oms.ord_pay a where a.ord_id in (select b.ord_id from oms.ord_info b where b.ord_no=:ord_no)", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //多分期订单排序
    public function GetOrderbyStage($order_no)
    {
        $model = \Yii::$app->db->createCommand("select b.* from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
(c.pac_sname='预付款' and b.yn_pay=0) order by b.stag_times ASC ", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //多信用额度排序
    public function GetOrderbyCredit($order_no)
    {
        $model = \Yii::$app->db->createCommand("select b.* from oms.ord_pay b left join erp.bs_payment c on b.pac_id=c.pac_id left join oms.repay_credit d on b.ord_pay_id=d.ord_pay_id where b.ord_id in (select a.ord_id from oms.ord_info a where a.ord_no=:ord_no) AND
    (c.pac_sname='信用额度支付' and b.yn_pay=1 and d.is_repay=0) order by b.stag_cost ASC ", ['ord_no' => $order_no])->queryAll();
        return $model;
    }

    //根据流水号和审核id查询流水号上面绑定的订单
    public function GetOrderNo($transid, $rboid)
    {
        $model = \Yii::$app->db->createCommand("select distinct c.ord_no from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join oms.ord_info c on b.ord_id=c.ord_id where a.TRANSID=:TRANSID and a.rbo_id=:rbo_id", ['TRANSID' => $transid, 'rbo_id' => $rboid])->queryAll();
        return $model;
    }

    //获取批量收款流水信息
    public function GetTransBatch($rbo_id)
    {
        $model = \Yii::$app->db->createCommand("select b.* from oms.bs_bank_info b where b.TRANSID in(select DISTINCT a.TRANSID from oms.r_bank_order a where a.rbo_id=:rbo_id)", ['rbo_id' => $rbo_id])->queryAll();
        return $model;
    }

    //获取批量订单金额
    public function GetBatchMoney($transid, $rboid)
    {
        $model = \Yii::$app->db->createCommand("select SUM(b.stag_cost) stag_cost from oms.r_bank_order a left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id where a.TRANSID=:TRANSID and a.rbo_id=:rbo_id group by a.TRANSID", ['TRANSID' => $transid, 'rbo_id' => $rboid])->queryAll();
        return $model;
    }

    //获取批量流水说明
    public function GetBatchRemark($transid, $rboid)
    {
        $model = \Yii::$app->db->createCommand("select DISTINCT a.remark from oms.r_bank_order a where a.TRANSID=:TRANSID and a.rbo_id=:rbo_id", ['TRANSID' => $transid, 'rbo_id' => $rboid])->queryAll();
        return $model;
    }

    //获取所有流水绑定过的ord_pay_id
    public function GetBinded($transid)
    {
        $model = \Yii::$app->db->createCommand("select a.ord_pay_id from oms.r_bank_order a  left join oms.bs_bank_check b on a.rbo_id=b.rbo_id where a.TRANSID=:TRANSID and b.state=20", ['TRANSID' => $transid])->queryAll();
        return $model;
    }

    //查询单笔支付信息
    public function OrdPayInfo($ord_pay_id)
    {
        $model = \Yii::$app->db->createCommand("select a.* from oms.ord_pay a where a.ord_pay_id=:ord_pay_id", ['ord_pay_id' => $ord_pay_id])->queryOne();
        return $model;
    }

    //根据流水号查询所有已完成记录订单的客户id
    public function GetCustCode($transid)
    {
        $model = \Yii::$app->db->createCommand("select DISTINCT c.cust_code from oms.r_bank_order a  left join oms.bs_bank_check d on a.rbo_id=d.rbo_id left join oms.ord_pay b on a.ord_pay_id=b.ord_pay_id left join oms.ord_info c on b.ord_id=c.ord_id where 
a.TRANSID=:TRANSID and d.state=20", ['TRANSID' => $transid])->queryOne();
        return $model;
    }
    //根据订单号查询客户id
    public function GetCustCodeByOrder($order_no)
    {
        $model = \Yii::$app->db->createCommand("select a.cust_code from oms.ord_info a where a.ord_no=:ord_no", ['ord_no' => $order_no])->queryOne();
        return $model;
    }
}