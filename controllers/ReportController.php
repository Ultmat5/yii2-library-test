<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\services\ReportService;

class ReportController extends Controller
{
    private $reportService;

    public function __construct($id, $module, ReportService $reportService, $config = [])
    {
        $this->reportService = $reportService;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $year = Yii::$app->request->get('year');
        $results = [];

        if ($year && is_numeric($year)) {
            $results = $this->reportService->getTopAuthorsByYear((int)$year);
        }

        return $this->render('index', [
            'results' => $results,
            'year' => $year,
        ]);
    }
}