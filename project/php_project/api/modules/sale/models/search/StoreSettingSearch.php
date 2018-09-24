<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/2/14
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;

use app\classes\Trans;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\sale\models\show\StoreSettingShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 销售点
 */
class StoreSettingSearch extends CrmStoresinfo
{
    public $sts_code;
    public $sts_sname;
    public $csarea_id;
    public $sts_status;

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['sts_code', 'sts_sname', 'csarea_id', 'sts_status'], 'safe'],
        ];
    }

    /**
     * 场景
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 搜索店铺信息
     */
    public function search($param)
    {
        $trans = new Trans();
        $query = StoreSettingShow::find()->where(['!=', 'sts_status', self::STATUS_DELETE]);

        if(isset($param['rows'])){
            $pageSize = $param['rows'];
        }else{
            if(isset($param['export'])){
                $pageSize =false;
            }else{
                $pageSize =10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        $this->load($param);
        if(empty($this->sts_status)){
            $query->andFilterWhere(['=', "sts_status", 10]);
        }
        if (!empty($param['StoreSettingSearch']['sts_status'])) {
            if ($param['StoreSettingSearch']['sts_status'] == 30) {
                $csarea_status = "";
            }
            if ($param['StoreSettingSearch']['sts_status'] == 10) {
                $csarea_status = 10;
            }
            if ($param['StoreSettingSearch']['sts_status'] == 11) {
                $csarea_status = 11;
            }
            if ($param['StoreSettingSearch']['sts_status'] == 13) {
                $csarea_status = 13;
            }
            if ($param['StoreSettingSearch']['sts_status'] == 14) {
                $csarea_status = 14;
            }
            if ($param['StoreSettingSearch']['sts_status'] == 15) {
                $csarea_status = 15;
            }
        }else{
            $csarea_status="";
        }
        $query->andFilterWhere(['or',
            ['like','crm_bs_storesinfo.sts_code',$trans->t2c(trim($this->sts_code))],
            ['like','crm_bs_storesinfo.sts_code',$trans->c2t(trim($this->sts_code))],
            ['like','crm_bs_storesinfo.sts_sname',$trans->t2c(trim($this->sts_code))],
            ['like','crm_bs_storesinfo.sts_sname',$trans->c2t(trim($this->sts_code))],
            ])
            ->andFilterWhere(['csarea_id' => $this->csarea_id])
            ->andFilterWhere(['=', "crm_bs_storesinfo.sts_status", $csarea_status]);
        return $dataProvider;
    }
}
