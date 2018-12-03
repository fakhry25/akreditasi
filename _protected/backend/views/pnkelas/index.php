<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PnkelasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kelas/Type Pengadilan Negeri';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pn-kelas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'kelas_id',
            'kelas_nama',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
