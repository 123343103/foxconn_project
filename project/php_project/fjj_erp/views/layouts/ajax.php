<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
use yii\widgets\Breadcrumbs;
JqueryUIAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge;10;9">
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= Html::encode($this->title) ?></title>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= Breadcrumbs::widget([
    'homeLink' =>isset($this->params['homeLike']) ? $this->params['homeLike'] : [],
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
    $(function () {

    })
</script>

