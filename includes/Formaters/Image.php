<?php
/**
 * Class describes entity of Image files.
 *
 * Class provide saving image to database, formatting images.
 * Created by PhpStorm.
 * User: root
 * Date: 04.10.16
 * Time: 16:10
 */

namespace Formaters;


use UserLib\UserTableGateway;

class Image
{
    private $uploadDir;
    private $uploadedFileName;
    private $table;

    function __construct(UserTableGateway $table)
    {
        $this->uploadDir = __DIR__.'/../../templates/default/images/avatars/';
        $name = Randomize::getRandomPicName(Randomize::IMG_JPEG, $table);
        $this->uploadedFileName = $this->uploadDir.$name;
        $this->table = $table;
    }
    
    public function save($id)
    {
        move_uploaded_file($_FILES['userfile']['tmp_name'], $this->uploadedFileName);
        $this->table->setAvatar($this->uploadedFileName, $id);
    }
}