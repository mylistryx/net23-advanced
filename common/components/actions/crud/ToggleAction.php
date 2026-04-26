<?php

namespace common\components\actions\crud;

use Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ToggleAction extends AbstractAction
{
    public ?string $isActiveAttribute = null;
    public string $errorMessage = 'Status toggle failed';
    public string $successMessage = 'Status toggle successfully';

    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function run(int|string $id): Response
    {
        $model = $this->findModel($id);

        if ($model->toggle($this->isActiveAttribute)) {
            $this->controller->addFlash($this->successMessageCategory, $this->successMessage, $this->messageSourceCategory);
        } else {
            $this->controller->addFlash($this->errorMessageCategory, $this->errorMessage, $this->messageSourceCategory);
        }

        return $this->controller->redirect(['index']);
    }
}