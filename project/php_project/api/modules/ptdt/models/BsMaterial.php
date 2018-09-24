<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "bs_material".
 *
 * @property string $pkmt_id
 * @property string $part_no
 * @property string $pdt_name
 * @property string $category_no
 * @property string $tp_spec
 * @property integer $status
 * @property string $brand
 * @property string $unit
 * @property string $applicant
 * @property string $applydep
 * @property string $applydate
 * @property string $creatdate
 * @property string $thedtabase
 * @property string $pdt_manager
 * @property integer $price_type
 * @property integer $price_from
 * @property integer $iskz
 * @property integer $isproxy
 * @property integer $isonlinesell
 * @property integer $risk_level
 * @property string $market_price
 * @property string $lower_profit
 * @property string $upper_profit
 * @property string $valid_date
 * @property integer $istitle
 * @property string $archrival
 * @property integer $pdt_level
 * @property integer $isto_xs
 * @property string $lirunsx
 * @property string $lirunxx
 * @property string $so_nbr
 * @property string $salearea
 * @property string $usefor
 * @property string $packagespc
 * @property string $isrelation
 * @property string $relation_remark
 * @property integer $p_flag
 * @property string $partno_remark
 * @property string $istitle_so_nbr
 * @property integer $istitle_status
 * @property string $istitle_editor
 * @property string $brand2
 * @property string $center
 * @property string $bu
 * @property string $commodity
 * @property string $pricedate
 * @property string $isvalid
 * @property integer $validstatus
 * @property string $valid_so_nbr
 */
class BsMaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_material';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'price_type', 'price_from', 'iskz', 'isproxy', 'isonlinesell', 'risk_level', 'istitle', 'pdt_level', 'isto_xs', 'p_flag', 'istitle_status', 'validstatus'], 'integer'],
            [['applydate', 'creatdate', 'valid_date', 'pricedate'], 'safe'],
            [['market_price', 'lower_profit', 'upper_profit', 'lirunsx', 'lirunxx'], 'number'],
            [['part_no', 'unit', 'applicant', 'applydep'], 'string', 'max' => 20],
            [['pdt_name', 'tp_spec', 'salearea', 'bu'], 'string', 'max' => 1000],
            [['category_no'], 'string', 'max' => 30],
            [['brand', 'pdt_manager', 'so_nbr'], 'string', 'max' => 50],
            [['thedtabase', 'archrival'], 'string', 'max' => 255],
            [['usefor'], 'string', 'max' => 2000],
            [['packagespc'], 'string', 'max' => 150],
            [['isrelation', 'istitle_editor', 'brand2', 'valid_so_nbr'], 'string', 'max' => 100],
            [['relation_remark', 'partno_remark'], 'string', 'max' => 3000],
            [['istitle_so_nbr'], 'string', 'max' => 60],
            [['center', 'commodity'], 'string', 'max' => 400],
            [['isvalid'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pkmt_id' => 'pkid',
            'part_no' => '料号',
            'pdt_name' => '品名',
            'category_no' => '类别编码',
            'tp_spec' => '规格型号',
            'status' => '狀態0未定價1發起定價2商品開發維護3審核中4已定價5被駁回6已逾期7重新定價',
            'brand' => '品牌',
            'unit' => '计量单位',
            'applicant' => '申请人',
            'applydep' => '申请部门',
            'applydate' => '申请时间',
            'creatdate' => '生成时间',
            'thedtabase' => '所属资料库',
            'pdt_manager' => '商品經理人',
            'price_type' => '0新增1降價2漲價3定價不變，利潤率變更4延期',
            'price_from' => '0自主開發1crd/prd',
            'iskz' => '是否客製化',
            'isproxy' => '是否取得代理',
            'isonlinesell' => '能否線上銷售',
            'risk_level' => '法務風險等級 0 高 1 中 2 低',
            'market_price' => '市場均價',
            'lower_profit' => '利潤率下限',
            'upper_profit' => '利潤率上限',
            'valid_date' => '價格有效日期',
            'istitle' => '是否拳頭商品0否1是',
            'archrival' => '主要競爭對手',
            'pdt_level' => '商品定位 1高2 中3低',
            'isto_xs' => '是否發佈到銷售管理系統0否1是',
            'lirunsx' => '利潤上限',
            'lirunxx' => '利潤下限',
            'so_nbr' => 'So Nbr',
            'salearea' => '0全球1全國2全國（不包含港澳台）3省市區',
            'usefor' => 'Usefor',
            'packagespc' => 'Packagespc',
            'isrelation' => '是否關聯料號，如果為空則不是，不為空則是',
            'relation_remark' => 'Relation Remark',
            'p_flag' => '0 標準定價(抓pas核價) 1實時定價,手動維護',
            'partno_remark' => 'Partno Remark',
            'istitle_so_nbr' => 'Istitle So Nbr',
            'istitle_status' => '0已保存1提交審核2審核通過3審核駁回',
            'istitle_editor' => 'Istitle Editor',
            'brand2' => '用於管控利潤率',
            'center' => 'Center',
            'bu' => 'Bu',
            'commodity' => 'Commodity',
            'pricedate' => 'Pricedate',
            'isvalid' => '是否有效 1是 0否',
            'validstatus' => '料号是否有效审核状态  0审核中 1已审核 -1驳回',
            'valid_so_nbr' => '料号有效无效申请单号',
        ];
    }
    //获取储位码
    public static function getBsMaun($part_no)
    {
        return self::find()->select('pdt_name,unit')->where(['part_no'=>$part_no])->one();
    }

}