<?php

namespace common\components\actions\crud;

use yii\web\NotFoundHttpException;
use yii\web\Response;

class ViewAction extends AbstractAction
{
    /**
     * @param int|string $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function run(int|string $id): Response
    {
        $model = $this->findModel($id);

        return $this->controller->render($this->view ?? 'view', [
            'model' => $model,
        ]);
    }
}