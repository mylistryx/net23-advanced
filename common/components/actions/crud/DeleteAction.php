<?php

namespace common\components\actions\crud;

use Throwable;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DeleteAction extends AbstractAction
{
    public string $successMessage = 'Record deleted successfully';
    public string $errorMessage = 'Record was not deleted';

    /**
     * @throws NotFoundHttpException
     */
    public function run(int|string $id): Response
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {
                $this->controller->addFlash($this->successMessageCategory, $this->successMessage, $this->messageSourceCategory);
            } else {
                $this->controller->addFlash($this->errorMessageCategory, $this->errorMessage, $this->messageSourceCategory);
            }
        } catch (Throwable $e) {
            $this->controller->addFlash($this->errorMessageCategory, $e->getMessage(), $this->messageSourceCategory);
        }

        return $this->controller->redirect(['index']);
    }
}