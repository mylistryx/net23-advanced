<?php

namespace common\components\actions\crud;

use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UpdateAction extends AbstractAction
{
    public ?string $successMessage = 'Updated successfully';

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function run(int|string $id): Response
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->controller->addFlash($this->successMessageCategory, $this->successMessage, $this->messageSourceCategory);
            return $this->controller->redirect(['view', 'id' => $model->id]);
        }

        return $this->controller->render($this->view ?? 'update', [
            'model' => $model,
        ]);
    }
}