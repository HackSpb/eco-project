<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 02.10.16
 * Time: 16:22
 */

namespace UserLib;


class User
{
    protected $id;
    protected $name;
    protected $surname;
    protected $gender;
    protected $password;
    protected $email;
    protected $telNum;
    protected $birthday;
    protected $image;

    /**
     * Function return gender information.
     * @return string - gender
     */
    public function getGender()
    {
        switch ($this->gender) {
            case 'f':
                return 'Пол: женский.';
            case 'm':
                return 'Пол: мужской.';
            case 'b':
                return 'Пол: бинарный';
            default:
                return '';
        }
    }

    const FEMALE_GENDER = 0;
    const MALE_GENDER = 1;
    const BINARY_GENDER = 2;

    function __construct()
    {
    }

    public function setUser(array $data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->surname = $data['surname'];
        $this->gender = $data['gender'];
        $this->password = $data['password'];
        $this->email = $data['email'];
        $this->telNum = $data['telNum'];
        $this->birthday = $data['birthday'];
        $this->image = $data['image'];
    }

    /**
     * Return data from User object
     * @return array - data of this object
     */
    public function getUser() {
        $data = array(
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'gender' => $this->gender,
            'password' => $this->password,
            'email' => $this->email,
            'telNum' => $this->telNum,
            'birthday' => $this->birthday,
            'image' => $this->image,
        );

        return $data;
    }
}