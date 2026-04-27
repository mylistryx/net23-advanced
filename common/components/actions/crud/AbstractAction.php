<?php

namespace common\components\actions\crud;

use common\components\actions\BaseAction;
use common\components\controllers\CrudController;
use common\components\db\ActiveRecord;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @property CrudController $controller
 */
abstract class AbstractAction extends BaseAction
{
    public ?string $modelClass = null;
    public string $successMessageCategory = 'success';
    public string $errorMessageCategory = 'error';
    public string $infoMessageCategory = 'info';
    public string $warningMessageCategory = 'warning';
    public string $messageSourceCategory = 'app';
    public ?string $notFoundMessage = 'Record with id = :id not found';

    public ?string $view = null;

    protected function success(string $message, array $params = []): CrudController
    {
        return $this->controller->success($message, $this->successMessageCategory, $params);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel(int|string $id): ActiveRecord
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        if (!$model = $modelClass::findOne($id)) {
            throw new NotFoundHttpException(Yii::t($this->messageSourceCategory, $this->notFoundMessage, ['id' => $id]));
        }

        return $model;
    }
}