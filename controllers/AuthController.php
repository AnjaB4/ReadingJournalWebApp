<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\RegistrationModel;
use app\models\LoginModel;
use app\models\RoleModel;
use app\models\SessionUserModel;
use app\models\UserRoleModel;

class AuthController extends BaseController
{
    //REGISTRACIJA
    public function registration() //samo vodi ka registration procesu
    {
        $this->view->render('registration', 'auth', new RegistrationModel());
    }

    public function processRegistration()
    {
        $model = new RegistrationModel();

        $model->mapData($_POST);

        $model->validate();

        if ($model->errors) {
            Application::$app->session->set('errorNotification', 'Unsuccessful registration!');
            $this->view->render('registration', 'auth', $model);
            exit;
        }

        $model->password = password_hash($model->password, PASSWORD_DEFAULT);

        $model->insert();

        $model->one("where email = '$model->email'");
        //ovde cemo imati id korisnika koji se registruje

        $roleModel = new RoleModel();

        $roleModel->one("where name = 'User'"); //jer je user strana registracije
        //ovde cemo imati id role

        $userRoleModel = new UserRoleModel();
        $userRoleModel->id_user = $model->id; //dodeli na id_user medjutabele id naseg registrovanog
        $userRoleModel->id_role = $roleModel->id; //dodeli na id_role medjutabele role naseg registrovanog
        $userRoleModel->insert();

        Application::$app->session->set('successNotification', 'Successful registration!');

        header("location:" . "/login");
//        $this->view->render('login', 'auth', $model);
//        echo "<pre>";
//        var_dump($model);exit;
    }

    //LOGIN
    public function login()
    {
        if (Application::$app->session->get('user'))
        {
            header("location:" . "/home");
        }
        $this->view->render('login', 'auth', new LoginModel());
    }

    public function processLogin()
    {
        $model = new LoginModel();

        $model->mapData($_POST);

        $model->validate();

        if ($model->errors) {
            $this->view->render('login', 'auth', $model);
            exit;
        }

        $loginPassword = $model->password; //moramo da sacuvamo pre parsiranja podataka

        $model->one("where email = '$model->email'");
        //jer ovo overrideuje sve sto se trenutno nalazi u modelu pozivom podataka iz baze

        $verifyResult = password_verify($loginPassword, $model->password);
        //prvi arg je korisnicki request, a drugi ono sto imamo u bazi. ugradjena fja poredi ta dva hash-a

        if ($verifyResult) {
            $sessionUserModel = new SessionUserModel();
            $sessionUserModel->email = $model->email;

            Application::$app->session->set('user', $sessionUserModel->getSessionData());
            //sve sto nam treba od usera a da ne idemo opet u bazu ->> email i role
            header("location:" . "/home");
        }

        $model->password = $loginPassword;

        Application::$app->session->set('errorNotification', 'Unsuccessful login attempt!');

        $this->view->render('login', 'auth', $model);

    }

    public function processLogout()
    {
        Application::$app->session->delete('user');
        header("location:" . "/login");
    }

    public function accessDenied()
    {
        $this->view->render('accessDenied', 'auth', null);
    }

    public function accessRole(): array
    {
        return []; // moze da pristupi SVAKO
    }
}