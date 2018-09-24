<?php
/**
 * User: F1676624
 * Date: 2017/1/12
 */
namespace app\modules\app\models\show;

use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use yii\data\ActiveDataProvider;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\classes\Trans;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use app\modules\hr\models\HrStaff;
use app\modules\crm\models\show\CrmCustomerInfoShow;

class CustomerAppShow extends CrmCustomerInfo
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['cust_id'] = function () {
            return $this->cust_id;
        };
        $fields['cust_sname'] = function () {
            return $this->cust_sname;
        };
        $fields['address'] = function () {
            return !empty($this->district) ? $this->district[0]['district_name'] . $this->district[1]['district_name'] . $this->district[2]['district_name'] . $this->district[3]['district_name'] . $this->cust_adress : $this->cust_adress;
        };
        $fields['custType'] = function () {
            return $this->custType['bsp_svalue'];
        };

        $fields['managerName'] = function () {
            return $this->manager['staff_name'];
        };
        $fields['managerCode'] = function () {
            return $this->manager['staff_code'];
        };

        $fields['saleArea'] = function () {
            return $this->saleArea['csarea_name'];
        };
        $fields['reqitemClass'] = function () {
            return $this->productType['catg_name'];
        };
        $fields['district'] = function () {
            return $this->district;
        };
        /*修改,详情页主要联系人*/
        $fields['contactPersons'] = function () {
            return $this->contactPersons;
        };
        return $fields;
    }

    /*移动端客户查询*/
    public function AppSearch($params)
    {
        $query = CustomerAppShow::find()->joinWith('status')->joinWith('personinch')->andWhere(["!=", "sale_status", CrmCustomerStatus::STATUS_DEL])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])])->orderBy('create_at DESC');

        $staffcode = HrStaff::find()->where(['staff_id' => $params['staffId']])->select('staff_code')->one();
        $staff = CrmEmployee::find()->where(['staff_code' => $staffcode['staff_code']])->select('isrule,leader_id')->one();
        if ($staff['isrule'] == 1) {
            $query->andWhere([
                'or',
                [
                    'and',
                    [CrmCustPersoninch::tableName() . '.ccpich_personid' => $params['staffId']],
                    [CrmCustPersoninch::tableName() . '.ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES],
                    [CrmCustPersoninch::tableName() . '.ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT]
                ],
                [
                    'and',
//                    ['is', CrmCustPersoninch::tableName() . '.ccpich_personid', null],// 过滤认领没反写客户表状态的被查出
                    ['=', CrmCustomerInfo::tableName() . '.personinch_status', CrmCustomerInfo::PERSONINCH_NO]

                ]
//                    ['is',CrmCustPersoninch::tableName().'.ccpich_personid',null]
            ]);
        } else {
            $code = CrmEmployee::find()->where(['staff_id' => $staff['leader_id']])->select('staff_code')->one();
            $staffId = HrStaff::find()->where(['staff_code' => $code['staff_code']])->select('staff_id')->one();
            $query->andWhere([
                'or',
                [
                    'and',
                    [CrmCustPersoninch::tableName() . '.ccpich_personid' => $staffId['staff_id']],
                    [CrmCustPersoninch::tableName() . '.ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES],
                ],
//                    ['is',CrmCustPersoninch::tableName().'.ccpich_personid',null]
                ['=', CrmCustomerInfo::tableName() . '.personinch_status', CrmCustomerInfo::PERSONINCH_NO]
            ]);
        }
        $query->groupBy(['cust_id', 'cust_sname']);
//        $query->where(['crm_bs_customer_personinch.ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES])->andWhere(["=", "crm_bs_customer_personinch.ccpich_personid", $id])->orWhere(["crm_bs_customer_info.personinch_status" => 0]);
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
        $type = isset($params['type']) ? $params['type'] : null;
        $area = isset($params['area']) ? $params['area'] : null;
        $province = isset($params['province']) ? $params['province'] : null;
        $city = isset($params['city']) ? $params['city'] : null;
        $country = isset($params['country']) ? $params['country'] : null;

//UTF8内简繁转换
        $go = new Trans;
        $content = $go->t2c($content);
        $ftcontent = $go->c2t($content);

        $query->andFilterWhere(['or',
            ['like', 'cust_sname', $content],
            ['like', 'cust_sname', $ftcontent],
            ['like', 'cust_shortname', $content],
            ['like', 'cust_shortname', $ftcontent],
            ['like', 'cust_ename', $content],
            ['like', 'cust_eshortname', $content],
            ['like', 'cust_compvirtue', $content],
            ['like', 'cust_compvirtue', $ftcontent],
            ['like', 'cust_contacts', $content],
            ['like', 'cust_contacts', $ftcontent],
            ['like', 'cust_inchargeperson', $content],
            ['like', 'cust_inchargeperson', $ftcontent],
            ['like', 'cust_adress', $content],
            ['like', 'cust_adress', $ftcontent],
            ['like', 'cust_code', $content]]);
        if (!empty($type)) {
            $query->andFilterWhere(['cust_type' => $type]);
        }
        if (!empty($area)) {
            $query->andFilterWhere(['cust_salearea' => $area]);
        }
        //地址
        if (!empty($country)) {
            $query->andFilterWhere(['in', 'cust_district_2', $this->getCountry($country)]);
        } else if (!empty($city)) {
            $query->andFilterWhere(['in', 'cust_district_2', $this->getCountry($city)]);
        } else if (!empty($province)) {
            $query->andFilterWhere(['in', 'cust_district_2', $this->getCountry($province)]);
        }
        return $dataProvider;
//        return $query->createCommand()->rawSql;
    }
    /*移动端客户认领列表查询*/
    public function AppPersoninchSearch($params)
    {
        $query = CustomerAppShow::find()->joinWith('status')->joinWith('personinch')->andWhere(["!=", "sale_status", CrmCustomerStatus::STATUS_DEL])->andWhere(['in', 'company_id', BsCompany::getIdsArr($params['companyId'])])->orderBy('create_at DESC');
        //获得我认领的客户
        $myCust = CrmCustPersoninch::find()->select('cust_id')->where(['ccpich_personid'=>$params['staffId']])->asArray();

        $staffcode = HrStaff::find()->where(['staff_id' => $params['staffId']])->select('staff_code')->one();
        $staff = CrmEmployee::find()->where(['staff_code' => $staffcode['staff_code']])->select('isrule,leader_id')->one();
//        if ($staff['isrule'] == 1) {
            $query->andWhere([
                'or',
                [
                    'and',
                    ['not in',CrmCustomerInfo::tableName() . '.cust_id',$myCust],//查询我未认领的
                    [CrmCustPersoninch::tableName() . '.ccpich_stype' => CrmCustPersoninch::PERSONINCH_SALES],
                    [CrmCustPersoninch::tableName() . '.ccpich_status' => CrmCustPersoninch::STATUS_DEFAULT]
                ],

                    ['=', CrmCustomerInfo::tableName() . '.personinch_status', CrmCustomerInfo::PERSONINCH_NO]


            ]);
//        }
        $query->groupBy(['cust_id', 'cust_sname']);

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
        $type = isset($params['type']) ? $params['type'] : null;
        $area = isset($params['area']) ? $params['area'] : null;
        $province = isset($params['province']) ? $params['province'] : null;
        $city = isset($params['city']) ? $params['city'] : null;
        $country = isset($params['country']) ? $params['country'] : null;

//UTF8内简繁转换
        $go = new Trans;
        $content = $go->t2c($content);
        $ftcontent = $go->c2t($content);

        $query->andFilterWhere(['or',
            ['like', 'cust_sname', $content],
            ['like', 'cust_sname', $ftcontent],
            ['like', 'cust_shortname', $content],
            ['like', 'cust_shortname', $ftcontent],
            ['like', 'cust_ename', $content],
            ['like', 'cust_eshortname', $content],
            ['like', 'cust_compvirtue', $content],
            ['like', 'cust_compvirtue', $ftcontent],
            ['like', 'cust_contacts', $content],
            ['like', 'cust_contacts', $ftcontent],
            ['like', 'cust_inchargeperson', $content],
            ['like', 'cust_inchargeperson', $ftcontent],
            ['like', 'cust_adress', $content],
            ['like', 'cust_adress', $ftcontent],
            ['like', 'cust_code', $content]]);
        if (!empty($type)) {
            $query->andFilterWhere(['cust_type' => $type]);
        }
        if (!empty($area)) {
            $query->andFilterWhere(['cust_salearea' => $area]);
        }
        //地址
        if (!empty($country)) {
            $query->andFilterWhere(['in', 'cust_district_2', $this->getCountry($country)]);
        } else if (!empty($city)) {
            $query->andFilterWhere(['in', 'cust_district_2', $this->getCountry($city)]);
        } else if (!empty($province)) {
            $query->andFilterWhere(['in', 'cust_district_2', $this->getCountry($province)]);
        }
        return $dataProvider;
    }

    //获取区县数组
    function getCountry($id)
    {
        $countries = array();
        $district = BsDistrict::findOne($id);
        if ($district->district_level != 4) {
            $districts = $district->getChildByParentId($id);
            foreach ($districts as $k => $v) {
                $countries = array_merge($countries, $this->getCountry($v["district_id"]));
            }
        } else {
            $countries[] = $district["district_id"];
        }
        return $countries;
    }
}