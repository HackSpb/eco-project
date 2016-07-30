<?php
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

            $sql ="INSERT INTO USERS (`u_password`, `u_email`, `role_id`, `u_create_date`, `u_active_date`)
                VALUES ('".$password."', '".$email."', 1, NOW(), NOW() ); ";
            $db->query($sql);

            session_destroy();
            session_start();
            $_SESSION = array();
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);

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
