<?php

namespace app\modules\crm\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\models\User;
use app\modules\common\models\BsDistrict;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Query;

/**
 * This is the model class for table "crm_employee".
 *
 * @property string $staff_id
 * @property string $staff_code
 * @property string $sarole_id
 * @property string $sts_id
 * @property string $sts_superior
 * @property string $sts_boss
 * @property string $leader_id
 * @property string $isrule
 * @property string $leaderrole_id
 * @property string $sale_area
 * @property string $category_id
 * @property string $sale_qty
 * @property string $sale_quota
 */
class CrmEmployee extends Common
{
    /**
     * @inheritdoc
     */
    const LEADER_MANAGER = 0;
    const SALE_STATUS_DEL = 0;
    const SALE_STATUS_DEFAULT = 20;
    const SALE_STATUS_OUT = 10;//离职
    const SALE_MANAGER_Y = 1;//是经理人
    const SALE_MANAGER_N = 0;//不是经理人

    public static function tableName()
    {
        return 'crm_employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_code'], 'required', 'message' => '{attribute}必填'],
//            [['staff_code'], 'unique','targetAttribute' => 'staff_code', 'message' => '{attribute}已经存在'],
            [['staff_code'], 'unique', 'filter' => ['!=', 'sale_status', CrmEmployee::SALE_STATUS_DEL], 'targetAttribute' => 'staff_code', 'message' => '{attribute}已经存在!', 'on' => ["create"]],
            [['staff_code'], 'exist', 'filter' => ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT], 'targetAttribute' => 'staff_code', 'message' => '销售员不存在!', 'on' => ["get"]],
            [['staff_id', 'sarole_id', 'sts_id', 'leader_id', 'leaderrole_id', 'create_by', 'update_by', 'isrule', 'sale_status'], 'integer'],
            [['sale_qty', 'sale_quota'], 'number'],
            [['category_id', 'create_at', 'update_at'], 'safe'],
            [['staff_code'], 'string', 'max' => 255],
            [['sts_superior', 'sts_boss'], 'string', 'max' => 10],
            [['sale_area'], 'string', 'max' => 20],
            [['sale_status'], 'default', 'value' => self::SALE_STATUS_DEFAULT],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => 'Staff ID',
            'staff_code' => '销售人员工号',
            'sarole_id' => '销售角色',
            'sts_id' => '所在销售点',
            'sts_superior' => '对应店长',
            'sts_boss' => '对应省长',
            'leader_id' => '關係人員表,对应上司',
            'leaderrole_id' => '对应上司角色',
            'sale_area' => '關係區域表,允许销售区域',
            'category_id' => '關聯商品類型表,允许销售商品类别',
            'sale_qty' => '个人销售提成系数',
            'sale_quota' => '个人销售目标指数',
            'sale_status' => '销售员状态',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        //各个场景的活动属性
        $scenarios['create'] = ['staff_code'];
        $scenarios['get'] = ['staff_code'];
        return $scenarios;
    }

    /*销售点*/
    public function getStoreInfo()
    {
        return $this->hasOne(CrmStoresinfo::className(), ['sts_id' => 'sts_id']);
    }

    /*公司地址*/
    public function getDistrict()
    {
        $disId = $this->hasOne(BsDistrict::className(), ['district_id' => 'district_id'])->via('storeInfo');
        return $disId;
    }

    public function getDistrict2()
    {
        $disId2 = $this->hasOne(BsDistrict::className(), ['district_id' => 'district_pid'])->via('district');
        return $disId2;
    }

    public function getDistrict3()
    {
        $disId3 = $this->hasOne(BsDistrict::className(), ['district_id' => 'district_pid'])->via('district2');
        return $disId3;
    }

    public function getDistrict4()
    {
        $disId4 = $this->hasOne(BsDistrict::className(), ['district_id' => 'district_pid'])->via('district3');
        return $disId4;
    }

    /*客户经理人*/
    public static function getManagerInfo()
    {
        return static::find()->joinWith('staffName')->where(['and', ['isrule' => CrmEmployee::SALE_MANAGER_Y], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->groupBy('staff_name')->asArray()->all();
    }
    public static function getManager($userId)
    {
        if(User::isSupper($userId) == false){
            /*F1678086 11/22 根据登录人角色权限选择客户经理人*/
            $ass = 'SELECT GROUP_CONCAT(item_name) as a FROM auth_assignment WHERE auth_assignment.user_id='.$userId;
            $rpw = 'SELECT GROUP_CONCAT(org_id) as b FROM r_pwr_dpt WHERE FIND_IN_SET(ass_id,('.$ass.')) AND type_id = 0';
            $org = 'SELECT GROUP_CONCAT(organization_code) as c FROM hr_organization WHERE FIND_IN_SET(organization_id,('.$rpw.')) AND organization_state = 10';
            $staff = 'SELECT GROUP_CONCAT(staff_code) as d FROM hr_staff WHERE FIND_IN_SET(organization_code,('.$org.')) AND staff_status = 10';
            $code = 'SELECT GROUP_CONCAT(staff_code) as e FROM crm_employee WHERE FIND_IN_SET(staff_code,('.$staff.')) AND sale_status != '.CrmEmployee::SALE_STATUS_DEL.' AND isrule = '.CrmEmployee::SALE_MANAGER_Y;
            $str = \Yii::$app->db->createCommand($code)->queryScalar();
            $user = (new Query())->select(['staff_id'])->from('user')->where(['user_id'=>$userId])->one();
            $staffCode = (new Query())->select(['staff_id','staff_name','staff_code'])->from('hr_staff')->where(['staff_id'=>$user['staff_id']])->one();

            $arr = explode(',',$str);
            if(in_array($staffCode['staff_code'],$arr) === false){
                array_push($arr,$staffCode['staff_code']);
            }

            $em = [];
            foreach (array_filter($arr) as $key => $val){
                $aa = (new Query())->select(['staff_id','staff_name','staff_code'])->from('hr_staff')->where(['staff_code'=>$val])->one();
                $em[$key]['staff_id'] = $aa['staff_id'];
                $em[$key]['staff_name'] = $aa['staff_name'];
                $em[$key]['staff_code'] = $aa['staff_code'];
            }
            return $em;
        }
//            $res = static::find()->joinWith('staffName')->where(['and', ['isrule' => CrmEmployee::SALE_MANAGER_Y], ['!=','sale_status',CrmEmployee::SALE_STATUS_DEL]])->groupBy('staff_name')->asArray()->all();
//            $result = [];
//            foreach ($res as $key => $val){
//                $result[$key]['staff_id'] = $val['staffName']['staff_id'];
//                $result[$key]['staff_name'] = $val['staffName']['staff_name'];
//                $result[$key]['staff_code'] = $val['staffName']['staff_code'];
//            }
            $res = (new Query())->select(['hs.staff_id','hs.staff_code','hs.staff_name'])->from('crm_employee em')->leftJoin('hr_staff hs','hs.staff_code = em.staff_code')->where(['and', ['isrule' => CrmEmployee::SALE_MANAGER_Y], ['!=','sale_status',CrmEmployee::SALE_STATUS_DEL]])->groupBy('staff_name')->all();
            $result = [];
            foreach ($res as $key => $val){
                $result[$key]['staff_id'] = $val['staff_id'];
                $result[$key]['staff_name'] = $val['staff_name'];
                $result[$key]['staff_code'] = $val['staff_code'];
            }
            return $result;
    }

    /*销售人员(关联客户经理人)*/
    public static function getSaleInfo($leaderId)
    {
        return static::find()->joinWith('staffName')->andWhere(['and', ['!=','isrule',CrmEmployee::SALE_MANAGER_Y],['leader_id' => $leaderId]])->asArray()->all();
    }

    /*所有销售人员*/
    public static function getAllSales()
    {
        return static::find()->joinWith('staffName')->where(['sarole_id' => '2'])->asArray()->all();
    }

    /*客户经理人带出信息*/
    public static function getManagetRelation($code)
    {
        return static::find()->where(['and', ['isrule' => CrmEmployee::SALE_MANAGER_Y], ['staff_code' => $code], ['sale_status' => CrmEmployee::SALE_STATUS_DEFAULT]])->one();
    }

    /*所在军区*/
    public function getSaleArea()
    {
        return $this->hasOne(CrmSalearea::className(), ['csarea_id' => 'csarea_id'])->via('storeInfo');
    }

    /*关联军区*/
    public function getArea()
    {
        return $this->hasOne(CrmSalearea::className(), ['csarea_id' => 'sale_area']);
    }

    //工号信息
    public function getStaffName()
    {
        return $this->hasOne(HrStaff::className(), ['staff_code' => 'staff_code']);
    }

    //人力类型
    public function getSaleRole()
    {
        return $this->hasOne(CrmSaleRoles::className(), ['sarole_id' => 'sarole_id']);
    }

    //直属上司
    public function getLeader()
    {
        return $this->hasOne(CrmEmployee::className(), ['staff_id' => 'leader_id']);
    }

    //上司角色
    public function getLeaderRole()
    {
        return $this->hasOne(CrmSaleRoles::className(), ['sarole_id' => 'leaderrole_id']);
    }

    /*销售人员是否存在*/
    public function isExistSeller($staff_code)
    {
        $result = self::find()->where(['staff_code' => $staff_code])->one();
        $result = $result ? true : false;
        return $result;
    }
    public static function getEmployeeInfo($staff_code)
    {
        $result=self::find()->where(['staff_code'=>$staff_code])->one();
        return $result;
     }
    public function behaviors()
    {
        return [
//            "formCode" => [
//                "class" => FormCodeBehavior::className(),
//                "codeField" => 'cust_filernumber',
//                "formName" => self::tableName(),
//                "model" => $this
//            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }
}
