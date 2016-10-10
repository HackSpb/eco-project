<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.10.16
 * Time: 3:32
 */

namespace EventLib;


use PDO;
use Silex\Application;
use UserLib\UserTableGateway;

class PostController
{
    private $app;
    private $db;

    public function __construct(Application $app, PDO $db)
    {
        $this->app = $app;
        $this->db = $db;
    }

    public function getPost($slug)
    {
        $eventTable = new EventsTableGateWay($this->db);
        $event = $eventTable->getEventBySlug($slug);
        
        $this->app['twig']->addGlobal('post', $event->getPostInfo());

        if (!empty($_SESSION['user']['u_id'])) {
            $this->getCurrentUser($_SESSION['user']['u_id']);
        }
    }

    private function getCurrentUser($id)
    {
        $userTable = new UserTableGateway($this->db);
        $user = $userTable->getUser($id);

        $this->app['twig']->addGlobal('mainUser', $user->getUser());
    }
}