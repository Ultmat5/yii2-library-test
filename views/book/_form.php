<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $authors array */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'year')->textInput(['type' => 'number']) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->isNewRecord && $model->image): ?>
        <div class="mb-3">
            <label>Текущая обложка:</label><br>
            <?= Html::img('@web/uploads/' . $model->image, ['style' => 'width:100px']) ?>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'authorIds')->checkboxList($authors) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>