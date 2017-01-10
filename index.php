<!DOCTYPE html>
<html>
	<head>
		<title>Ben's Messenger</title>
		<link rel="stylesheet" href="reset.css">
		<link rel="stylesheet" href="main.css">
		<link rel="apple-touch-icon" href="apple-touch.png">
		<link rel="favicon" href="send.png">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="linkifyjs/linkify.min.js"></script>
		<script src="linkifyjs/linkify-jquery.min.js"></script>
		<script>
			var messages = [];
			var message_ids = [];
			var new_messages = [];
			date_now = new Date();
			
			var color = {};
			color.ORANGE = "#ff9800";
			color.PURPLE = "#9c27b0";
			color.RED = "#f44336";
			color.YELLOW = "#ffeb3b";
			color.BROWN = "#795548";
			
			
			
			class Message{
				constructor(id, owner, user, content, date){
					this.id = id;
					this.owner = owner;
					this.user = user;
					this.content = content.split("^").join("<br>");
					this.content = this.content.split("`").join("+");
					this.content = this.content.split("¬").join("&");
					this.content = this.content.split("|").join("%");
					this.content = this.content.split("~").join("#");
					messages.push(this);
					new_messages.push(this);
					message_ids.push(id);
					this.date = date;
					
				}
			}
			
			function showMenu(){
				document.getElementById("settings").style.display = "block";
			}
			function hideMenu(){
				document.getElementById("settings").style.display = "none";
				
			}			
			
			
			function prettyDateTime(input_datetime){//"MM:HH DD/MM/YYYY"				
				current_datetime = getDate();
				
				input_date = input_datetime.split(" ")[1];
				current_date = current_datetime.split(" ")[1];
				
				input_time = input_datetime.split(" ")[0];
				current_time = current_datetime.split(" ")[0];
				
				if(input_date == current_date){
					return input_time + " Today";
				}
				
				input_date = String(input_date).split("\/");
				current_date = String(current_date).split("\/");
				
				if(input_date[1] == current_date[1] && input_date[2] == current_date[2]){
					if(parseInt(input_date[0])+1 == parseInt(current_date[0])){
						return  input_time + " Yesterday";
					}
				}
				
				
				return input_datetime;
			}
			
			function getDate(){
				var dateString =  date_now.getHours()+":";
				
				if(date_now.getMinutes() < 10){
					dateString += "0" + date_now.getMinutes() + " ";
				}else{
					dateString += date_now.getMinutes() + " ";
				}
				
				if(date_now.getDate() < 10){
					dateString += "0" + date_now.getDate() + "/";
				}
				else{
					dateString += date_now.getDate() + "/";
				}
				if(date_now.getMonth() < 9){
					dateString += "0" + String(parseInt(date_now.getMonth())+1) + "/";
				}else{
					dateString += String(parseInt(date_now.getMonth())+1) + "/";
				}
				dateString += date_now.getFullYear();
				
				return dateString;
				
			}
			
			function activate(item){
				classes = item.className;
				classes = classes.split(" ");
				var active = false;
				for(var i = 0; i < classes.length; i += 1){
					if(classes[i] == "activated"){
						active = true;
						index = i;
						break;
					}
				}
				classOut = "";
				
				if(active){
					for(var i = 0; i < classes.length; i += 1){
						if(i != index){
							classOut += classes[i] + " ";
						}
					}
					classOut += " deactivated";
				}else{
					classOut = item.className + " activated";
				}
				
				item.className = classOut;
			}
			
			
			function loadMessages() {
			  var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				  addMessage(this.responseText)
				}
			  };
			  xhttp.open("GET", "get.php?t=" + Math.random(), true);
			  xhttp.send();
			}
			function sendMessage(){
				if($("#message").val() != ""){
					
					$.ajax({url: "send.php?message=" + $("#message").val().replace(/\n/g, "^").split("+").join("`").split("&").join("¬").split("%").join("|").split("#").join("~") +
							"&date=" + getDate(), success: function(result){
						document.getElementById("message").value = "";
						document.getElementById("message").style.height = "20%";
					}});
				}
			}			
			
			function autoGrow(element){
				element.style.height = "5px";
				element.style.height = (element.scrollHeight)+"px";
			}
			
			function addMessage(message){
				message.split("\n").forEach(function(item, index){
					if(item != ""){
						info = item.split(";");
						if(!message_ids.includes(info[0])){//info[0] is the id of the message
							new Message(info[0], info[1], info[2], info[3], info[4]);
						}
					} 
					});
				displayMessages();
			}
			
			
			function displayMessages(){
				if(new_messages.length > 0){
					output = "";
					new_messages.forEach(function(item, index){
						output += "<div class='entry " + item.owner + "' onclick='activate(this)'>" +
										"<div class='meta'>"+
											"<div class='name'>" + item.user + "</div>" +
											"<div class='date'>" + prettyDateTime(item.date) + "</div>" +
										"</div>" +
										"<div class='positioner'>" +
											"<div class='content'><p>" + item.content + "</p></div>" +
										"</div>"+
									"</div>";
					});
					
					$('#conversation').append(output);
					new_messages = [];
					$('#conversation').linkify();
					scrollBottom();
				}
			}
			
			
			function scrollBottom(){
				$("html, body").animate({ scrollTop: $(document).height() + 999999}, "slow");
			}
			
			
			$(document).ready(function(){
				scrollBottom();
				loadMessages();
				var messageLoader = setInterval(loadMessages, 1000);
				
				$("#send").click(function(){
					sendMessage();
				});
			});
			
			if(document.cookie.match(/^(.*;)?id=[^;]+(.*)?$/) == null){
				var a = new Date();
				a = new Date(a.getTime() +1000*60*60*24*365);
				document.cookie = "id="+Math.random() + ";expires="+ a.toGMTString()+";";
			}
		</script>
	</head>
	
	<body>
		
		<div id="settings">
			<div class="container">
				<h3>Settings</h3>
				<h4>Set your color:</h4>
				<input type="color">
				<h4>Set your name:</h4>
				<input type="name" placeholder="Anonymous"><br>
				
				<button>Apply</button>
				<button onclick="hideMenu()">Cancel</button>
			</div>
		</div>
		
		<div id="conversation"></div>
		
		<div id="messageForm">
<!--
			<button id="menu" onclick="showMenu()"><img src="menu.png" width="120px"></button>
-->
			<button id="send"><img src="send.png" width="120px"></button>
			<textarea oninput="autoGrow(this)" onchange="autoGrow(this)" placeholder="Send a message" id="message"></textarea>
		</div>
		
	</body>	
</html>