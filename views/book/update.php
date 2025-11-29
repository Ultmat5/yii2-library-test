<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $authors array */

$this->title = 'Редактировать книгу: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="book-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'authors' => $authors,
    ]) ?>
</div>