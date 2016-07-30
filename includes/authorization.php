<?php

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
                $result = $db->query("
                    SELECT
                        *
                    FROM
                        `users`
                    WHERE 
                            `u_email` = '".$email."'
                    LIMIT 1");

                $user = $result->fetch();
                $hash_password = $user[3];

                $password = trim($_POST["password"]);

                if ( !password_verify($password, $hash_password) ) {

                    $form_err[] = "Вы ввели неправильный пароль"; 
                }

            }

        } else {
            $form_err[] = 'Необходимо заполнить все поля!';
        }

        // если пройдена авторизация
        if( count($form_err) == 0) {
            session_start();
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);
            // Если нет ошибок, то возвращаемся на главную страницу
            header("Location: /"); exit();
        }

    // иначе первый раз зашли на страницу
    } else {

        $form_err = false;
        $_POST['email'] = false;
    }

    $app['twig']->addGlobal('form_err', $form_err);
    $app['twig']->addGlobal('POST', $_POST['email']);
}