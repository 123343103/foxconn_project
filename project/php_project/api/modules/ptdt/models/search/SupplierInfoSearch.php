<?php

namespace app\modules\ptdt\models\search;

use app\classes\Trans;
use app\modules\ptdt\models\SupplierInfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * PdSupplierSearch represents the model behind the search form about `app\modules\ptdt\models\PdSupplier`.
 */
class SupplierInfoSearch extends SupplierInfo
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['supplier_sname', 'supplier_brand', 'supplier_type', 'supplier_issupplier', 'supplier_status', 'supplier_source'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * 选择商品
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchProducts($params)
    {
        $query = (new Query())
            ->select([
                'pdt.pdt_id',
                // 料号
                'pdt.pdt_no',
                // 品名
                'pdt.pdt_name',
                // 仓库
                'wh.wh_name',
                // 商品库存
                'bi.invt_num',
                // 类别
                //'ctg.category_id',
                'ctg.category_sname',
                // 单位
                'cu.unit_name',
                //'pdt.unit',
                // 重量
                'pdt.pdt_weight',
                // 品牌
                'bb.BRAND_NAME_CN',
                // 规格
                'ca.ATTR_NAME specification',
                // 材积
                'pdt.pdt_vol',
                // 折扣率
            ])
            ->from(['pdt' => BsProduct::tableName()])
            ->LeftJoin(BsCategory::tableName() . ' ctg', "pdt.bs_category_id=ctg.category_id")
            ->leftJoin('category_attr ca', 'ca.CATEGORY_ATTR_ID=pdt.tp_spec')
            ->LeftJoin('wms.bs_wh wh', "wh.wh_id=pdt.pdt_whsid")
            ->LeftJoin('wms.l_bs_invt bi', "bi.pdt_id=pdt.pdt_id")
            ->leftJoin('bs_category_unit cu', 'cu.id=pdt.unit')
            ->LeftJoin('bs_brand bb', "bb.brand_id=pdt.brand_id")
            ->where('pdt.pdt_id is not null and ctg.category_id  is not null');

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
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if (!empty($params['searchKeyword'])) {
            $trans = new Trans();
            $params['searchKeyword'] = trim($params['searchKeyword']);
            $query->andFilterWhere(['or',
                ['like', 'pdt_no', $params['searchKeyword']],
                ['like', 'pdt_name', $trans->t2c($params['searchKeyword'])],
                ['like', 'pdt_name', $trans->c2t($params['searchKeyword'])],
                ['like', 'pdt_name', $params['searchKeyword']],
            ]);
        }
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 查询商品库列表
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchSupplierLib($params) {
        $query = (new Query())
            ->select([
                // 编号
                'si.supplier_id',
                'si.supplier_code',
                // 供应商全称
                'si.supplier_sname',
                // 简称
                'si.supplier_shortname',
                // 英文全称
                'si.supplier_ename',
                // 品牌
                'si.supplier_brand',
                // 供应商状态
                //'si.supplier_status',
                '(CASE si.supplier_status '
                .'WHEN ' . SupplierInfo::STATUS_NORMAL . ' THEN "正常" '
                .'WHEN ' . SupplierInfo::STATUS_SEAL . ' THEN "封存" '
                .'ELSE "" END) AS status',
                // 供应商类型
                //'si.supplier_type',
                'bp1.bsp_svalue AS supplier_type',
                // 供应商来源
                //'si.supplier_source',
                'bp2.bsp_svalue AS supplier_source',
                // 是否为集团供应商
                //'si.supplier_issupplier',
                'IF(si.supplier_issupplier="1","是","否") AS issupplier',
                // 供应商代码

                // 供应商地位
                //'si.supplier_position',
                'bp3.bsp_svalue AS supplier_position',
                // 供应商地址
                'si.supplier_compaddress',
                // 代理商品定位
                'si.supplier_agents_position',
                // 代理等级
                //'si.supplier_agentslevel',
                'bp4.bsp_svalue AS supplier_agentslevel',
                // 销售范围
                //'si.supplier_salarea',
                'cbs1.csarea_name AS salarea',
                // 授权日期
                'si.supplier_authorize_bdate',
                // 授权区域范围
                //'si.supplier_authorize_area',
                'cbs2.csarea_name AS auth_area',
                // 供应商主营范围
                //'si.supplier_main_product',
                'sm.vmainl_product',
                // 备注
                'si.supplier_remark1',
            ])
            ->from(['si' => SupplierInfo::tableName()])
            ->leftJoin('bs_pubdata bp1', "bp1.bsp_id=si.supplier_type") // 供应商类型
            ->leftJoin('bs_pubdata bp2', "bp2.bsp_id=si.supplier_source") // 供应商来源
            ->leftJoin('bs_pubdata bp3', "bp3.bsp_id=si.supplier_position") // 供应商地位
            ->leftJoin('bs_pubdata bp4', "bp4.bsp_id=si.supplier_agentslevel") // 供应商等级
            ->leftJoin('crm_bs_salearea cbs1', "cbs1.csarea_id=si.supplier_salarea") // 销售范围
            ->leftJoin('crm_bs_salearea cbs2', "cbs2.csarea_id=si.supplier_authorize_area") // 授权范围
            ->leftJoin('supplier_mainlist sm', "sm.vmainl_id=si.supplier_main_product") // 主营范围
//            ->LeftJoin('bs_brand bb', "bb.brand_id=pdt.brand_id")
            ->where([]);
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
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $trans = new Trans();
        $query->andFilterWhere([
                'and',
                ['like', 'supplier_sname', $trans->c2t($this->supplier_sname)],
                ['like', 'supplier_sname', $trans->t2c($this->supplier_sname)],
            ])
            ->andFilterWhere([
                'and',
                ['like', 'supplier_brand', $trans->c2t($this->supplier_brand)],
                ['like', 'supplier_brand', $trans->t2c($this->supplier_brand)],
            ]) // 品牌
            ->andFilterWhere(['si.supplier_type' => $this->supplier_type]) // 类型
            ->andFilterWhere(['si.supplier_issupplier' => $this->supplier_issupplier]) // 是否集团供应商
            ->andFilterWhere(['si.supplier_status' => $this->supplier_status]) // 状态
            ->andFilterWhere(['bp2.bsp_id' => $this->supplier_source]); // 来源
//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    /**
     * 查看详情
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchSupplierView($id) {
        $query = (new Query())
            ->select([
                // 编号
                'si.supplier_id',
                'si.supplier_code',
                // 供应商全称
                'si.supplier_sname',
                // 简称
                'si.supplier_shortname',
                // 英文全称
                'si.supplier_ename',
                // 品牌
                'si.supplier_brand',
                // 供应商状态
                //'si.supplier_status',
                '(CASE si.supplier_status '
                .'WHEN ' . SupplierInfo::STATUS_NORMAL . ' THEN "正常" '
                .'WHEN ' . SupplierInfo::STATUS_SEAL . ' THEN "封存" '
                .'ELSE "" END) AS status',
                // 供应商类型
                //'si.supplier_type',
                'bp1.bsp_svalue AS supplier_type',
                // 供应商来源
                //'si.supplier_source',
                'bp2.bsp_svalue AS supplier_source',
                // 是否为集团供应商
                //'si.supplier_issupplier',
                'IF(si.supplier_issupplier="1","是","否") AS issupplier',
                // 供应商代码

                // 供应商地位
                //'si.supplier_position',
                'bp3.bsp_svalue AS supplier_position',
                // 供应商地址
                'si.supplier_compaddress',
                // 代理商品定位
                'si.supplier_agents_position',
                // 代理等级
                //'si.supplier_agentslevel',
                'bp4.bsp_svalue AS supplier_agentslevel',
                // 销售范围
                //'si.supplier_salarea',
                'cbs1.csarea_name AS salarea',
                // 授权日期
                'si.supplier_authorize_bdate',
                // 授权区域范围
                //'si.supplier_authorize_area',
                'cbs2.csarea_name AS auth_area',
                // 供应商主营范围
                //'si.supplier_main_product',
                'sm.vmainl_product',
                // 备注
                'si.supplier_remark1',
            ])
            ->from(['si' => SupplierInfo::tableName()])
            ->leftJoin('bs_pubdata bp1', "bp1.bsp_id=si.supplier_type") // 供应商类型
            ->leftJoin('bs_pubdata bp2', "bp2.bsp_id=si.supplier_source") // 供应商来源
            ->leftJoin('bs_pubdata bp3', "bp3.bsp_id=si.supplier_position") // 供应商地位
            ->leftJoin('bs_pubdata bp4', "bp4.bsp_id=si.supplier_agentslevel") // 供应商等级
            ->leftJoin('crm_bs_salearea cbs1', "cbs1.csarea_id=si.supplier_salarea") // 销售范围
            ->leftJoin('crm_bs_salearea cbs2', "cbs2.csarea_id=si.supplier_authorize_area") // 授权范围
            ->leftJoin('supplier_mainlist sm', "sm.vmainl_id=si.supplier_main_product") // 主营范围
//            ->LeftJoin('bs_brand bb', "bb.brand_id=pdt.brand_id")
            ->where(['si.supplier_id' => $id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
