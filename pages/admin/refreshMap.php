<?php
/**
 * Created by PhpStorm.
 * User: Олег
 * Date: 23.05.2016
 * Time: 21:25
 */

include_once "../../lib/php/map_lib/MapRefresher.php";
include_once "../../lib/php/map_lib/PresetRefresher.php";
include_once "../../lib/php/map_lib/IconRefresher.php";

use MapLib\MapRefresher;
use MapLib\PresetRefresher;
use MapLib\IconRefresher;

$presetRefresher = new PresetRefresher();
$presetRefresher->refresh('../../js/mapScripts/preset.json');

$iconsRefresh = new IconRefresher();
$iconsRefresh->refresh('../../js/mapScripts/icons.json');

$mapRefresher = new MapRefresher();
$mapRefresher->refresh();



header('Location: addPointToMap.php');