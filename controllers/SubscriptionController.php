<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Subscription;

class SubscriptionController extends Controller
{
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Subscription();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['success' => true, 'message' => 'Вы успешно подписались на автора!'];
        }

        return ['success' => false, 'errors' => $model->getErrors()];
    }
}