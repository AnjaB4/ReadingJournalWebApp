<?php

namespace app\controllers;

use app\core\Application;
use app\core\BaseController;
use app\models\LibrarianRequestModel;
use app\models\UserRoleModel;
use app\models\RoleModel;

class LibrarianRequestController extends BaseController
{

    public function allRequests()
    {
        $model = new LibrarianRequestModel();

        $query = "select lr.id, lr.id_user, lr.status, u.first_name, u.last_name, u.email
          from librarian_requests lr
          join users u on lr.id_user = u.id
          where lr.status = 'pending'";

        $requests = $model->con->query($query)->fetch_all(MYSQLI_ASSOC);
//        var_dump($requests); exit;

        $this->view->render('librarianRequests', 'main', ['requests' => $requests]);
    }

    public function approve()
    {
        list($id, $req) = $this->errorMessage();

        $req->status = 'approved';
        $req->update("where id = $id");

        $roleModel = new RoleModel();
        $roleModel->one("where name = 'Librarian'");
        if (!($roleModel->id ?? false)) {
            Application::$app->session->set('errorNotification', 'Role "Librarian" not found in roles table');
            header("location: " . "/librarianRequests");
            exit;
        }

        $userRole = new UserRoleModel();
        $userRole->id_user = $req->id_user;
        $userRole->id_role = $roleModel->id;
        $userRole->insert();

        Application::$app->session->set('successNotification', 'Request approved and role assigned.');
        header("location: " . "/librarianRequests");
    }

    // Reject (POST)
    public function reject()
    {
        list($id, $req) = $this->errorMessage();

        $req->status = 'rejected';
        $req->update("where id = $id");

        Application::$app->session->set('errorNotification', 'Request rejected.');
        header("location: " . "/librarianRequests");
    }

    /**
     * @return array|void
     */
    public function errorMessage()
    {
        $id = (int)$_POST['id'] ?? 0;
        if (!$id) {
            Application::$app->session->set('errorNotification', 'Invalid request id');
            header("location: " . "/librarianRequests");
            exit;
        }

        $req = new LibrarianRequestModel();
        $req->one("where id = $id");
        if (!($req->id ?? false)) {
            Application::$app->session->set('errorNotification', 'Request not found');
            header("location: " . "/librarianRequests");
            exit;
        }
        return array($id, $req);
    }

    public function accessRole(): array
    {
        return ['Librarian'];
    }
}
