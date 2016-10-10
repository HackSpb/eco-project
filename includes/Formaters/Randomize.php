<?php
/**
 * Class making randomize names for uploaded files.
 * Created by PhpStorm.
 * User: root
 * Date: 07.10.16
 * Time: 1:45
 */

namespace Formaters;


use PDO;
use UserLib\UserTableGateway;

class Randomize
{
    const IMG_JPEG = ".jpeg";

    /**
     * @param $extension
     * @param UserTableGateway $table
     * @return bool|string - bool, if
     */
    public static function getRandomPicName($extension, UserTableGateway $table)
    {
        $fileName = uniqid().$extension;

        if (self::isPicFileNameExist($fileName, $table))
            self::getRandomPicName($extension, $table);
        else
            return $fileName;
    }
    
    private static function isPicFileNameExist($fileName, UserTableGateway $table) {
        return $table->isPicNameExist($fileName);
    }
}