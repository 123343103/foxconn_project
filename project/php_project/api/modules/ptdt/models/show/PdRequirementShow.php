<?php
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdRequirement;

/**
 * F3858995
 * 2016/11/11
 */
class PdRequirementShow extends PdRequirement
{
    public $createBy;
    public $pdqSourceTypeName;
    public $developTypeName;
    public $developCenterName;
    public $developDepartmentName;
    public $productManagerName;
    public $products;
    public $commodityName;

    public function fields()
    {
        $fields = parent::fields();
        $fields['createBy'] = function () {
            return [
                "name"=>$this->creatorStaff['staff_name'],
                "code"=>$this->creatorStaff['staff_code'],
            ];
        };
        $fields['offerName'] = function () {
            return $this->offerStaff['staff_name'];
        };
        $fields['pdqSourceTypeName'] = function () {
            return $this->sourceType['bsp_svalue'];
        };
        $fields['developTypeName'] = function () {
            return $this->developType['bsp_svalue'];
        };
        $fields['developCenterName'] = function () {
            return $this->developCenter['organization_name'];
        };
        $fields['developDepartmentName'] = function () {
            return $this->developDepartment['organization_name'];
        };
        $fields['productManagerName'] = function () {
            return [
                'name'=>$this->productManager['staff_name'],
                'code'=>$this->productManager['staff_code']
            ];
        };
        //商品列
        $fields['products'] = function () {
            return $this->productList;
        };
        $fields['commodityName'] = function () {
            return $this->commodityType->category_sname;
        };
        $fields['status'] = function (){
            switch ($this->pdq_status){
                case self::STATUS_DEFAULT:
                    return '待审核';
                case self::STATUS_REVIEW:
                    return '审核中';
                case self::STATUS_FINISH:
                    return '已通过';
                case self::STATUS_REJECT:
                    return '已驳回';
            }
        };

        return $fields;
    }

}