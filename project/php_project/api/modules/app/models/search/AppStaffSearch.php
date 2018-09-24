<?php

namespace app\modules\app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;
use app\classes\Trans;

/**
 * StaffSearch represents the model behind the search form about `app\modules\hr\models\Staff`.
 */
class AppStaffSearch extends HrStaff
{
    public $organization_name;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function AppSearch($params)
    {
        $query = HrStaffShow::find()->select('staff_id,staff_code,staff_name,hr_staff.organization_code,job_task,position,employment_date,staff_mobile,staff_avatar')->where(["!=", "app_list_top", 10]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $content = $params['content'];

        //UTF8内简繁转换
        $go = new Trans;
        $content = $go->t2c($content);
        $ftcontent = $go->c2t($content);

        $query->joinWith('organization');
        $query->andFilterWhere(['or',
            ['like', 'staff_code', $content],
            ['like', 'english_name', $content],
            ['like', 'staff_sex', $content],
            ['like', 'staff_sex', $ftcontent],
            ['like', 'birth_date', $content],
            ['like', 'staff_age', $content],
            ['like', 'staff_tel', $content],
            ['like', 'staff_mobile', $content],
            ['like', 'hr_organization.organization_name', $content],
            ['like', 'hr_organization.organization_name', $ftcontent],
            ['like', 'staff_name', $ftcontent],
            ['like', 'staff_name', $content]]);

        return $dataProvider;
    }
}
