<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $results array */
/* @var $year int|null */

$this->title = 'ТОП-10 Авторов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="<?= \yii\helpers\Url::to(['index']) ?>" class="row g-3 align-items-center">
                <input type="hidden" name="r" value="report/index">

                <div class="col-auto">
                    <label for="inputYear" class="col-form-label">Выберите год:</label>
                </div>
                <div class="col-auto">
                    <input type="number" id="inputYear" name="year" class="form-control" value="<?= Html::encode($year) ?>" placeholder="Например, 2024" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Сформировать отчет</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($year): ?>
        <h3>Результаты за <?= Html::encode($year) ?> год:</h3>

        <?php if (!empty($results)): ?>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Автор</th>
                    <th style="width: 150px">Количество книг</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= Html::encode($row['full_name']) ?></td>
                        <td>
                                <span class="badge bg-success" style="font-size: 1em;">
                                    <?= $row['book_count'] ?>
                                </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                В указанном году книги не найдены.
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>