<?php

namespace app\modules\warehouse\models\search;

use app\classes\Trans;
use app\modules\warehouse\models\InvChangeh;
use app\modules\warehouse\models\show\InvChangehShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\show\SetWarehouseShow;
use yii\data\SqlDataProvider;
use yii\db\Query;
use app\modules\common\models\BsBusinessType;

/**
 * This is the ActiveQuery class for [[\app\modules\warehouse\models\InvChangeh]].
 *
 * @see \app\modules\warehouse\models\InvChangeh
 */
class InvChangehSearch extends InvChangeh
{
    public $organization_id;
    public $staff_name;
    public $business_type_desc;
    public $organization_name;
    public $chh_statusH;
    public $chh_status;
    public $create_at;
    public $Iwh_name;
    public $o_status;
    public $in_status;
    public $Owh_name;
    public $Iinvh_statusH;
    public $Iinvh_status;
    public $Oinvh_statusH;
    public $Oinvh_status;
    public $start;
    public $end;
    public $start_date;
    public $end_date;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chh_id', 'chh_type', 'comp_id', 'wh_id', 'wh_id2', 'whp_id', 'create_by', 'review_by'], 'integer'],
            [['chh_date', 'organization_id', 'staff_name','start_date','end_date', 'create_at', 'review_at'], 'safe'],
            [['chh_code'], 'string', 'max' => 40],
            [['chh_status'], 'string', 'max' => 4],
            [['chh_description', 'chh_remark'], 'string', 'max' => 200],
            [['chh_code','business_type_desc','organization_name','chh_statusH','chh_status','create_at','Iwh_name','Owh_name','in_status','o_status','Iinvh_statusH','Oinvh_statusH','Iinvh_status','Oinvh_status','start','end'], 'safe']
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * 主表搜索
     */
    public function search($params)
    {
//        $query = (new Query())
//            ->select([
//                // 商品id
//                'invh.chh_id',
//                'invh.chh_code',
//                'invh.chh_status',
////                (case a.invh_status when :wait_commit then '待提交' when :check_ing then '审核中' when :check_complete then '审核完成' when :wait_warehouse then '待入仓' when :already_warehouse then '已入仓' when :reject_status then '驳回' else '删除' end) stockInStatus,
//                'sta.staff_name',
//
//            ])
//            ->from(['invh' => 'wms.inv_changeh'])
//            ->leftJoin('erp.hr_staff sta','sta.staff_id=invh.create_by')
//            ->where(["!=", "chh_status", self::STATUS_DELETE]);
//        $query = InvChangehShow::find()
//            ->select([
//                'invh.chh_id',
//                'invh.chh_code',
////                'invh.chh_status',
////                'sta.staff_name',
//            ])
//            ->from(['invh' => 'wms.inv_changeh'])
//            ->leftJoin('erp.hr_staff sta','sta.staff_id=invh.create_by')
//            ->where(["!=", "chh_status", self::STATUS_DELETE]);
        // add conditions that should always apply here
        $types = [];
        $changeType = BsBusinessType::find()->select(['business_type_id'])->where(['business_code' => 'wm04'])->all();
        foreach ($changeType as $key => $val) {
            $types[] = $val["business_type_id"];
        }
        $query = InvChangehShow::find()
//            ->select([
////                'invh.chh_id',
////                'invh.chh_code',
//////                'invh.chh_status',
//////                'sta.staff_name',
//            ])
//            ->from(['invh' => 'wms.inv_changeh'])
//            ->leftJoin('erp.hr_staffss sta','sta.staff_id=invh.create_by')
//            ->leftJoin('wms.bs_wh wh', 'wh.wh_id=invh.wh_id')
            ->where(["!=", "chh_status", self::STATUS_DELETE]);


        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [         //查询按照id倒序
                'defaultOrder' => ['chh_id' => SORT_DESC],
            ],
        ]);

        $this->load($params);
        if(!$this->validate())
        {
            return $dataProvider;
        }
        $trans = new Trans();//繁简转换

//        $query->joinWith('applyStaff');
//        $query->joinWith('custCustomer');
//        $query->joinWith('personinch');
//        $query->joinWith("staff");
        $query->andFilterWhere([
            'chh_status' => $this->chh_status,
            'chh_type' => $this->chh_type,
            'wh_id' => $this->wh_id,
            'review_at'=>$this->review_at,
//            'organization_id'=> $this->organization_id,
//            'applydate' => $this->applydate,
//            'applydep' => $this->applydep,
//            'member_id' => $this->member_id,
//            'cust_area' => $this->cust_area,
        ]);
        $query->andFilterWhere(['like', 'chh_code', $trans->t2c($this->chh_code)])
            ->andFilterWhere(["in", "chh_type", $types])
//              ->andFilterWhere(['like','hr_staff.staff_name',$this->staff_name]);
            ->andFilterWhere(['or', ['like', 'hr_staff.staff_name', $trans->t2c($this->staff_name)],
                ['like', 'hr_staff.staff_name', $trans->c2t($this->staff_name)]]);
        if($this->start!="" || $this->end!=""){
            $query->andFilterWhere(['>=','review_at',date('Y-m-d H:i:s',strtotime($this->start))])
                ->andFilterWhere(['<','review_at',date('Y-m-d ',strtotime($this->end.'+1 day'))]);
        }
        return $dataProvider;

    }

    //异动列表查询
    public function changeSearch($params)
    {
        $types = [];
        $changeType = BsBusinessType::find()->select(['business_type_id'])->where(['business_code' => 'wm05'])->all();
        foreach ($changeType as $key => $val) {
            $types[] = $val["business_type_id"];
        }
        $query = InvChangehShow::find()->where(["!=", "chh_status", self::STATUS_DELETE])->andFilterWhere(["in", "chh_type", $types]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [         //查询按照id倒序
                'defaultOrder' => ['chh_id' => SORT_DESC],
            ],
        ]);
        $this->load($params);
        $query->andFilterWhere([
            'wh_id' => $this->wh_id,
            'wh_id2' => $this->wh_id2,
            'chh_code' => $this->chh_code,
            'chh_type' => $this->chh_type,
            'chh_date' => $this->chh_date,
            'create_by' => $this->create_by,
//            'review_at' => $this->review_at,
            'review_by' => $this->review_by,
            'chh_status' => $this->chh_status,
        ]);
        if($this->start!=""||$this->end !=""){
            $query->andFilterWhere(['>=','review_at',date('Y-m-d H:i:s',strtotime($this->start))])
                ->andFilterWhere(['<','review_at',date('Y-m-d H:i:s',strtotime($this->end.'+1 day'))]);
        }
//        return $query;
        return $dataProvider;

    }

    /**
     * @param $id
     * @return ActiveDataProvider
     * 子表搜索
     */
    public function searchInvL($id)
    {
        $query="SELECT s.chh_remark,s.chl_bach,s.chl_barcode,s.chl_num,s.part_no2,s.pdt_no,
                s.before_num1,s.before_num2,s.mode,s.deal_price,s.chl_bach2,
				(SELECT t.st_code FROM wms.bs_st t WHERE t.st_id=s.st_id )st_id,
				(SELECT t.st_code FROM wms.bs_st t WHERE t.st_id=s.st_id2 )st_id2,
				(SELECT bw.wh_name FROM wms.bs_wh bw WHERE bw.wh_id=s.wh_id )wh_id,
				(SELECT bw.wh_name FROM wms.bs_wh bw WHERE bw.wh_id=s.wh_id2 )wh_id2,
				(SELECT m.pdt_name FROM pdt.bs_material m WHERE m.part_no=s.part_no2)pdt_name2,
				(SELECT m.tp_spec FROM pdt.bs_material m WHERE m.part_no=s.part_no2)tp_spec2,
				(SELECT m.unit FROM pdt.bs_material m WHERE m.part_no=s.part_no2)unit2,
				(SELECT m.brand FROM pdt.bs_material m WHERE m.part_no=s.part_no2)brand2,
				(SELECT m.catg_name FROM pdt.bs_category m WHERE m.catg_no=p.category_no)catg_name,
				p.pdt_name,
				p.brand,
				p.unit,
				p.tp_spec
                from wms.inv_changel s  
                LEFT JOIN pdt.bs_material p ON s.pdt_no=p.part_no
                WHERE s.chh_id=$id";
        $dataProvider = new SqlDataProvider([
            'sql' => $query,
        ]);
        return $dataProvider;
//        $query = (new Query())
//            ->select([
//                   // s.*,p.pdt_name,p.brand,p.unit,p.tp_spec
//                // 商品id
//                'pdt.pdt_id',
//                'pdt2.pdt_id pdt_id2',
//                // 商品料号
//                'pdt.pdt_no',
//                'pdt2.pdt_no pdt_no2',
//                'pdt.pdt_name',//商品名
//                'pdt2.pdt_name pdt_name2',
//                'invl.chl_num',//報廢數
//                'invl.chh_remark',//備註
//                'invl.wh_id',//出仓仓库
//                'wh.wh_name',//出仓仓库
//                'invl.wh_id2',//入仓仓库
//                'wh2.wh_name wh_name2',//入仓仓库
//                'invl.st_id',//出仓仓库储位
//                'st.st_code',
//                'invl.st_id2',//入仓仓库储位
//                'st2.st_code st_code2',
//                'invl.pdt_id2',//料号异动2
//                'atr.ATTR_NAME',//規格
//                'unt.unit_name',//單位
//                'cat.category_sname',//分類
//                'lbi.invt_num',//商品庫存
//                'atr2.ATTR_NAME ATTR_NAME2',//規格
//                'unt2.unit_name unit_name2',//單位
//                'cat2.category_sname category_sname2',//分類
//                'lbi2.invt_num invt_num2',//商品庫存
//
//            ])
//            ->from(['invl' => 'wms.inv_changel'])
//            ->leftJoin('pdt.bs_material pdt', 'pdt.part_no=invl.pdt_no')
//            ->leftJoin('erp.bs_product pdt2', 'pdt2.pdt_id=invl.pdt_id2')
//            ->leftJoin('wms.bs_wh wh', 'wh.wh_id=invl.wh_id')
//            ->leftJoin('wms.bs_wh wh2', 'wh2.wh_id=invl.wh_id2')
//            ->leftJoin('wms.bs_st st', 'st.st_id=invl.st_id')
//            ->leftJoin('wms.bs_st st2', 'st2.st_id=invl.st_id2')
//            ->leftJoin('erp.bs_brand bra', 'bra.BRAND_ID=pdt.brand_id')
//            ->leftJoin('erp.category_attr atr', 'atr.CATEGORY_ATTR_ID=pdt.tp_spec')
//            ->leftJoin('erp.category_attr atr2', 'atr2.CATEGORY_ATTR_ID=pdt2.tp_spec')
//            ->leftJoin('erp.bs_category_unit unt', 'unt.id=pdt.unit')
//            ->leftJoin('erp.bs_category_unit unt2', 'unt2.id=pdt2.unit')
//            ->leftJoin('erp.bs_category cat', 'cat.category_id=pdt.bs_category_id')
//            ->leftJoin('erp.bs_category cat2', 'cat2.category_id=pdt2.bs_category_id')
//            ->leftJoin('wms.l_bs_invt lbi', 'lbi.pdt_id=pdt.pdt_id')// 库存表
//            ->leftJoin('wms.l_bs_invt lbi2', 'lbi2.pdt_id=pdt2.pdt_id')// 库存表
//            ->where(['and', ['invl.chh_id' => $id]]);
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
    }

    public function attributeLabels()
    {
        return [
            'chh_id' => '单据ID',
            'chh_code' => '单据编码',
            'chh_type' => '异动类型',//（仓库转移，调拨，仓位异动，料号转换等等异动单）
            'comp_id' => '公司ID',
            'chh_date' => '单据日期',
            'wh_id' => '异动仓库,料号异动仓或转仓为转出仓',
            'wh_id2' => '对应异动仓库',
            'chh_status' => '状态',
            'chh_description' => '异动描述',
            'whp_id' => '关联仓库费用表(wh_price)',
            'chh_remark' => '备注',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'review_by' => '确认人',
            'review_at' => '确认日期',
        ];
    }

    public function searchs($params)
    {
        $types = [];
        $changeType = BsBusinessType::find()->select(['business_type_id'])->where(['business_code' => 'wm03'])->all();
        foreach ($changeType as $key => $val) {
            $types[] = $val["business_type_id"];
        }
        $query = InvChangehShow::find()
            ->where(["!=", "chh_status", self::STATUS_DELETE])
            ->andFilterWhere(["in", "chh_type", $types]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [         //查询按照id倒序
                'defaultOrder' => ['chh_id' => SORT_DESC],
            ],
        ]);

        $this->load($params);
        $trans = new Trans();//繁简转换

        $query->andFilterWhere([
            'chh_status' => $this->chh_status,
            'chh_type' => $this->chh_type,
            'wh_id' => $this->Owh_name,
            'wh_id2' => $this->Iwh_name,
            'create_at' => $this->create_at,
            'o_status'=>$this->o_status,
            'in_status'=>$this->in_status
        ]);
//        dumpE($params);
        $query->andFilterWhere(['like', 'chh_code', $trans->t2c($this->chh_code)]);
        $query->andFilterWhere(['>=','create_at',$this->start_date]);
        $query->andFilterWhere(['<=','create_at',$this->end_date]);
        return $dataProvider;
    }

    public function searchbyid($id)
    {
        $types = [];
        $changeType = BsBusinessType::find()->select(['business_type_id'])->where(['business_code' => 'wm03'])->all();
        foreach ($changeType as $key => $val) {
            $types[] = $val["business_type_id"];
        }
        $query = InvChangehShow::find()
            ->where(["!=", "chh_status", self::STATUS_DELETE])
            ->andFilterWhere(["in", "chh_type", $types])
            ->andWhere(['=','chh_id',$id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
    //关联商品信息
    public function searchRelationCommodity($id)
    {
        $sql = "SELECT
                    ma.part_no,
                    ma.brand,
                    ma.pdt_name,
                    ma.tp_spec,
                    ma.unit,
                    l.chl_num,
                    l.chl_bach,
                    l.before_num1,
                    st.st_code AS st_id,
                    st2.st_code AS st_id2
                FROM
                    wms.inv_changeh h
                LEFT JOIN wms.inv_changel l ON h.chh_id = l.chh_id
                LEFT JOIN pdt.bs_material ma ON ma.part_no = l.pdt_no
                LEFT JOIN wms.bs_st st ON st.st_id = l.st_id
                LEFT JOIN wms.bs_st st2 ON st2.st_id = l.st_id2
                WHERE
                    h.chh_id = $id";
//        return $sql;
//        $productInfo= \Yii::$app->db->createCommand($sql, $queryParams)->queryAll();
//        $list['rows']=$productInfo;
//        return $list;
        $provider=new SqlDataProvider([
            'sql'=>$sql,
        ]);
        return $provider;
    }
}
