<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Kelas */

$this->title = 'Update Kelas: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kelas_id, 'url' => ['view', 'id' => $model->kelas_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kelas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
