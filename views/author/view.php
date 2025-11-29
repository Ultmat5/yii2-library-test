<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php else: ?>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#subscribeModal">
                Подписаться на новинки
            </button>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
        ],
    ]) ?>

</div>

<div class="modal fade" id="subscribeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подписка на автора: <?= \yii\helpers\Html::encode($model->full_name) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $subModel = new \app\models\Subscription(); ?>
                <?php $subModel->author_id = $model->id; ?>

                <?php $form = ActiveForm::begin([
                    'id' => 'subscription-form',
                    'action' => Url::to(['subscription/create']),
                    'enableAjaxValidation' => false,
                ]); ?>

                <?= $form->field($subModel, 'author_id')->hiddenInput()->label(false) ?>

                <?= $form->field($subModel, 'phone')->widget(MaskedInput::class, [
                    'mask' => '79999999999',
                    'options' => [
                        'placeholder' => '79001234567',
                        'class' => 'form-control',
                        'id' => 'phone-input'
                    ],
                    'clientOptions' => [
                        'clearIncomplete' => true, // Очищать, если ввел не до конца
                    ]
                ])->label('Введите ваш номер телефона') ?>

                <div class="alert alert-success d-none" id="sub-success-msg"></div>
                <div class="alert alert-danger d-none" id="sub-error-msg"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Подписаться</button>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$('#subscription-form').on('beforeSubmit', function(e) {
    var \$form = $(this);
    $.post(
        \$form.attr("action"),
        \$form.serialize()
    )
    .done(function(result) {
        if(result.success) {
            $('#sub-success-msg').text(result.message).removeClass('d-none');
            $('#sub-error-msg').addClass('d-none');
            \$form.trigger("reset");
            setTimeout(function() {
                var modal = bootstrap.Modal.getInstance(document.getElementById('subscribeModal'));
                modal.hide();
                $('#sub-success-msg').addClass('d-none');
            }, 2000);
        } else {
            var errorMsg = '';
            if (result.errors.phone) errorMsg += result.errors.phone[0] + ' ';
            if (result.errors.author_id) errorMsg += result.errors.author_id[0]; 
            
            $('#sub-error-msg').text(errorMsg).removeClass('d-none');
            $('#sub-success-msg').addClass('d-none');
        }
    })
    .fail(function() {
        $('#sub-error-msg').text('Ошибка сервера').removeClass('d-none');
    });
    return false;
});
JS;
$this->registerJs($js);
?>
