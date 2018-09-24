<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_wh".
 *
 * @property string $wh_code
 * @property string $wh_name
 * @property integer $DISTRICT_ID
 * @property string $wh_addr
 * @property string $wh_state
 * @property string $wh_type
 * @property string $wh_lev
 * @property string $wh_attr
 * @property string $wh_YN
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $NWER
 * @property string $NW_DATE
 * @property string $wh_nature
 * @property string $opp_ip
 * @property string $wh_YNw
 * @property BsPart[] $bsParts
 * @property BsPrice[] $bsPrices
 * @property WhAdm[] $whAdms
 */
class BsWhM extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_wh';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wh_code', 'wh_name','people','company', 'wh_state', 'wh_type', 'wh_lev', 'wh_YN', 'wh_YNw','wh_nature'], 'required'],
            [['DISTRICT_ID','wh_id'], 'integer'],
            [['OPP_DATE', 'NW_DATE'], 'safe'],
            [['wh_code', 'OPPER', 'NWER','company'], 'string', 'max' => 30],
            [['wh_name', 'wh_attr', 'remarks','people'], 'string', 'max' => 200],
            [['wh_addr', 'opp_ip'], 'string', 'max' => 20],
            [['wh_state', 'wh_type', 'wh_lev', 'wh_YN','wh_YNw', 'wh_nature'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'wh_code' => 'Wh Code',
            'wh_name' => 'Wh Name',
            'DISTRICT_ID' => 'District  ID',
            'people'=>'people',
            'company'=>'company',
            'wh_addr' => 'Wh Addr',
            'wh_state' => 'Wh State',
            'wh_type' => 'Wh Type',
            'wh_lev' => 'Wh Lev',
            'wh_attr' => 'Wh Attr',
            'wh_YN' => 'Wh  Yn',
            'wh_YNw' => 'Wh  YNw',
            'remarks' => 'Remarks',
            'OPPER' => 'Opper',
            'OPP_DATE' => 'Opp  Date',
            'NWER' => 'Nwer',
            'NW_DATE' => 'Nw  Date',
            'wh_nature' => 'Wh Nature',
            'opp_ip' => 'Opp Ip',
            'wh_id' => '倉庫PKID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsParts()
    {
        return $this->hasMany(BsPart::className(), ['wh_code' => 'wh_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsPrices()
    {
        return $this->hasMany(BsPrice::className(), ['wh_code' => 'wh_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhAdms()
    {
        return $this->hasMany(WhAdm::className(), ['wh_code' => 'wh_code']);
    }
    //查找所有的仓库名称
    public static  function getWhCodeAll($select='wh_name,wh_code,wh_id'){
        //return self::find()->select($select)->asArray()->all();
        return self::find()->select($select)->asArray()->all();
    }
}
