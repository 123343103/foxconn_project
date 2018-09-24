<?php


namespace app\behaviors;

use yii\base\InvalidCallException;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii;
/*
 * 自動生成 創建,更新人數據
 * F3858995
 * 2016.9.22
 */
class StaffBehavior extends AttributeBehavior
{
    /**
     * 創建人字段
     */
    public $createdAtAttribute = "create_by";
    /**
     * 更新人字段
     * @var string
     */
    public $updatedAtAttribute = 'update_by';
    /**
     *
     * @var
     */
    public $value;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->createdAtAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
            ];
        }
    }


    protected function getValue($event)
    {
        if ($this->value === null) {
            return Yii::$app->user->identity->staff->staff_id;
        }
        return parent::getValue($event);
    }

    /**
     * 更新更新人信息
     * @param $attribute
     */
    public function touch($attribute)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        if ($owner->getIsNewRecord()) {
            throw new InvalidCallException('Updating the updater is not possible on a new record.');
        }
        $owner->updateAttributes(array_fill_keys((array) $attribute, $this->getValue(null)));
    }
}
