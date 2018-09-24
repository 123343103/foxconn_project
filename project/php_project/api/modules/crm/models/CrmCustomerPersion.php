<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;
use app\modules\common\models\BsDistrict;
/**
 * This is the model class for table "crm_bs_custconetion_persion".
 *
 * @property string $ccper_id
 * @property string $cust_id
 * @property string $ccper_name
 * @property string $ccper_sex
 * @property string $ccper_birthday
 * @property string $ccper_birthplace
 * @property string $ccper_age
 * @property string $ccper_deparment
 * @property string $ccper_post
 * @property string $ccper_tel
 * @property string $ccper_fax
 * @property string $ccper_mobile
 * @property string $ccper_mail
 * @property string $ccper_wechat
 * @property string $ccper_qq
 * @property string $ccper_hobby
 * @property string $ccper_relationship
 * @property string $ccper_isshareholder
 * @property string $ccper_ispost
 * @property string $ccper_ismain
 * @property string $ccper_status
 * @property string $ccper_remark
 */
class CrmCustomerPersion extends Common
{
    /**
     * @inheritdoc
     */
    const POST_LEAVE = '0';                  //离职
    const POST_ON = '1';                     //在职
    const STATUS_DELETE = '0';              //删除
    const STATUS_DEFAULT = '10';           //正常
    const SHAREHOLDER_NO = '0';           //不是股东
    const SHAREHOLDER_YES = '1';           //是股东

    public static function tableName()
    {
        return 'crm_bs_customer_persion';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'ccper_age'], 'integer'],
            [['ccper_birthday'], 'safe'],
            [['ccper_birthplace', 'ccper_post', 'ccper_tel', 'ccper_mobile', 'ccper_qq', 'ccper_relationship'], 'string', 'max' => 20],
            [['ccper_sex'], 'string', 'max' => 4],
            [['ccper_deparment', 'ccper_fax', 'ccper_wechat'], 'string', 'max' => 255],
            [['ccper_hobby'], 'string', 'max' => 120],
            [['ccper_name','ccper_mail'], 'string', 'max' => 100],
            [['ccper_isshareholder', 'ccper_ispost', 'ccper_ismain', 'ccper_status'], 'string', 'max' => 2],
            [['ccper_remark'], 'string', 'max' => 200],
            [['ccper_status'],'default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ccper_id' => 'ID',
            'cust_id' => '关联客户信息表id',
            'ccper_name' => '联系人姓名',
            'ccper_sex' => '性别',
            'ccper_birthday' => '生日',
            'ccper_birthplace' => '籍贯',
            'ccper_age' => '年龄',
            'ccper_deparment' => '部门',
            'ccper_post' => '职务',
            'ccper_tel' => '联系电话',
            'ccper_fax' => '传真',
            'ccper_mobile' => '手机',
            'ccper_mail' => '邮箱',
            'ccper_wechat' => '微信',
            'ccper_qq' => 'QQ',
            'ccper_hobby' => '爱好',
            'ccper_relationship' => '社会关系',
            'ccper_isshareholder' => '是否股东',
            'ccper_ispost' => '是否在职',
            'ccper_ismain' => '是否主要联系人',
            'ccper_status' => '状态',
            'ccper_remark' => '备注',
        ];
    }
    /*籍贯*/
    public function getArea(){
        return $this->hasOne(BsDistrict::className(),['district_id'=>'ccper_birthplace']);
    }
    public static function getContacts($condition){
        return self::find()->where($condition)->andWhere(['<>','ccper_status',self::STATUS_DELETE])->orderBy('ccper_id DESC')->all();
    }
}
