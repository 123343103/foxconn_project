<?php
/**
 * User: F1677929
 * Date: 2017/9/6
 */
namespace app\modules\ptdt\controllers;

use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\ptdt\models\BsCategory;
use app\modules\ptdt\models\BsCatgAttr;
use app\modules\ptdt\models\RAttrValue;
use app\modules\ptdt\models\RCatg;
use app\modules\ptdt\models\search\BsCategorySearch;
use Yii;
use yii\data\SqlDataProvider;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * 商品分类管理控制器
 */
class CategoryManageController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\BsCategory';

    public function actionIndex()
    {
        $searchModel = new BsCategorySearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
//        $model = $dataProvider->getModels();
        //$list['rows'] = $model;
        return $dataProvider;
    }

    //新增類別信息
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $transaction = BsCatgAttr::getDb()->beginTransaction();
            try {
                $BsCategoryInfo = new BsCategory();
                //$pcatgidinfo=$this->getpcatgid($data["p_catg_no"]);
                // $BsCategoryInfo->p_catg_id=$pcatgidinfo;
                $BsCategoryInfo->crt_date = $BsCategoryInfo["crt_date"];
                $BsCategoryInfo->crter = $BsCategoryInfo["crter"];
                $BsCategoryInfo->crt_ip = $BsCategoryInfo["crt_ip"];
                $count = BsCategory::find()->where(['catg_no' => $data['BsCategory']['catg_no']])->count();
//                return $count;
                if ($count == 0) {
                    if (!($BsCategoryInfo->load($data) && $BsCategoryInfo->save())) {
                        $errors = $BsCategoryInfo->getErrors();
                        if (empty($errors['catg_name'])) {
                            throw new \Exception(json_encode($BsCategoryInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                        } else {
                            throw new Exception('名称已存在！');
                        }
                    }
                } else {
                    throw new Exception('类别编码已存在！');
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->success();
    }

    //修改
    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $transaction = BsCatgAttr::getDb()->beginTransaction();
            try {
                //$BsCategoryInfo=new BsCategory();
                $BsCategoryInfo = BsCategory::findOne($id);
                $BsCategoryInfo->opp_date = $BsCategoryInfo["opp_date"];
                $BsCategoryInfo->opper = $BsCategoryInfo["opper"];
                $BsCategoryInfo->opp_ip = $BsCategoryInfo["opp_ip"];
                if (!($BsCategoryInfo->load($data) && $BsCategoryInfo->save())) {
                    $errors = $BsCategoryInfo->getErrors();
                    if (empty($errors['catg_name'])) {
                        throw new \Exception(json_encode($BsCategoryInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                    } else {
                        throw new Exception('名称已存在！');
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->success();
    }
    //根据catg_no获取p_catg_id
//    public function getpcatgid($catg_no){
//        $sql="select p_catg_id from pdt.bs_category where catg_no=:catg_no";
//        $pcatgidinfo=Yii::$app->db->createCommand($sql)->bindValue(":catg_no",$catg_no)->queryOne();
//        return  (int)$pcatgidinfo["p_catg_id"];
//    }
    /*
   * 修改页面调用
   */
    public function actionModels($id)
    {
        return $this->findModel($id);
    }

    protected function findModel($id)
    {
        $sql = "select m.catg_name as p_catg_name,s.catg_name ,s.catg_id,s.catg_level,s.orderby,s.isvalid,s.yn_machine,s.catg_no,s.p_catg_id from 
(select * from pdt.bs_category t where t.catg_id=:catg_id) s LEFT JOIN pdt.bs_category m ON s.p_catg_id=m.catg_id";
        $bscategoryInfo = Yii::$app->db->createCommand($sql)->bindValue(':catg_id', $id)->queryAll();
        if ($bscategoryInfo !== null) {
            return $bscategoryInfo;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //获取上级类别
    public function actionGetPcatgname($catg_level)
    {
        return Json::encode(BsCategory::getPcatgname($catg_level));
    }

    //获取排序编号
    public function actionGetOrderbyno($p_catg_id)
    {
        return Json::encode(BsCategory::getOrderbyno($p_catg_id));
    }
//    //根据用户输入的类别编码判断是否存在相同的类别
//    public function actionGetCatgnoInfo($no){
//        return BsCategory::getCheckCatgNoOne($no);
//    }
    //判断是否已经关联类别属性
    public function actionGetCheckedattr($id)
    {
        $sql = "SELECT * FROM pdt.bs_catg_attr WHERE  catg_id=:catg_id";
        $catgnoInfo = Yii::$app->db->createCommand($sql)->bindValue(':catg_id', $id)->queryAll();
        return $catgnoInfo;
    }

    //商品分类属性列表
    public function actionAttrList()
    {
        $params = Yii::$app->request->queryParams;
        $queryParams = [':id' => $params['id']];
        if (empty($params['rows'])) {
            return Yii::$app->pdt->createCommand("select catg_id,catg_name from pdt.bs_category where catg_id = :id", $queryParams)->queryOne();
        }
        $countSql = "select count(*) from pdt.bs_catg_attr where catg_id = :id";
        $querySql = "select catg_attr_id,
                          attr_name,
                          case attr_type when 0 then '多项选择' when 1 then '平铺选择' when 2 then '下拉选择' when 3 then '文字录入' else '未知' end attrType,
                          case isrequired when 0 then '否' when 1 then '是' else '未知' end isRequired,
                          status
                   from pdt.bs_catg_attr 
                   where catg_id = :id";
        if ((!isset($params['status'])) || $params['status'] === '1') {
            $countSql .= " and status = 1";
            $querySql .= " and status = 1";
        }
        if (isset($params['status']) && $params['status'] === '0') {
            $countSql .= " and status = 0";
            $querySql .= " and status = 0";
        }
        if (!empty($params['attr_name'])) {
            $trans = new Trans();
            $queryParams[':attr_name1'] = '%' . $params['attr_name'] . '%';
            $queryParams[':attr_name2'] = '%' . $trans->c2t($params['attr_name']) . '%';
            $queryParams[':attr_name3'] = '%' . $trans->t2c($params['attr_name']) . '%';
            $countSql .= " and (attr_name like :attr_name1 or attr_name like :attr_name2 or attr_name like :attr_name3)";
            $querySql .= " and (attr_name like :attr_name1 or attr_name like :attr_name2 or attr_name like :attr_name3)";
        }
        $totalCount = Yii::$app->pdt->createCommand($countSql, $queryParams)->queryScalar();
        $querySql .= " order by status desc, catg_attr_id asc";
        $provider = new SqlDataProvider([
            'db' => 'pdt',
            'sql' => $querySql,
            'params' => $queryParams,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $params['rows']
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount
        ];
    }

    //新增商品分类属性
    public function actionAddAttr($id)
    {
        $data = Yii::$app->request->post();
        $transaction = Yii::$app->pdt->beginTransaction();
        try {
            //属性表
            $attrModel = new BsCatgAttr();
            $attrModel->catg_id = $id;
            if ((!$attrModel->load($data)) || (!$attrModel->save())) {
                $errors = $attrModel->getErrors();
                if (empty($errors['attr_name'])) {
                    throw new Exception(json_encode($errors, JSON_UNESCAPED_UNICODE));
                } else {
                    throw new Exception('属性已存在！');
                }
            }
            //属性值表
            if (!empty($data['values'])) {
                foreach ($data['values'] as $val) {
                    $valueModel = new RAttrValue();
                    $valueModel->catg_attr_id = $attrModel->catg_attr_id;
                    $valueModel->opp_date = $data['BsCatgAttr']['opp_date'];
                    $valueModel->opper = $data['BsCatgAttr']['opper'];
                    $valueModel->opp_ip = $data['BsCatgAttr']['opp_ip'];
                    if ((!$valueModel->load($val)) || (!$valueModel->save())) {
                        throw new Exception(json_encode($valueModel->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success('新增成功！');
        } catch (Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //修改商品分类属性
    public function actionEditAttr($id)
    {
        if ($data = Yii::$app->request->post()) {
            $transaction = Yii::$app->pdt->beginTransaction();
            try {
                //属性表
                $attrModel = BsCatgAttr::findOne($id);
                if ((!$attrModel->load($data)) || (!$attrModel->save())) {
                    $errors = $attrModel->getErrors();
                    if (empty($errors['attr_name'])) {
                        throw new Exception(json_encode($errors, JSON_UNESCAPED_UNICODE));
                    } else {
                        throw new Exception('属性已存在！');
                    }
                }
                //属性值表
                if (!empty($data['values'])) {
                    $arrId = [];
                    foreach ($data['values'] as $val) {
                        if (empty($val['RAttrValue']['attr_val_id'])) {
                            $valueModel = new RAttrValue();
                        } else {
                            $valueModel = RAttrValue::findOne($val['RAttrValue']['attr_val_id']);
                        }
                        $valueModel->catg_attr_id = $attrModel->catg_attr_id;
                        $valueModel->opp_date = $data['BsCatgAttr']['opp_date'];
                        $valueModel->opper = $data['BsCatgAttr']['opper'];
                        $valueModel->opp_ip = $data['BsCatgAttr']['opp_ip'];
                        if ((!$valueModel->load($val)) || (!$valueModel->save())) {
                            throw new Exception(json_encode($valueModel->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        $arrId[] = $valueModel->attr_val_id;
                    }
                    //删除表单里删除的属性值
                    RAttrValue::deleteAll(['and', ['catg_attr_id' => $attrModel->catg_attr_id], ['not in', 'attr_val_id', $arrId]]);
                } else {
                    RAttrValue::deleteAll(['catg_attr_id' => $attrModel->catg_attr_id]);
                }
                $transaction->commit();
                return $this->success('修改成功！');
            } catch (Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        //获取修改的数据
        $data = Yii::$app->pdt->createCommand("select catg_attr_id,attr_name,attr_type,isrequired,attr_remark from pdt.bs_catg_attr where catg_attr_id = :id", ['id' => $id])->queryOne();
        $data['values'] = Yii::$app->pdt->createCommand("select attr_val_id,attr_value,yn from pdt.r_attr_value where catg_attr_id = {$data['catg_attr_id']}")->queryAll();
        //若属性被引用，则资料格式不可以修改，1表示可以修改，0表示不可以修改
        $result1 = Yii::$app->pdt->createCommand("select * from pdt.r_prt_attr where catg_attr_id = {$data['catg_attr_id']}")->queryOne();
        if (empty($result1)) {
            $data['material_format_edit_flag'] = 1;
        } else {
            $data['material_format_edit_flag'] = 0;
        }
        //若属性值被引用，则不可删除，1表示可以修改，0表示不可以修改
        foreach ($data['values'] as $key => &$val) {
            $result2 = Yii::$app->pdt->createCommand("select * from pdt.r_prt_attr where catg_attr_id = {$data['catg_attr_id']} and attr_val_id = {$val['attr_val_id']}")->queryOne();
            if (empty($result2)) {
                $val['attr_value_edit_flag'] = 1;
            } else {
                $val['attr_value_edit_flag'] = 0;
            }
        }
        return $data;
    }

    //查看商品分类属性
    public function actionViewAttr($id)
    {
        $data = Yii::$app->pdt->createCommand("select catg_attr_id,attr_name,attr_type,isrequired,attr_remark from pdt.bs_catg_attr where catg_attr_id = :id", ['id' => $id])->queryOne();
        $data['values'] = Yii::$app->pdt->createCommand("select attr_val_id,attr_value,yn from pdt.r_attr_value where catg_attr_id = {$data['catg_attr_id']}")->queryAll();
        return $data;
    }

    //启用商品分类属性
    public function actionEnableAttr($id)
    {
        $attrModel = BsCatgAttr::findOne($id);
        $attrModel->status = 1;
        if ($attrModel->save()) {
            return $this->success('已启用！', $attrModel->attr_name);
        }
        return $this->error('未知错误！');
    }

    //禁用商品分类属性
    public function actionDisableAttr($id)
    {
        $attrModel = BsCatgAttr::findOne($id);
        $attrModel->status = 0;
        if ($attrModel->save()) {
            return $this->success('已禁用！', $attrModel->attr_name);
        }
        return $this->error('未知错误！');
    }

    /**
     * 下拉菜单数据
     */
    public function actionDownList()
    {
        $Bscategory['catgname'] = BsCategory::getCatenameAll();
        return $Bscategory;
    }


    //查询已经关联的三阶
    public function actionCategoryRelating($id)
    {
        $queryParams = [':id' => $id];
        $sql = "SELECT c.catg_id, a.catg_no no1, a.catg_name name1,b.catg_no no2,b.catg_name name2,c.catg_no no3,c.catg_name name3 FROM pdt.bs_category a JOIN pdt.bs_category b ON a.catg_id = b.p_catg_id JOIN  pdt.bs_category c ON b.catg_id = c.p_catg_id WHERE c.catg_level = 3 and c.catg_id in ( SELECT catg_r_id FROM pdt.r_catg WHERE catg_id = :id)";
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $queryParams,
//            'totalCount'=>$totalCount,
            'pagination' => [
//                'pageSize'=>$params['rows']
                'pageSize' => ''
            ]
        ]);
        return $provider;
    }

    //通过id查询三阶类别名称
    public function actionCateName($id)
    {
        $model = BsCategory::find()->where(['catg_id' => $id])->one();
        return $model;
    }


    public function actionGetCate($level, $pid)
    {
        $provider = BsCategory::find()->where(['catg_level' => $level])->andWhere(['p_catg_id' => $pid])->all();
        return $provider;
    }

    //保存类别关联数据
    public function actionSave()
    {

        $db = Yii::$app->get('pdt');
        $param = Yii::$app->request->post();
        //判断删除的关联料号有没有被删除
        $transaction = $db->beginTransaction();
//        $param['catg_id']='43418765';
        try {
            //先delete
            $Rcatg = RCatg::find()->where(["catg_id" => $param['catg_id']])->all();
            if (!empty($Rcatg)) {
                if (RCatg::deleteAll(["catg_id" => $param['catg_id']])) {

                } else {
                    $transaction->rollBack();
                    return $this->error();
                }
            }
            if(!empty($param['catg_r_id'])){
            //再insert
            $ridArr = explode(",", $param['catg_r_id']);
            foreach ($ridArr as $key => $val) {
                $model = new RCatg();
                $model->catg_id = $param['catg_id'];
                $model->catg_r_id = $val;
                $model->opper = $param['opper'];
                $model->op_date = $param['op_date'];
                $model->opp_ip = $param['opp_ip'];
                if (!$model->save()) {
                    throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }}
            $transaction->commit();
            return $this->success();

        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }


    }

    //查询树
    public function actionGetTree($catgid)
    {
        return BsCategory::getTree(0, $catgid);
    }


    //查询该类别关联的类别有没有帮商品
    public function actionCheck($id)
    {
        $queryParams = [':id' => $id];
        $sql = "SELECT DISTINCT
   (catg_id)
FROM
   pdt.bs_product
WHERE
   pdt_no IN (
      SELECT
         r_pdt_PKID
      FROM
         pdt.r_pdt_pdt
      WHERE
         pdt_PKID IN (
            SELECT
               pdt_no
            FROM
               pdt.bs_product
            WHERE
               catg_id = :id
         )
   )";
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $queryParams,
//            'totalCount'=>$totalCount,
            'pagination' => [
//                'pageSize'=>$params['rows']
                'pageSize' => ''
            ]
        ]);
        return $provider;
    }


}