<?php
function reg_save () {
    
	 if(isset($_POST['submit'])) {

        // массив ошибок
        $err = array();

        if ( isset($_POST["email"] ) && isset($_POST["password"]) && isset($_POST["password_repeat"]) 
            && !empty($_POST["email"] ) && !empty($_POST["password"]) && !empty($_POST["password_repeat"])) {

            // проверям email
            if ( !preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) {

                $err[] = 'Неверно введён email';
            }

            // проверка что пароли равны
            if ( $_POST["password"] != $_POST["password_repeat"]  ) {

                $err[] = 'Пароли не равны';
            }

            $password = password_hash(trim($_POST["password_repeat"]), PASSWORD_DEFAULT);
            $email = $_POST["email"];

            // проверяем, не сущестует ли пользователя с таким именем
            if ( $email && $password ) {
                global $db;

                // узнаем пользователь с таким  уже существует
                $query = $db->query("SELECT * FROM `users` WHERE `u_email` = '".$email."'");

                if($query->rowCount() > 0) {
                    $err[] = 'Пользователь с таким email уже существует в базе данных';
                } 

            } 

        } else {
            $err[] = 'Необходимо заполнить все поля!';
        }

        // Если нет ошибок, то добавляем в БД нового пользователя
        if(count($err) == 0) {

            $sql ="INSERT INTO USERS (`u_password`, `u_email`, `role_id`, `u_create_date`, `u_active_date`)
                VALUES ('".$password."', '".$email."', 1, NOW(), NOW() ); ";
            $db->query($sql) ;

            header("Location: /GreenAge"); exit();
        // иначе выводим ошибки
        } else {
            print $msg_error = "<b>При регистрации произошли следующие ошибки:</b><br>";
            foreach($err AS $error) {
                print $error."<br>";
            }
        }
    }
}
