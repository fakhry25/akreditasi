<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Kaling */

$this->title = 'Create Kep. Instansi';
$this->params['breadcrumbs'][] = ['label' => 'Kep. Instansi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kaling-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
        'status' => 'create',
    ]) ?>

</div>