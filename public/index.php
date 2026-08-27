<?php

require_once __DIR__ . "/../vendor/autoload.php";

use app\controllers\AuthController;
use app\controllers\BookController;
use app\controllers\BookshelfController;
use app\controllers\HomeController;
use app\controllers\LibrarianReportController;
use app\controllers\LibrarianRequestController;
use app\controllers\ReadingLogController;
use app\controllers\StatisticsController;
use app\controllers\AchievementController;
use app\controllers\UserController;
use app\controllers\UserReportController;
use app\controllers\WelcomeController;
use app\core\Application;


$app = new Application();

// WELCOME
$app->router->get("/", [WelcomeController::class, 'welcome']);

// HOME
$app->router->get("/home", [HomeController::class, 'home']);

// REGISTRATION
$app->router->get("/registration", [AuthController::class, 'registration']);
$app->router->post("/processRegistration", [AuthController::class, 'processRegistration']);

// LOGIN
$app->router->get("/login", [AuthController::class, 'login']);
$app->router->post("/processLogin", [AuthController::class, 'processLogin']);
$app->router->get("/processLogout", [AuthController::class, 'processLogout']);

// ACCESS DENIED
$app->router->get("/accessDenied", [AuthController::class, 'accessDenied']);

// USERS
$app->router->get("/getUser", [UserController::class, 'readUser']);
$app->router->get("/users", [UserController::class, 'readAll']);
$app->router->get("/updateUser", [UserController::class, 'updateUser']);
$app->router->post("/processUpdateUser", [UserController::class, 'processUpdateUser']);
$app->router->get("/createUser", [UserController::class, 'createUser']);
$app->router->post("/processCreateUser", [UserController::class, 'processCreate']);
$app->router->get("/deleteUser", [UserController::class, 'delete']);

// BOOKS
$app->router->get("/books", [BookController::class, 'books']);
$app->router->get("/updateBook", [BookController::class, 'update']);
$app->router->post("/processUpdateBook", [BookController::class, 'processUpdate']);
$app->router->get("/createBook", [BookController::class, 'create']);
$app->router->post("/processCreateBook", [BookController::class, 'processCreate']);
$app->router->get("/deleteBook", [BookController::class, 'delete']);

// READING LOG
$app->router->get("/readingLog", [ReadingLogController::class, 'myLog']);
$app->router->get("/createLog", [ReadingLogController::class, 'create']);
$app->router->post("/processCreateLog", [ReadingLogController::class, 'processCreate']);
$app->router->get("/updateLog", [ReadingLogController::class, 'update']);
$app->router->post("/processUpdateLog", [ReadingLogController::class, 'processUpdate']);
$app->router->get("/deleteLog", [ReadingLogController::class, 'delete']);

// ACHIEVEMENTS
$app->router->get("/achievements", [AchievementController::class, 'achievements']);
$app->router->post("/saveAchievementTask", [AchievementController::class, 'saveAchievementTask']);
$app->router->post("/deleteAchievementTask", [AchievementController::class, 'deleteAchievementTask']);

// USER REPORTS (graphs)
$app->router->get("/myReports", [UserReportController::class, 'myReports']);
$app->router->get("/getNumberOfBooksPerMonth", [UserReportController::class, 'getNumberOfBooksPerMonth']);
$app->router->get("/getNumberOfPagesPerMonth", [UserReportController::class, 'getNumberOfPagesPerMonth']);
$app->router->get("/getNumberOfGenres", [UserReportController::class, 'getNumberOfGenres']);
$app->router->get("/getNumberOfBooksPerStatus", [UserReportController::class, 'getNumberOfBooksPerStatus']);
$app->router->get("/getNumberOfBooksPerPageCount", [UserReportController::class, 'getNumberOfBooksPerPageCount']);

// ADMIN REPORTS
$app->router->get("/librarianReports", [LibrarianReportController::class, 'librarianReports']);
$app->router->get("/getBooksPerUser", [LibrarianReportController::class, 'getBooksPerUser']);
$app->router->get("/getBooksPerGenre", [LibrarianReportController::class, 'getBooksPerGenre']);

// LIBRARIAN REQUESTS
$app->router->get("/librarianRequests", [LibrarianRequestController::class, 'allRequests']);
$app->router->post("/librarianRequests/approve", [LibrarianRequestController::class, 'approve']);
$app->router->post("/librarianRequests/reject", [LibrarianRequestController::class, 'reject']);
$app->router->post("/user/requestLibrarian", [HomeController::class, 'requestLibrarian']);

// BOOKSHELF
$app->router->get("/myBookshelf", [BookshelfController::class, 'showBookshelf']);



$app->run();

?>