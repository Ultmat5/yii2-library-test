<?php

namespace app\controllers;

use Yii;
use app\models\Book;
use app\models\Author;
use app\services\BookService;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class BookController extends Controller
{
    private $bookService;

    public function __construct($id, $module, BookService $bookService, $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Book::find()->with('authors');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Book();

        if ($model->load(Yii::$app->request->post())) {

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {

                if ($this->bookService->create($model)) {
                    Yii::$app->session->setFlash('success', 'Книга успешно добавлена!');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка при сохранении.');
                }
            }
        }

        $authors = Author::find()->select(['full_name', 'id'])->indexBy('id')->column();

        return $this->render('create', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->authorIds = \yii\helpers\ArrayHelper::getColumn($model->authors, 'id');

        if ($model->load(Yii::$app->request->post())) {

            $model->imageFile = \yii\web\UploadedFile::getInstance($model, 'imageFile');

            if ($model->validate()) {
                if ($this->bookService->update($model)) {
                    Yii::$app->session->setFlash('success', 'Книга обновлена!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        $authors = Author::find()->select(['full_name', 'id'])->indexBy('id')->column();

        return $this->render('update', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}