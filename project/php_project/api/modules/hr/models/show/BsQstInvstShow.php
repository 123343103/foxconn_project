<?php
/**
 * User: G0007903
 * Date: 2017/10/20
 */
namespace app\modules\warehouse\models\show;
use app\modules\hr\models\BsQstInvst;
use yii\data\ActiveDataProvider;

class BsQstInvstShow extends BsQstInvst
{
    public function fields()
    {
        $fields=parent::fields();
        $fields['invst_id']=function(){
            return $this->logCode['invst_id'];
        };
        return $fields;
    }

    public static function getStaffByCode($code)
    {
        $query = BsQstInvstShow::find()->where(['invst_id' => $code
        ]);

        $dataProvider = new ActiveDataProvider([
            'query'=>$query
        ]);
        return $dataProvider->getModels();
    }
}