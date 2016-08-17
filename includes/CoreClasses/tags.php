<?php /***************
**** Класс tags
****
*************/

namespace CoreClasses;

class tags
{
	var $max_level = 7 ;
 	
 	public function test() {
	echo 123456; 
 	}

 	static public function selectBranch($mpatch = 0)
 	{
 		global $db;

 		$sql = "SELECT * FROM tags_tree WHERE mpatch>".$mpatch." and mpatch<".$mpatch+ $this::getCoefficientByPatch($mpatch)." ORDER BY mpatch";

		foreach ($db->query($sql) as $row) {
       		$short_mpatch = $row['mpatch'] - $mpatch;
       		$tags[] = $row;

    	}
 	}

 	static public function getCoefficientBylevel ($level) {
		// по 2 нуля на уровень
		return $coefficient = pow(10,($this->max_level-$level)*2);
 	}

 	static public function getCoefficientByPatch ($mpatch) {
		// по 2 нуля на уровень
		return (int) '1'.strstr((string)$mpatch,'00');
 	}

 	public function recursive_tree($array){
 		//$row
 	}


}






?>