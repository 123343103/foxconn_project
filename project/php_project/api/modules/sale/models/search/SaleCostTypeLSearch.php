<?php

namespace app\modules\sale\models\search;

/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostTypeL]].
 *
 * @see \app\modules\sale\models\SaleCostTypeL
 */
class SaleCostTypeLSearch extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\sale\models\SaleCostTypeL[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\sale\models\SaleCostTypeL|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
