<?php

namespace common\components\actions;

use yii\web\Response;

class RenderAction extends BaseAction
{
    public string $layout = 'main';
    public ?string $view = null;
    public array $params = [];

    public function run(): Response
    {
        return $this->controller->render($this->view ?? $this->controller->action->id, $this->params);
    }
}