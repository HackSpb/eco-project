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