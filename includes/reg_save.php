require_once __DIR__.'/vendor/autoload.php';
include_once 'lib/php/map_lib/DataBaseConnection.php';
include_once 'lib/php/map_lib/EventGeoObjToDB.php';
include_once 'lib/php/map_lib/BalloonTempComposer.php';

include 'config.php';
function reg_save () {
	 if ( isset($_POST["name"]) && isset($_POST["email"] ) && isset($_POST["password"]) && isset($_POST["password_repeat"])) {
        $name = ( !preg_match("|[\\<>'\"-/]+|", $_POST["name"]) ) ? $_POST["name"] : false;
        $email = ( preg_match("|^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$|", $_POST["email"]) ) ? $_POST["email"] : false;
        // проверка что пароли равны
        if ( $_POST["password"] == $_POST["password_repeat"]  ) {
            $password = password_hash(trim($_POST["password_repeat"]), PASSWORD_DEFAULT);
            if ( $name && $email && $password ) {
                global $db;

                // узнаем пользователь с таким  уже существует
                $u_id = $db->query("
                    SELECT
                        `u_id`
                    FROM
                        `users`
                    WHERE 
                        `u_email` = '".$email."'
                    LIMIT 1");
                list( $user_id) = $u_id->fetchColumn();
                if ( !$user_id ) {
                    $sql ="INSERT INTO USERS (`u_name`, `u_password`, `u_email`, `role_id`, `u_create_date`, `u_active_date`)
                         VALUES ('".$name."', '".$password."', '".$email."', 1, NOW(), NOW()); ";
                    $db->query($sql) ;
                    echo 'регистрация прошла успешно';
                } else echo 'Пользователь с таким email уже существует';
            } else echo 'Некорректные данные';

        } else  echo "не равны пароли";
    } 

}
