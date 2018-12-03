<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Pertanyaan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pertanyaan-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'tanya_id')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($model, 'pertanyaan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tanya_ket_a')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tanya_ket_b')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tanya_ket_c')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tanya_bobot')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanya_bagian')->dropDownList(
        ArrayHelper::map($data,'bg_id','bg_nama')
    ) ?>

    <!-- <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
