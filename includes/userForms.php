<?php


use EventLib\EventsTableGateWay;
use Formaters\Image;
use UserLib\UserTableGateway;

spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class);
    require_once __DIR__.DIRECTORY_SEPARATOR.$class.'.php';
});



// регистрация пользователя
function regSave () {

    global  $app, $db, $form_err;

    // если нажали на кнопку
    if(isset($_POST['submit'])) {

        if ( isset($_POST["email"] ) && isset($_POST["password"]) && isset($_POST["password_repeat"]) 
            && !empty($_POST["email"] ) && !empty($_POST["password"]) && !empty($_POST["password_repeat"])) {

            // проверям email
            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {

                $form_err[] = 'Неверно введён email';
            }

            // проверка что пароли равны
            if ( $_POST["password"] != $_POST["password_repeat"]  ) {

                $form_err[] = 'Пароли не равны';
            }

            $password = password_hash(trim($_POST["password_repeat"]), PASSWORD_DEFAULT);
            $email = $_POST["email"];

            // проверяем, не сущестует ли пользователя с таким именем
            if ( $email && $password ) {
                global $db;

                // узнаем пользователь с таким  уже существует
                $res = $db->query("SELECT COUNT(`u_id`) FROM `users` WHERE `u_email` = '".$email."'");
                $count = $res->fetchColumn();
              
                if($count > 0) {
                    $form_err[] = 'Пользователь с таким email уже существует в базе данных';
                } 

            } 

        } else {
            $form_err[] = 'Необходимо заполнить все поля!';
        }

        // Если нет ошибок, то добавляем в БД нового пользователя
        if(count($form_err) == 0) {

            $sql ="
            	INSERT INTO 
            		`users`
            	SET 
            		`u_password`	= '".$password."',
            		`u_email`		= '".$email."',
            		`role_id`		= 3, 
            		`u_create_date`	= NOW(), 
            		`u_active_date`	= NOW()
            ";
            $db->query($sql);


            $_SESSION = array();
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);

            authorizationCheck();
            // переход на главную страницу

            header("Location: /"); exit();
        }
    // иначе первый раз зашли на страницу
    } else {
        $form_err = false;
        $_POST['email'] = false;
    }

    $app['twig']->addGlobal('POST', $_POST['email']);
    $app['twig']->addGlobal('form_err', $form_err);
}

// авторизация пользователя
function authorizationCheck(){

    global  $app, $db, $form_err;

    // если нажали на кнопку
    if(isset($_POST['submit'])) {


        if ( isset($_POST["email"] ) && isset($_POST["password"]) && !empty($_POST["email"] ) && !empty($_POST["password"])) {

            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {

                $form_err[] = "Вы ввели неправильный email";
            } else {

                $email = $_POST["email"];

                // Вытаскиваем из БД запись, у которой логин равняеться введенному
                if($result = $db->query("
                    SELECT
                        *
                    FROM
                        `users`
                        LEFT JOIN roles USING (role_id)
                    WHERE 
                            `u_email` = '".$email."'
                    LIMIT 1"))
                {

                $user = $result->fetch(PDO::FETCH_ASSOC);
                $hash_password = $user['u_password'];

                $password = trim($_POST["password"]);
                
                if ( !password_verify($password, $hash_password) ) {

                    $form_err[] = "Вы ввели неправильный пароль или емейл"; 
                }
                }
                else  $form_err[] = "Вы ввели неправильный пароль или емейл"; 

            }

        } else {
            $form_err[] = 'Необходимо заполнить все поля!';
        }

        // если пройдена авторизация
        if( count($form_err) == 0) {
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);
            // Если нет ошибок, то возвращаемся на главную страницу
            header("Location: /"); exit();
        }
        //else print_r($form_err);

    // иначе первый раз зашли на страницу
    } else {

        $form_err = false;
        $_POST['email'] = false;
    }
    
    $app['twig']->addGlobal('form_err', $form_err);
    $app['twig']->addGlobal('POST', $_POST['email']);
}

// сохранение и редактрирование анкеты
function profileEdit(){
	global  $app, $db, $form_err;

    $now = new DateTime();
    $app['twig']->addGlobal('now', $now->format('Y-m-d'));

	$usersTable = new UserTableGateway($db);

    $id = 0;

    if (session_status() == PHP_SESSION_ACTIVE && !empty($_SESSION['user']['u_id'])) {
        $id = $_SESSION['user']['u_id'];
    }
    
//    $user = $usersTable->getUser($id);
//    $app['twig']->addGlobal('user', $user->getUser());

    if (!empty($_POST)) {
        if (!empty($_POST['user_name'])) {
            $name = $_POST['user_name'];
            if (preg_match('/[\W ]{1,50}/', $name))
                $usersTable->setName($name, $id);
            else
                $form_err[] = "Неправильно введено имя. Имя может содержать буквы, пробелы, и не должно быть больше 50 символов";

        }

        if (!empty($_POST['user_surname'])) {
            $surname = $_POST['user_surname'];
            if (preg_match('/[\W ]{1,60}/', $surname))
                $usersTable->setSurname($surname, $id);
            else
                $form_err[] = "Неправильна введена фамилия. Фамилия может содержать буквы, пробелы, и не должны быть больше 50 символов";

        }

        if (!empty($_POST['user_gender'])) {
            $gender = intval($_POST['user_gender']);
            if ($gender < 3 && $gender >= 0)
                $usersTable->setGender($gender, $id);
            else
                $form_err[] = "Проблемы в выборе пола";
        }

        if (!empty($_POST['user-password']) && isset($_POST['user-password-check'])) {
            $password = $_POST['user-password'];
            $passwordCheck = $_POST['user-password-check'];

            if ($password == $passwordCheck && preg_match('//', $password))
                $usersTable->setPassword($password, $id);
            else
                $form_err[] = "Пароль должен содержать 1 букву или 1 цифру.";
        }

        if (!empty($_POST['user-email'])) {
            $email = $_POST['user-email'];

            if (preg_match('/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/', $email))
                $usersTable->setEmail($email, $id);
            else
                $form_err[] = "Адрес почтового ящика введен некоректно";
        }

        if (!empty($_POST['user_birthday'])) {
            $birthday = new DateTime($_POST['user_birthday']);
            $now = new DateTime();

            if (strtotime($birthday->format('Y-m-d')) < strtotime($now->format('Y-m-d')))
                $usersTable->setBirthday($birthday->format('Y-m-d'), $id);
            else
                $form_err[] = "Ошибка. Дата рождения не может быть позже текущего времени";
        }

        
        if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $image = new Image($usersTable);
            $image->save($id);
        }
    }

    $app['twig']->addGlobal('form_err', $form_err);

}

//Вывод анкеты
function getProfile($profileID)
{
    global $app, $db, $form_err;

    $userTable = new UserTableGateway($db);

    $isExist = $userTable->isIDExist($profileID);
    if (!$isExist) {
        $app->abort(404, 'Нет такого пользователя');
    }

    if (!empty($_SESSION['user']['u_id'])) {
        $mainUserID = $_SESSION['user']['u_id'];
        $mainUser = $userTable->getUser($mainUserID);
        
        $app['twig']->addGlobal('mainUser', $mainUser->getUser());
    }

    $user = $userTable->getUser($profileID);
    $userData = $user->getUser();
    


    $app['twig']->addGlobal('user', $userData);
    $app['twig']->addGlobal('isAllowEditing', isAllowEditing($profileID));


    $date = new DateTime();
    $app['twig']->addGlobal('now', $date->format('Y-m-d'));
    $app['twig']->addGlobal('form_err', $form_err);
    

    $app['twig']->addGlobal('gender', $user->getGender());
}

function isAllowEditing($profileID) {
    if (!empty($_SESSION) && $profileID == $_SESSION['user']['u_id'])
        return true;
    else 
        return false;
}

function getUserPosts() {
    global $db, $app;
    $eventTable = new EventsTableGateWay($db);
    $id = $_SESSION['user']['u_id'];

    $events = $eventTable->getUserEvents($id);
    $eventsDataArray = array();
    
    foreach ($events as $event) {
        $eventsDataArray[] = $event->getArrayData();
    }
    
    $app['twig']->addGlobal('events', $eventsDataArray);
}