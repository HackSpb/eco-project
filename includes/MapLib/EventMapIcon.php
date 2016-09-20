<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 01.08.2016
 * Time: 11:24
 */

namespace MapLib;


class EventMapIcon extends MapIcon
{
    private $pathToIcon;
    private $iconName;

    public function __construct()
    {
        $this->pathToIcon = "map_files/icon/event.svg";
        $this->iconName = 'Event#image';
    }

    function createIcon()
    {
        // TODO: Implement createIcon() method.
    }

    /**
     * Возвращает путь к картинке
     *
     * @return string
     */
    public function getPathToIcon()
    {
        return $this->pathToIcon;
    }

    /**
     * Возвращает название иконки для yandex map API
     * 
     * @return string
     */
    public function getIconName() {
        return $this->iconName;
    }

    /**
     * Изменяет путь к иконке
     * 
     * @param string $pathToIcon
     */
    public function setPathToIcon($pathToIcon)
    {
        $this->pathToIcon = $pathToIcon;
    }


}