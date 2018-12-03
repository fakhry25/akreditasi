<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PertanyaanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pertanyaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tanya_id') ?>

    <?= $form->field($model, 'pertanyaan') ?>

    <?= $form->field($model, 'tanya_ket_a') ?>

    <?= $form->field($model, 'tanya_ket_b') ?>

    <?= $form->field($model, 'tanya_ket_c') ?>

    <?php // echo $form->field($model, 'tanya_bobot') ?>

    <?php // echo $form->field($model, 'tanya_bagian') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
