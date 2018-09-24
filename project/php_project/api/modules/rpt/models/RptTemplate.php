<?php

namespace app\modules\rpt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "rpt_template".
 *
 * @property integer $rptt_id
 * @property integer $rptt_cat
 * @property string $rptt_code
 * @property string $rptt_name
 * @property string $rptt_title
 * @property string $rptt_type
 * @property integer $rptt_pid
 * @property integer $rptt_sort
 * @property string $rptt_height
 * @property string $rptt_width
 * @property string $rptt_isborder
 * @property string $rptt_bg
 * @property string $rptt_dtype
 * @property string $rptt_tempsql
 * @property string $rptt_status
 * @property string $rptt_descr
 * @property string $create_by
 * @property string $cdate
 * @property string $update_by
 * @property string $udate
 */
class RptTemplate extends Common
{
    const READ_STATUS=10;// 未读状态
    const UNREAD_STATUS=20;// 已读状态

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rpt_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rptt_cat', 'rptt_pid', 'rptt_sort', 'create_by', 'update_by'], 'integer'],
            [['cdate', 'udate'], 'safe'],
            [['rptt_code', 'rptt_height', 'rptt_width', 'rptt_bg', 'rptt_dtype'], 'string', 'max' => 20],
            [['rptt_name', 'rptt_title'], 'string', 'max' => 100],
            [['rptt_type', 'rptt_isborder', 'rptt_status'], 'string', 'max' => 2],
            [['rptt_tempsql'], 'string', 'max' => 1000],
            [['rptt_descr'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rptt_id' => 'Rptt ID',
            'rptt_cat' => 'Rptt Cat',
            'rptt_code' => 'Rptt Code',
            'rptt_name' => 'Rptt Name',
            'rptt_title' => 'Rptt Title',
            'rptt_type' => 'Rptt Type',
            'rptt_pid' => 'Rptt Pid',
            'rptt_sort' => 'Rptt Sort',
            'rptt_height' => 'Rptt Height',
            'rptt_width' => 'Rptt Width',
            'rptt_isborder' => 'Rptt Isborder',
            'rptt_bg' => 'Rptt Bg',
            'rptt_dtype' => 'Rptt Dtype',
            'rptt_tempsql' => 'Rptt Tempsql',
            'rptt_status' => 'Rptt Status',
            'rptt_descr' => 'Rptt Descr',
            'create_by' => 'Create By',
            'cdate' => 'Cdate',
            'update_by' => 'Update By',
            'udate' => 'Udate',
        ];
    }

//    public function getRpt()
//    {
//        return $this->hasMany(RptData::className(),['rptt_id'=>'rptt_id']);
//    }

    // 根据模板id 取参数列表
    public function getParams()
    {
        $res = $this->hasMany(RptParam::className(),['rptp_tp'=>'rptt_id']);
        return !empty($res) ? $res : [];
    }
}
