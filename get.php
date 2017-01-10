<?php
	$data = file_get_contents("conversation.txt");
	$data = explode("\n", $data);
	
	function idToName($id){
		$users_data = explode("\n", file_get_contents("users.txt"));
		
		for($i = 0; $i < sizeof($users_data); $i++){
			if(explode(";", $users_data[$i])[0] == $id){
				return explode(";", $users_data[$i])[1];
			}
		}
		return "Anonymous";
	}
	
	
	
	for($i = 0; $i < sizeof($data); $i++){
		$info = explode(";", $data[$i]);
		
		echo $info[0].";";
		if($info[1] == $_COOKIE['id']){
			echo "self;";
		}else{
			echo "not_self;";
		}
		echo idToName($info[1]) . ";"; 
		echo $info[2] . ";";
		echo $info[3] . "\n";
	}
?>