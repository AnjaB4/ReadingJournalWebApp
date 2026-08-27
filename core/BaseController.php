<?php
//AKO VIDIS OVO
namespace app\core;

abstract class BaseController
{
    public View $view;

    abstract public function accessRole();

    public function __construct()
        //posto svaki kontroler nasledjuje Base, ova metoda ce se izvrsiti
        // koji god kontroler da se pokrene, i onda znamo da uvek ispitujemo ko ima access cemu
    {
        $this->view = new View();

        $controllerRoles = $this->accessRole();

        $sessionUserData = Application::$app->session->get('user');

        if ($controllerRoles == []) {
            return; //ako je prazno, dozvoli (svakome)
        }

        $hasAccess = false;

        foreach ($sessionUserData as $userData) {
            $userRole = $userData['role'];

            foreach ($controllerRoles as $controllerRole) {
                if ($userRole == $controllerRole) {
                    $hasAccess = true;
                }
            }
        }

        if ($hasAccess) {
            return;
        }else {
            header("location:" . "/accessDenied");
        }

    }
}