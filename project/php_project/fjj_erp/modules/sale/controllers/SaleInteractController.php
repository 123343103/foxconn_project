<?php

namespace app\modules\sale\controllers;

class SaleInteractController extends \app\controllers\BaseController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'indexdata' => 'this is saleInteractcontroller data',
        ]);
    }

}
