<?php
/**
 * 组织机构控制器
 * User: F3859386
 * Date: 2016/9/12
 * Time: 上午 11:38
 */
namespace app\modules\hr\controllers;

use app\controllers\BaseActiveController;
use app\modules\hr\models\HrOrganization;
use Yii;
use yii\web\NotFoundHttpException;


class OrganizationController extends BaseActiveController
{

    public $modelClass = 'app\modules\hr\models\HrOrganization';

    /**
     * @return mixed
     */
    public function actionIndex($isIcon = null)
    {
        $model = new HrOrganization();
        $data = $model->getOrgTree(0, $isIcon);
        return $data;
    }

    /**
     * 創建.
     */
    public function actionCreate()
    {
        $model = new HrOrganization();
        $post = Yii::$app->request->post();

        $data = HrOrganization::find()->orWhere(['organization_code' => $post['HrOrganization']['organization_code']])->orWhere(['organization_name' => $post['HrOrganization']['organization_name']])->one();
        if ($data) {
            return $this->error('部门已存在,无法添加');
        }
        $model->load($post);
        $result = $model->save();
        if (!$result) {
            return $this->error('数据错误,无法添加');
        }
        return $this->success($model['organization_name']);
    }

    /**
     * 更新.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        $model->load($post);
        $result = $model->save();
        if ($result) {
            return $this->success($model['organization_name']);
        }
        return $this->error();
    }

    /**
     * 刪除.
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->organization_state = HrOrganization::STATUS_DELETE;
        $result = $model->save();
        if ($result) {
            return $this->success($model['organization_name']);
        }
        return $this->error();
    }

    public function actionDownList()
    {
        $downList['orgAll'] = HrOrganization::getOrgAll();
//        部門級別(1:課級,2:部級,3:製造處級4:產品處級)
        $downList['level'] = [
            1 => '课级',
            2 => '部级',
            3 => '中心/直属部级',
            4 => '制造处级',
            5 => '产品处级'
        ];

        return $downList;
    }

    public function actionModels($id)
    {
        return HrOrganization::find()->where(['organization_id' => $id])->one();
    }

    protected function findModel($id)
    {
        if (($model = HrOrganization::findOne(['organization_id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面不存在');
        }
    }

    //通过部门代码查询部门详细信息
    public function actionGetOrgInfo($organization_code)
    {
        return HrOrganization::byCodeOrg($organization_code);
    }

    //部门权限设置的树状列表
    public function actionGetTree($ass_id, $org_id)
    {
        //判断type_id的值0角色部門，1用戶部門'
//        if ($type_id == 0) {
//            $RPwrDpt=RPwrDpt::find()->andWhere(['ass_id' => $ass_id])->all();
//        }
//        if ($type_id == 1) {
//        }
        $model = new HrOrganization();
        $HrModel = HrOrganization::find()->where(['organization_id' => $org_id])->one();
        $data = "";
        if ($HrModel) {
            $data .= "[{ \"id\" :\"" . $HrModel->organization_level . "\",\"text\" :\"" . $HrModel->organization_name . "<div style='display:none' class='catgid'>" . $HrModel->organization_id . "</div><div style='display:none' class='level'>" . $HrModel->organization_level . "</div>\", \"level\":\"" . $HrModel->organization_level . "\", \"value\" :\"" . $HrModel->organization_id . "\"";
            $chile = $model->getTree($org_id, $ass_id);
            if ($chile != "") {
                $data .= ",\"children\":" . $chile;
            }
            $data .= "}]";
        } else {
            $data = $model->getTree(100, $ass_id);
        }
        return $data;

    }

    //更具部门名称查询部门id
    public function actionGetIdByName($name)
    {
        $model = HrOrganization::find()->where(['organization_name' => $name])->one();
        return $model;
    }

}
