<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Pertanyaan */

$this->title = 'Tambah Pertanyaan';
$this->params['breadcrumbs'][] = ['label' => 'Pertanyaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pertanyaan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ]) ?>

</div>