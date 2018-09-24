<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "lqt_express".
 *
 * @property string $expr_id
 * @property string $lqt_no
 * @property integer $itemno
 * @property string $costno
 * @property string $costname
 * @property string $uom
 * @property string $weightmin
 * @property string $firstprice
 * @property string $nextweight
 * @property string $next_rate
 * @property string $min_value
 * @property string $max_value
 * @property string $chargemin
 * @property string $chargemax
 * @property string $transittime
 * @property string $effectdate
 * @property string $expiredate
 * @property string $remark
 * @property string $costconfirmeddate
 * @property string $STATUS
 * @property string $transittime1
 * @property string $transittime2
 */
class LqtExpress extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lqt_express';
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
            [['lqt_no'], 'required'],
            [['itemno','lqt_id'], 'integer'],
            [['weightmin', 'firstprice', 'nextweight', 'next_rate', 'min_value', 'max_value', 'chargemin', 'chargemax'], 'number'],
            [['effectdate', 'expiredate', 'costconfirmeddate'], 'safe'],
            [['lqt_no', 'costno', 'uom', 'transittime', 'STATUS', 'transittime1', 'transittime2'], 'string', 'max' => 30],
            [['costname'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 250],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'expr_id' => 'PKID',
            'lqt_id' => '報價單號',
            'itemno' => '項次',
            'costno' => '費用項目代碼',
            'costname' => '費用項目名稱',
            'uom' => '計價單位',
            'weightmin' => '首重重量',
            'firstprice' => '首重價格',
            'nextweight' => '續重重量',
            'next_rate' => '續重價格',
            'min_value' => '區間下限',
            'max_value' => '區間上限',
            'chargemin' => '最小收費',
            'chargemax' => '最高收費',
            'transittime' => '配送時效',
            'effectdate' => '生效日期',
            'expiredate' => '截止日期',
            'remark' => '備注',
            'costconfirmeddate' => '經管確認日期',
            'STATUS' => '報價單狀態',
            'transittime1' => '配送時效：地級市及以上時效',
            'transittime2' => '配送時效：縣級及以下區域時效',
        ];
    }
 //关联报价单头表
    public function getLqtno()
    {
        return $this->hasOne(LqtHead::className(),['lqt_id'=>'lqt_id']);
    }
//獲取快遞報價詳細信息
    public static function getWeightMin($Weight, $lqtid)
    {
        $queryParams = [];
        $queryParams[':min_value'] = $Weight;
        $queryParams[':max_value'] = $Weight;
        $queryParams[':lqt_id'] = $lqtid;
        $sql = " SELECT 
 T.min_value,
 T.max_value,
 IFNULL(T.weightmin,1) weightmin,
 IFNULL(T.firstprice,0) firstprice,
 IFNULL(T.nextweight,1) nextweight,
 IFNULL(T.next_rate,0) next_rate,
 IFNULL(T.transittime,0) transittime
 FROM lqt_express T
 WHERE T.STATUS = 'Confirm'
 AND T.lqt_id = :lqt_id
 AND T.min_value <= :min_value
 AND T.max_value >= :max_value";

        return self::getDb()->createCommand($sql,$queryParams)->queryAll();
    }

    public static function getOtherInterval($lqtid,$pdtMinWeight,$pdtWeightMin)
    {
        $queryParams=[];
        $queryParams[':lqtid']=$lqtid;
        $queryParams[':min_value']=$pdtMinWeight;
        $queryParams[':weightmin']=$pdtWeightMin;
        $sql=" SELECT 
 T.min_value,
 T.max_value,
 IFNULL(T.nextweight,1) nextweight,
 IFNULL(T.next_rate,0) next_rate,
 IFNULL(T.transittime,0) transittime
 FROM lqt_express T
 WHERE T.status = 'Confirm'
 AND T.lqt_id = :lqtid
 AND T.max_value <= :min_value
 AND T.min_value > :weightmin";
        file_put_contents('log.txt', self::getDb()->createCommand($sql,$queryParams)->getRawSql());
        return self::getDb()->createCommand($sql,$queryParams)->queryAll();
    }
}
