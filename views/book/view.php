<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Точно удалить?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <div class="row">
        <div class="col-md-4">
            <?php if ($model->image): ?>
                <?= Html::img('@web/uploads/' . $model->image, ['class' => 'img-thumbnail', 'style' => 'width:100%']) ?>
            <?php else: ?>
                <div class="alert alert-warning">Нет обложки</div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'title',
                    'year',
                    'description:ntext',
                    'isbn',
                    [
                        'label' => 'Авторы',
                        'value' => implode(', ', \yii\helpers\ArrayHelper::getColumn($model->authors, 'full_name')),
                    ],
                    'created_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

</div>