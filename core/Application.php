<?php

namespace app\core;

class Application
{
    public Router $router;
    public Session $session;
    //pravimo Singleton objekat od nase Application klase
    public static Application $app;
    public function __construct()
    {
        $this->router = new Router();
        $this->session = new Session();
        self::$app = $this;
    }

    public function run()
    {
        $this->router->resolve();
    }
}