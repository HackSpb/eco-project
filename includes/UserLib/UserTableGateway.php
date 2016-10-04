<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.10.16
 * Time: 16:38
 */

namespace UserLib;


use PDO;

class UserTableGateway
{
    private $pdo;
    public static $tableName = "users";

    /**
     * UserTableGateway constructor. Get connection to database
     * @param PDO $pdo
     */
    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Getting all data from table user
     * @return array
     */
    public function getData() {
        $sql = 'SELECT * FROM '.self::$tableName;
        $stmt = $this->pdo->query($sql);

        $dataArr = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $dataArr;
    }

    /**
     * Get user data from table `users` and return an array.
     * @param $id - Integer, id of searching user.
     * @return array|boolean - array of user data or false, if there is no such user.
     */
    public function getDataArray($id) {


        $sql = 'SELECT * FROM '.self::$tableName." WHERE u_id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('id' => $id));

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $userData = array(
            'id' => $id,
            'name' => $data[0]['u_name'],
            'surname' => $data[0]['u_fname'],
            'gender' => $data[0]['u_gender'],
            'password' => $data[0]['u_password'],
            'email' => $data[0]['u_email'],
            'telNum' => $data[0]['u_telephone'],
            'birthday' => $data[0]['u_birthday'],
            'image' => $data[0]['u_avatar']
        );

        return $userData;
    }

    /**
     * Get data from `users` table and return an object type User
     * @param $id - Integer - id of user
     * @return User|false - object of user with chosen id or false, if there is no data.
     */
    public function getUser($id) {

        $data = $this->getDataArray($id);
        if ($data) {
            $user = new User();

            $user->setUser($data);

            return $user;
        }
        else
            return false;
    }

    public function setName($name, $id) {
        $tableName = self::$tableName;
        $sql = "UPDATE $tableName SET u_name = :name WHERE users.u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(
            'name' => $name,
            'id' => $id)
        );
    }

    public function setSurname($surname, $id) {
        $tableName = self::$tableName;
        $sql = "UPDATE $tableName SET u_fname = :surname WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('surname' => $surname, 'id' => $id));
    }

    public function setGender($gender, $id) {
        $tableName = self::$tableName;

        $genderString = $this->checkGender($gender);

        $sql = "UPDATE $tableName SET u_gender = :gender WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('gender' => $genderString, 'id' => $id));
    }

    public function setPassword($password, $id) {
        $tableName = self::$tableName;
        $hashedPass = hash('md5', $password);

        $sql = "UPDATE $tableName SET u_password = :pass WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('pass' => $hashedPass, 'id' => $id));
    }

    public function setEmail($email, $id) {
        $tableName = self::$tableName;
        $sql = "UPDATE $tableName SET u_email = :email WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('email' => $email, 'id' => $id));
    }

    public function setTelNum($telNum, $id) {
        $tableName = self::$tableName;
        $sql = "UPDATE $tableName SET u_telephone = :telNum WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('telNum' => $telNum, 'id' => $id));
    }

    public function setBirthday($birthday, $id) {
        $tableName = self::$tableName;
        $sql = "UPDATE $tableName SET u_birthday = :birthday WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('birthday' => $birthday, 'id' => $id));
    }

    public function setAvatar($img, $id) {
        $tableName = self::$tableName;
        $sql = "UPDATE $tableName SET u_avatar = :img WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('img' => $img, 'id' => $id));
    }

    /**
     * @param $gender - num of gender
     * @return string - gender in format for saving to database
     */
    private function checkGender($gender) {
        $genderString = "";

        switch ($gender) {
            case User::FEMALE_GENDER:
                $genderString = 'f';
                break;
            case User::MALE_GENDER:
                $genderString = 'm';
                break;
            case User::BINARY_GENDER:
                $genderString = 'b';
        }

        return $genderString;
    }

    /**
     * @param $id - Integer, searching id
     * @return bool - result of searching in database
     */
    public function isIDExist($id) {
        $tableName = self::$tableName;
        $sql = "SELECT u_id FROM $tableName WHERE u_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array('id' => $id));
        $data = $stmt->fetchAll();

        return !empty($data);
    }
}