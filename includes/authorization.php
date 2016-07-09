<?php

function authorization_check(){
	
    if(isset($_POST['submit'])) {

        // массив ошибок
        $err = array();

        if ( isset($_POST["email"] ) && isset($_POST["password"]) && !empty($_POST["email"] ) && !empty($_POST["password"])) {

            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {
                
                $err[] = "Вы ввели неправильный email"; 
            } else {

                $email = $_POST["email"];   
                global $db;

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

                    $err[] = "Вы ввели неправильный пароль"; 
                }

            }

        } else {
            $err[] = 'Необходимо заполнить все поля!';
        }

    }

    if (isset($err)) {

        // если пройдена авторизация
        if( count($err) == 0) {
            session_start();
            $_SESSION['user'] = $user;
            $app['twig']->addGlobal('user', $_SESSION['user']);
            // Если нет ошибок, то возвращаемся на главную страницу
            header("Location: /GreenAge"); exit();
        
         }
    } else {
        $err = false;
        $_POST['email'] = false;
    }

    $app['twig']->addGlobal('err', $err);
    $app['twig']->addGlobal('POST', $_POST['email']);
}