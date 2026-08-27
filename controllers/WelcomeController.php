<?php

namespace app\controllers;

use app\core\BaseController;

class WelcomeController extends BaseController
{
    public function welcome()
    {
        $this->view->render('welcome', 'auth', null);
    }

    public function accessRole(): array
    {
        return [];
    }
}