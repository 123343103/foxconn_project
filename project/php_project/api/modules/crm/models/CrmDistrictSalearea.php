<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsDistrict;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "crm_bs_district_salearea".
 *
 * @property integer $id
 * @property integer $csarea_id
 * @property string $district_id
 */
class CrmDistrictSalearea extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_bs_district_salearea';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'csarea_id','city_id'], 'integer'],
//            [['csarea_id'], 'required'],
            [['district_id'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'csarea_id' => '军区ID',
            'district_id' => '行政地区ID',
            'city_id' => '市ID',
        ];
    }

    public function getSale(){
        return $this->hasOne(CrmSalearea::className(),['csarea_id'=>'csarea_id']);
    }

    public static function getDisSalearea($id){
//            return CrmDistrictSalearea::find()->where(['district_id'=>$id])->one();
        return (new Query())->select([CrmSalearea::tableName().'.csarea_name',CrmDistrictSalearea::tableName().'.csarea_id'])->from(CrmDistrictSalearea::tableName())->leftJoin(CrmSalearea::tableName(),CrmSalearea::tableName().'.csarea_id='.CrmDistrictSalearea::tableName().'.csarea_id')->where([CrmDistrictSalearea::tableName().'.district_id'=>$id])->andWhere([CrmSalearea::tableName().'.csarea_status'=>CrmSalearea::STATUS_DEFAULT])->distinct()->all();
    }

    public static function getDisCity($id,$pid){
//            return CrmDistrictSalearea::find()->where(['district_id'=>$id])->one();
        return (new Query())->select([BsDistrict::tableName().'.district_name'])->from(CrmDistrictSalearea::tableName())->leftJoin(BsDistrict::tableName(),BsDistrict::tableName().'.district_id='.CrmDistrictSalearea::tableName().'.city_id')->where(['and',[CrmDistrictSalearea::tableName().'.district_id'=>$pid],[CrmDistrictSalearea::tableName().'.csarea_id'=>$id]])->distinct()->all();
    }
}
