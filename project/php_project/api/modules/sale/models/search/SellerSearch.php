<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/19
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\show\SellerShow;
use app\modules\sale\models\show\StoreSettingShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * 搜索销售员列表
 */
class SellerSearch extends SellerShow
{
//    public $sts_code;
//    public $sts_sname;
//    public $csarea_id;
//    public $sts_status;
//    public $sellerInfo;
    public $staff_code;
    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['staff_code', 'staff_name', 'csarea_name'], 'safe'],
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
     * 模糊查询销售员列表
     */
    public function search($param)
    {
        $query = SellerShow::find();

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
        $this->load($param);
        $query->joinWith("area");
        $query->joinWith("staffName");
        $query->andFilterWhere(['like', "crm_employee.staff_code", $this->staff_code])
            ->orFilterWhere(['like', "hr_staff.staff_name", $this->staff_code])
            ->orFilterWhere(['like', "crm_bs_salearea.csarea_name", $this->staff_code]);
        return $dataProvider;
    }
}
