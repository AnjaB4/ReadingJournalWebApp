<?php
namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\ReadingLogModel;
use app\models\RoleModel;
use app\models\UserModel;
use app\models\UserRoleModel;

class UserController extends BaseController
{

    public function readUser()
    {
        $model = new UserModel();

        $user = Application::$app->session->get('user');
        $userId = $user[0]['id_user'];

        $model->one("where id = $userId");
                        // NE VRACA NOVI objekat, samo POPUNJAVA

        $this->view->render('getUser', 'main', $model);

        /*
        $model = new UserModel(); $model->one();
         — napravi objekat, one() povuče red iz baze,
         mapData() dodeli vrednosti poljima objekta,
         pa na kraju $model sadrži ta 3 polja
         i spreman je za view.
        */
    }
    public function readAll()
    {
        $model = new UserModel();

        $result = $model->all("");

        $userRoleModel = new UserRoleModel();
        $roleModel = new RoleModel();
        foreach ($result as &$user) {
            // izvuci sve role za datog usera
            $userRoles = $userRoleModel->all("where id_user = " . $user['id']);

            $roles = [];
            foreach ($userRoles as $ur) {
                $roleModel->one("where id = " . $ur['id_role']);
                if ($roleModel->id ?? false) {              // ako postoji id, znači da je role učitana
                    $roles[] = $roleModel->name;
                }
            }
            $user['roles'] = $roles;
        }
        $this->view->render('users', 'main', $result);
    }

    public function updateUser() //renderuje samo FORMU na osnovu podataka, nas HTML ZA Updatevanje
    {
        $model = new UserModel();
//        echo "<pre>"; var_dump($model);exit;

        $model->mapData($_GET); //saljemo podatke kroz URL

        $model->one("where id = $model->id");

        $this->view->render('updateUser', 'main', $model);
    }

    public function processUpdateUser() //ZAPRAVO VRSI Updateovanje
    {
        $model = new UserModel();

        $model->mapData($_POST); //saljemo podatke kroz FORMU

        $model->validate();

        if ($model->errors) {
            Application::$app->session->set('errorNotification', 'Update unsuccessful!');
            $this->view->render('updateUser', 'main', $model);
            exit;
        }

        $model->update("where id = $model->id");

        Application::$app->session->set('successNotification', 'Successfully updated user!');

        header("location:" . "/users");
    }

    public function createUser()   //samo renderuje FORMU
    {
        //nemamo komunikaciju s bazom, model nam ne treba
        //TO DO verifikacija/autentifikacija
        $model = new UserModel();
        $this->view->render('createUser', 'main', $model);
    }
    public function processCreate() //ZAPRAVO VRSI Create
    {
        $model = new UserModel();

        $model->mapData($_POST); //saljemo podatke kroz FORMU

        $model->validate();

        if ($model->errors) {
            Application::$app->session->set('errorNotification', 'User not created!');
            $this->view->render('createUser', 'main', $model);
            exit;
        }

        $model->insert();

        Application::$app->session->set('successNotification', 'Successfully created user!');

        header("location:" . "/users"); //redirectovanje
    }

    public function delete()
    {
        $model = new UserModel();

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $success = $model->delete("where id = $id");

            if ($success) {
                Application::$app->session->set('successNotification', 'User deleted successfully!');
            } else {
                Application::$app->session->set('errorNotification', 'Delete failed!');
            }
        }

        header("Location:" . "/users");
        exit;
    }

    public function accessRole(): array
    {
        return ['Librarian']; // moze da pristupi Librarian
    }
}