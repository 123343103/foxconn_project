<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_pwr_dpt".
 *
 * @property string $r_pwr_pdt_id
 * @property integer $type_id
 * @property string $ass_id
 * @property string $org_id
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 */
class RPwrDpt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_pwr_dpt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id'], 'required'],
            [['type_id', 'ass_id', 'org_id', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_pwr_pdt_id' => '部門權限pkid',
            'type_id' => '權限類型，0角色部門，1用戶部門',
            'ass_id' => '關聯pkid.當type_id為0時是角色pkid,erp.auth_item.name；當type_id為1時是用戶pkid, erp.user.user_id',
            'org_id' => '部門pkid,erp. hr_organization.organization_id',
            'opper' => '修改人',
            'opp_date' => '修改時間',
            'opp_ip' => '修改人IP',
        ];
    }
}
