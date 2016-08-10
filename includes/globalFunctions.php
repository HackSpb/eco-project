<?php
/*******
** Глобальные функции
********/

function checkRights($role,&$array)
{
/*проверка доступа
checkRights( номер уровня прав или название роли,массив с пользовательскими данными)

*/
	global $root_dir;
	if(!is_numeric($array['role_id'])) return false;

	if(is_file($root_dir."temp/.roles")){
			$roles = unserialize(file_get_contents($root_dir."temp/.roles"));
		}
	else{
		updateRoles();
		$roles = unserialize(file_get_contents($root_dir."temp/.roles"));
	}


	if(is_numeric($role)) $role_level=$role;
	else $role_level = $roles[$role][1];
 
	foreach($roles as $key=>$val)
		{
			if($val[0]==$array['role_id']) {
				$user_role_level = $val[1];
				$user_role_name = $key;
			}		
		}

	//echo $role_level.' / '. $user_role_level .' / '. $user_role_name ;

	if( 
		((is_numeric($role)) && ($user_role_level>=$role_level)) ||
	    ((!is_numeric($role)) && (($user_role_level >$role_level ) || $user_role_name==$role  ))
	   )
	    {
	    	/*
			если наше условие - номер уровня прав, то доступ разрешен при уровне пользователя >= нужного уровня
			если условие - название роли, то доступ разрешен при уровне пользователя > нужного, либо совпадении названий уровней.
			это нужно для отсеивание пользователей с тем же уровнем прав, но разными названиями (автор страниц / автор событий/ автор гео-объектов)
	    	*/

	    	return true;
	    } 
	  else return false;  
}

function updateRoles()
{
	// обновление файла ролей
	global  $db;
	$role = [];
	$result = $db->query("SELECT * FROM `roles`");

                While ($t = $result->fetch(PDO::FETCH_ASSOC))
                {
                	$roles[$t['role_name']][] = $t['role_id'];
                	$roles[$t['role_name']][] = $t['role_level'];
                }
                
  				file_put_contents($root_dir."temp/.roles", serialize($roles));
}


function translit($s) {
	//функция транслита
  $s = (string) $s; // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  $s = preg_replace("/[^0-9a-z-_ ]/i", "_", $s); // очищаем строку от недопустимых символов
  $s = str_replace(" ", "_", $s); // заменяем пробелы знаком минус
  while(stripos($s, '__')!== false){
  	$s = str_replace("__", "_", $s);
  }; // заменяем несколько знаков на один
  
  return $s; // возвращаем результат
}

function smart_cut($s , $length){
	//умная обрезка до заданной длины без распиливания слов
	 if(strlen($s)>$length){
	 	if(strripos($s,' ') == $length){
	 		$s = substr($s, 0, $length);
	 		return trim($s);
	 	}
 		$s = substr($s, 0, $length);
	 	if(strripos($s,' ') !== false)
	 	$s = substr($s, 0, strripos($s,' '));
	 	
	 	$s = trim($s, ',');
	 }
	 return $s;
}