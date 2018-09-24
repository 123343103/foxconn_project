<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/1
 * Time: 15:17
 */
namespace app\modules\hr\models\show;

use app\modules\hr\models\HrStaff;
use yii\data\ActiveDataProvider;


class HrStaffShow extends HrStaff{

    public $id;
    public function fields(){
        $fields = parent::fields();

        $fields['organization_name']=function(){
            return $this->organization['organization_name'];
        };
        $fields['organizationCode']=function(){
            return $this->organization['organization_code'];
        };
        $fields['organizationID']=function(){
            return $this->organization['organization_id'];
        };
        $fields['position']=function(){
            return $this->title['title_name'];
        };
        return $fields;
    }
    public static function getStaffByCode($code)
    {
        $query = HrStaffShow::find()->where(['staff_code' => $code
        ]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query
        ]);
        return $dataProvider->getModels();
    }
}
