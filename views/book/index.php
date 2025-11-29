<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Каталог книг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->image
                        ? Html::img('@web/uploads/' . $model->image, ['width' => '50'])
                        : 'Нет фото';
                },
            ],
            'title',
            'year',
            [
                'label' => 'Авторы',
                'value' => function ($model) {
                    $authors = \yii\helpers\ArrayHelper::getColumn($model->authors, 'full_name');
                    return implode(', ', $authors);
                }
            ],
            'isbn',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => Yii::$app->user->isGuest ? '{view}' : '{view} {update} {delete}',
                'visible' => true,
            ],
        ],
    ]); ?>
</div>