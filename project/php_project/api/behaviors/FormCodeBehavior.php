<?php


namespace app\behaviors;

use app\modules\common\models\BsForm;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii;

/*
 * 自動生成 表單編碼
 * F3858995
 * 2016.10.14
 */class FormCodeBehavior extends AttributeBehavior
{
    /**
     * 表名
     */
    public $formName;

    public $codeField;

    public $value;

    public $model;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->codeField,
            ];
        }
    }


    protected function getValue($event)
    {

        if ($this->value === null) {
            return BsForm::getCode($this->formName,$this->model);
        }
        return parent::getValue($event);
    }
}
