<?php
/**
 * User: F1677929
 * Date: 2017/12/4
 */
namespace app\modules\warehouse\controllers;

use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsForm;
use app\modules\warehouse\models\BsReceipt;
use Yii;
use yii\data\SqlDataProvider;

/**
 * 收货中心设置API控制器
 */
class ReceiptCenterSetController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsReceipt';

    //收货中心设置列表
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $sql = "select a.rcp_id,
                     a.rcp_no,
                     a.rcp_name,
                     case a.rcp_status when 'Y' then '启用' when 'N' then '禁用' else '未知' end rcp_status,
                     concat(e.district_name,d.district_name,c.district_name,b.district_name,a.addr) addr,
                     a.contact,
                     a.contact_tel,
                     a.contact_email,
                     a.remarks
              from wms.bs_receipt a 
              left join erp.bs_district b on b.district_id = a.district_id
              left join erp.bs_district c on c.district_id = b.district_pid
              left join erp.bs_district d on d.district_id = c.district_pid
              left join erp.bs_district e on e.district_id = d.district_pid
              where 1 = 1";
        //查询
        if (!empty($params['rcp_no'])) {
            $params['rcp_no'] = str_replace(['%', '_'], ['\%', '\_'], $params['rcp_no']);
            $queryParams[':rcp_no'] = '%' . $params['rcp_no'] . '%';
            $sql .= " and a.rcp_no like :rcp_no";
        }
        if (!empty($params['rcp_name'])) {
            $trans = new Trans();//处理简体繁体
            $params['rcp_name'] = str_replace(['%', '_'], ['\%', '\_'], $params['rcp_name']);
            $queryParams[':rcp_name1'] = '%' . $params['rcp_name'] . '%';
            $queryParams[':rcp_name2'] = '%' . $trans->c2t($params['rcp_name']) . '%';
            $queryParams[':rcp_name3'] = '%' . $trans->t2c($params['rcp_name']) . '%';
            $sql .= " and (a.rcp_name like :rcp_name1 or a.rcp_name like :rcp_name2 or a.rcp_name like :rcp_name3)";
        }
        if (!empty($params['contact'])) {
            $trans = new Trans();//处理简体繁体
            $params['contact'] = str_replace(['%', '_'], ['\%', '\_'], $params['contact']);
            $queryParams[':contact1'] = '%' . $params['contact'] . '%';
            $queryParams[':contact2'] = '%' . $trans->c2t($params['contact']) . '%';
            $queryParams[':contact3'] = '%' . $trans->t2c($params['contact']) . '%';
            $sql .= " and (a.contact like :contact1 or a.contact like :contact2 or a.contact like :contact3)";
        }
        $totalCount = Yii::$app->db->createCommand("select count(*) from ({$sql}) A", empty($queryParams) ? [] : $queryParams)->queryScalar();
        $sql .= " order by a.rcp_id desc";
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params' => empty($queryParams) ? [] : $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($params['rows']) ? false : $params['rows']
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }

    //新增时数据
    private function addData()
    {
        return [
            //国
            'country' => Yii::$app->db->createCommand("select district_id,district_name from erp.bs_district where is_valid = 1 and district_level = 1")->queryAll(),
        ];
    }

    //地址联动
    public function actionGetDistrict($id)
    {
        return BsDistrict::getChildByParentId($id);
    }

    //修改时获取地区
    public function getDistrict($id)
    {
        $districtId = [];
        $districtName = [];
        while ($id > 0) {
            $model = BsDistrict::findOne($id);
            if (empty($model)) {
                return [];
            }
            $districtId[] = $model->district_id;
            $districtName[] = BsDistrict::find()->select('district_id,district_name')->where(['is_valid' => '1', 'district_pid' => $model->district_pid])->all();
            $id = $model->district_pid;
        }
        return [
            'districtId' => array_reverse($districtId),
            'districtName' => array_reverse($districtName),
        ];
    }

    //新增收货中心设置
    public function actionAdd()
    {
        if ($data = Yii::$app->request->post()) {
            $model = new BsReceipt();
            $model->rcp_no = BsForm::getCode('bs_receipt', $model);
            if ($model->load($data)) {
                if ($model->save()) {
                    return $this->success('新增成功', ['code' => $model->rcp_no]);
                } else {
                    return $this->error(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            } else {
                return $this->error('数据加载失败');
            }
        }
        return $this->addData();
    }

    //修改收货中心设置
    public function actionEdit($id)
    {
        if ($data = Yii::$app->request->post()) {
            $model = BsReceipt::findOne($id);
            if ($model->load($data)) {
                if ($model->save()) {
                    return $this->success('修改成功', ['code' => $model->rcp_no]);
                } else {
                    return $this->error(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            } else {
                return $this->error('数据加载失败');
            }
        }
        $data['addData'] = $this->addData();
        $data['editData'] = Yii::$app->db->createCommand("select * from wms.bs_receipt where rcp_id = :rcp_id", [':rcp_id' => $id])->queryOne();
        if (!empty($data['editData']['district_id'])) {
            $data['editData']['edit_addr'] = $this->getDistrict($data['editData']['district_id']);
        }
        return $data;
    }

    //查看收货中心设置
    public function actionView($id)
    {
        $sql = "select a.rcp_no,
                     a.rcp_name,
                     a.contact,
                     a.contact_tel,
                     a.contact_email,
                     case rcp_status when 'Y' then '启用' when 'N' then '禁用' else '未知' end rcp_status,
                     concat(e.district_name,d.district_name,c.district_name,b.district_name,a.addr) addr,
                     a.remarks
              from wms.bs_receipt a 
              left join erp.bs_district b on b.district_id = a.district_id
              left join erp.bs_district c on c.district_id = b.district_pid
              left join erp.bs_district d on d.district_id = c.district_pid
              left join erp.bs_district e on e.district_id = d.district_pid
              where a.rcp_id = :id";
        return Yii::$app->db->createCommand($sql, [':id' => $id])->queryOne();
    }

    //启用禁用收货中心设置
    public function actionOperation($id, $flag)
    {
        $id = explode('-', $id);
        if ($flag == "enabled") {
            $rcp_status = 'Y';
            $msg = "启用成功";
        }
        if ($flag == "disabled") {
            $rcp_status = 'N';
            $msg = "禁用成功";
        }
        foreach ($id as $key => $val) {
            $model = BsReceipt::findOne($val);
            if (!empty($rcp_status)) {
                $model->rcp_status = $rcp_status;
            }
            $model->save(false);
        }
        return $this->success(empty($msg) ? "参数错误" : $msg);
    }

    //抓取收货中心信息(用于采购)
    public function actionReceiptInfo()
    {
        $params = Yii::$app->request->queryParams;
        if (!empty($params['rcp_no'])) {
            $sql = "select * from wms.bs_receipt r where r.rcp_status='Y' and r.rcp_no='{$params['rcp_no']}' or r.rcp_name='{$params['rcp_no']}'";
        } else {
            $sql = "select * from wms.bs_receipt r where r.rcp_status='Y'";
        }
        $totalCount = Yii::$app->get('wms')->createCommand("select count(*) from ( {$sql} ) a")->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($params['rows']) ? false : $params['rows']
            ]
        ]);
        $list['rows'] = $provider->getModels();
        $list['total'] = $provider->totalCount;
        return $list;

    }
}
