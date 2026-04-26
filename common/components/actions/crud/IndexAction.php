<?php

namespace common\components\actions\crud;

use Yii;
use yii\web\Response;

class IndexAction extends AbstractAction
{
    public ?string $searchModelClass = null;

    public function run(): Response
    {
        $searchModelClass = $this->searchModelClass ?? $this->modelClass . 'Search';

        $searchModel = new $searchModelClass();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render($this->view ?? 'index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}