<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PnKelas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pn-kelas-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'kelas_id')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($model, 'kelas_nama')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
