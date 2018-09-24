<?php
namespace app\modules\app\models\show;

use Yii;
use yii\base\Model;
use yii\bootstrap\Html;
use app\classes\Trans;
use app\modules\ptdt\models\PdVisitPlan;
use yii\data\ActiveDataProvider;
use app\modules\common\models\BsCompany;
use app\modules\ptdt\models\show\PdFirmShow;


class FirmVisitPlanShow extends PdVisitPlan
{
	public function fields()
    {
        $fields = parent::fields();

        $fields['planType'] = function(){
            return $this->planType['bsp_svalue']?$this->planType['bsp_svalue']:"";
        };
        $fields['purpose'] = function(){
            return $this->visitPurpose['bsp_svalue']?$this->visitPurpose['bsp_svalue']:"";
        };
        $fields['purpose_num'] = function(){
            return $this->purpose;
        };
        $fields['staffPerson']=function(){
            return $this->creatorStaff['staff_name']?$this->creatorStaff['staff_name']:"";

        };
        $fields['visitPerson'] = function(){
            return [
                'id'=>$this->visitManager['staff_id'],
                'name'=>$this->visitManager['staff_name'],
                'code'=>$this->visitManager['staff_code'],
                'job'=>$this->visitManager['job_task'],
                'mobile'=>$this->visitManager['staff_mobile'],
            ];
        };
        $fields['visitName'] = function(){
            return $this->visitManager['staff_name']?$this->visitManager['staff_name']:'';
        };
        $fields['firm']=function(){
            return $this->firm;
        };
        $fields['firm_sname'] = function(){
            return $this->firm['firm_sname'];
        };
        $fields['accompany']=function(){
            return $this->staff;
        };
        $fields['note'] = function(){
            return $this->note;
        };
        $fields['pvp_contact_man'] = function(){
            return $this->pvp_contact_man;
        };
        $fields['pvp_contact_position'] = function(){
            return $this->pvp_contact_position;
        };
        $fields['plan_place'] = function(){
            return $this->plan_place;
        };
        $fields['purpose_write'] = function(){
            return $this->purpose_write;
        };
        $fields['status'] = function(){
            switch ($this->plan_status){
                case 10:
                    return "新增";
                break;
                case 20:
                    return "执行中";
                break;
                case 30:
                    return "执行完成";
                break;
                default:
                    return "删除";
            }
        };
        return $fields;
    }
	
	
	public function search($params,$companyId)
    {
    	$content = $params['content'];	
        $query = FirmVisitPlanShow::find();
		$query->where(['!=','pvp_status',self::STATUS_DELETE]);
		//$query->andWhere(['in','company_id',BsCompany::getIdsArr($companyId)]);
        if(! empty($content)){
        	//UTF8内简繁转换
	        $go = new Trans;
			$content = $params['content'];
	        $content = $go->t2c($content);
	        $ftcontent = $go->c2t($content);
        	$query->joinWith('firmType');
			$query->andFilterWhere(['or',
	            ['like', 'pd_firm.firm_sname', $content],
	            ['like', 'pd_firm.firm_sname', $ftcontent],
	            ['like', 'pd_firm.firm_shortname', $content],
	            ['like', 'pd_firm.firm_shortname', $ftcontent],
	        ]);
        }
        
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
		
        if (!\Yii::$app->request->get('sort')){
            $query->orderBy("pd_visit_plan.create_at desc");
        }

        return $dataProvider;
		
	}
}
?>