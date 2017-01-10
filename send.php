<?php
		$contents = file_get_contents("conversation.txt");
		$next_id = (explode(";", end(explode("\n", $contents)))[0])+1;
		file_put_contents("conversation.txt", $contents . "\n".$next_id.";".$_COOKIE['id'].";".$_GET["message"].";".$_GET["date"]);
?>