<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/10/29
 * Time: 下午 03:14
 */
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'系统平台设置','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'平台交易相关设置', 'url' => Url::to(['/system/transaction/index'])];
$this->title = '平台交易相关设置';
?>
<div class="content">
    <table class="table">
        <thead>
        <tr>
            <th>序号</th>
            <th>交易相关列表</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>交易方式</td>
                <td><a href="<?=Url::to(['/system/transaction/transaction-index']) ?>">查看</a></td>
            </tr>
            <tr>
                <td>2</td>
                <td>交易条件</td>
                <td><a href="<?=Url::to(['/system/trad-condition/index']) ?>">查看</a></td>
            </tr>
            <tr>
                <td>3</td>
                <td>付款方式</td>
                <td><a href="<?=Url::to(['/system/payment/index']) ?>">查看</a></td>
            </tr>
            <tr>
                <td>4</td>
                <td>付款条件</td>
                <td><a href="<?=Url::to(['/system/pay-condition/index']) ?>">查看</a></td>
            </tr>
            <tr>
                <td>5</td>
                <td>收货方式</td>
                <td><a href="<?=Url::to(['/system/receipt/index']) ?>">查看</a></td>
            </tr>
            <tr>
                <td>6</td>
                <td>交货方式</td>
                <td><a href="<?=Url::to(['/system/devcon/index']) ?>">查看</a></td>
            </tr>
            <tr>
                <td>7</td>
                <td>結算方式</td>
                <td><a href="<?=Url::to(['/system/settlement/index']) ?>">查看</a></td>
            </tr>
            <tr>
                <td>8</td>
                <td>货币类别</td>
                <td><a href="<?=Url::to(['/system/currency/index']) ?>">查看</a></td>
            </tr>

            <tr>
                <td>9</td>
                <td>税率/税别</td>
                <td><a href="#">查看</a></td>
            </tr>

        </tbody>
    </table>
</div>
