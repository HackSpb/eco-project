<?php
/**
 * Абстрактный класс, описывающий базовую логику создания иконок для карты
 *
 * Created by PhpStorm.
 * User: Олег
 * Date: 31.07.2016
 * Time: 22:49
 */

namespace MapLib;


abstract class MapIcon
{
    protected $mainPicture;
    protected $width;
    protected $height;

    abstract function createIcon();

    /**
     * @return string
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * @param string $mainPicture
     */
    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;
    }

    /**
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param integer $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param integer $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
}