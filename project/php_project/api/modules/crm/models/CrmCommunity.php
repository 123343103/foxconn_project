<?php

namespace app\modules\crm\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "crm_community".
 *
 * @property integer $commu_ID
 * @property string $commu_type
 * @property string $cmt_id
 * @property string $cmt_intor
 * @property string $commu_plantype
 * @property string $commu_source
 * @property string $commu_postime
 * @property string $commu_arttitle
 * @property string $commu_link
 * @property string $commu_man
 * @property integer $company_id
 * @property string $commu_remark
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 * @property integer $commu_status
 * @property string $act_start_time
 * @property string $act_end_time
 * @property integer $act_type
 * @property string $private_commu_account
 * @property string $cust_account
 * @property string $cust_name
 * @property string $cust_contcats
 * @property string $cust_cmp_name
 * @property string $cust_cmp_district
 * @property string $cust_cmp_address
 * @property integer $email_group
 * @property integer $email_group_number
 *
 * @property CrmCommunityChild[] $crmCommunityChildren
 */
class CrmCommunity extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_community';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'create_by', 'update_by', 'commu_status', 'act_type', 'email_group', 'email_group_number','email_send_num'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['commu_type'], 'string', 'max' => 6],
            [['cmt_id','commu_code', 'commu_man'], 'string', 'max' => 20],
            [['cmt_intor', 'commu_plantype', 'commu_source', 'commu_postime', 'commu_arttitle', 'commu_link', 'commu_remark'], 'string', 'max' => 200],
            [['act_start_time', 'act_end_time','email_send_time', 'private_commu_account', 'cust_account', 'cust_name', 'cust_contcats', 'cust_cmp_name', 'cust_cmp_district', 'cust_cmp_address'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commu_ID' => 'Commu  ID',
            'commu_type' => 'Commu Type',
            'cmt_id' => 'Cmt ID',
            'cmt_intor' => 'Cmt Intor',
            'commu_plantype' => 'Commu Plantype',
            'commu_source' => 'Commu Source',
            'commu_postime' => 'Commu Postime',
            'commu_arttitle' => 'Commu Arttitle',
            'commu_link' => 'Commu Link',
            'commu_man' => 'Commu Man',
            'company_id' => 'Company ID',
            'commu_remark' => 'Commu Remark',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
            'commu_status' => 'Commu Status',
            'act_start_time' => 'Act Start Time',
            'act_end_time' => 'Act End Time',
            'act_type' => 'Act Type',
            'private_commu_account' => 'Private Commu Account',
            'cust_account' => 'Cust Account',
            'cust_name' => 'Cust Name',
            'cust_contcats' => 'Cust Contcats',
            'cust_cmp_name' => 'Cust Cmp Name',
            'cust_cmp_district' => 'Cust Cmp District',
            'cust_cmp_address' => 'Cust Cmp Address',
            'email_group' => 'Email Group',
            'email_group_number' => 'Email Group Number',
        ];
    }


    public static function getCommuTypes($id = "")
    {
        return BsPubdata::find()->select(["bsp_svalue"])->where(["bsp_stype"=>"sssqyxfs","bsp_status"=>10])->asArray()->indexBy("bsp_id")->orderBy("bsp_id asc")->column();
    }

    public static function getPlanTypes()
    {
        $data = BsPubdata::find()->select(["bsp_svalue"])->where(["bsp_stype" => "walx", "bsp_status" => 10])->asArray()->indexBy("bsp_id")->column();
        return $data;
    }

    public static function getPlanFroms()
    {
        $data = [
            "1" => "原创",
            "2" => "转载",
            "3" => "其他"
        ];
        return $data;
    }

    public static function getCarrierTypes($type = "")
    {
        $data=CrmCarrier::find()->select("group_concat(cc_carrier) carriers")->where(["like","cc_sale_way",$type])->andWhere(["cc_status"=>10])->scalar();
        if(!$data){
            return "";
        }
        $pubCarriers=array_unique(explode(",",$data));
        return BsPubdata::find()->select("bsp_svalue")->where(["in","bsp_id",$pubCarriers,'bsp_status'=>10])->asArray()->indexBy("bsp_id")->column();
    }

    public static function getCarrierNames($type,$id)
    {
        $data=CrmCarrier::find()->select("group_concat(cc_name) carrier_names")->where([
            "and",
            ["cc_status"=>10],
            ["like","cc_sale_way",$type],
            ["like","cc_carrier",$id]
        ])->scalar();
        if(!$data){
            return "";
        }
        return array_combine(explode(",",$data),explode(",",$data));
    }

    public static function getActType(){
//        return BsPubdata::find()->select("bsp_svalue")->indexBy("bsp_id")->where(["bsp_stype"=>"hdlx","bsp_status"=>10])->asArray()->column();
        return CrmActiveType::find()
            ->select([
                BsPubdata::tableName().".bsp_svalue"
            ])
            ->leftJoin(BsPubdata::tableName(),BsPubdata::tableName().".bsp_id=".CrmActiveType::tableName().".acttype_name")
            ->where("acttype_status=10")->indexBy("bsp_id")->asArray()->column();
    }

    public static function getStatus()
    {
        $data = [
            "0" => "禁用",
            "1" => "有效"
        ];
        return $data;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrmCommunityChildren()
    {
        return $this->hasMany(CrmCommunityChild::className(), ['commu_ID' => 'commu_ID']);
    }


    public function behaviors(){
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'commu_code',
                "formName" => self::tableName(),
                "model" => $this
            ]
        ];
    }
}
