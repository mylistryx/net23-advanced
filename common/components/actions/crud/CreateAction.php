<?php

namespace common\components\actions\crud;

use Yii;
use yii\web\Response;

class CreateAction extends AbstractAction
{
    public ?string $successMessage = 'Created successfully';

    public function run(): Response|string
    {
        $model = new $this->modelClass();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->controller->addFlash($this->successMessageCategory, $this->successMessage, $this->messageSourceCategory);
            return $this->controller->redirect(['view', 'id' => $model->id]);
        }

        return $this->controller->render($this->view ?? 'create', [
            'model' => $model,
        ]);
    }
}