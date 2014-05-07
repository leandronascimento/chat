<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8' />
<title>Chat - sistemas distribuidos</title>
<style type="text/css">
<!--
.chat_wrapper {
	width: 500px;
	margin-right: auto;
	margin-left: auto;
	background: #CCCCCC;
	border: 1px solid #999999;
	padding: 10px;
	font: 12px 'lucida grande',tahoma,verdana,arial,sans-serif;
}
.chat_wrapper .message_box {
	background: #FFFFFF;
	height: 150px;
	overflow: auto;
	padding: 10px;
	border: 1px solid #999999;
}
.chat_wrapper .panel input{
	padding: 2px 2px 2px 5px;
}
.system_msg{color: #BDBDBD;font-style: italic;}
.user_name{font-weight:bold;}
.user_message{color: #88B6E0;}
-->
</style>
</head>
<body>	
<?php 
$colours = array('007AFF','FF7000','FF7000','15E25F','CFC700','CFC700','CF1100','CF00BE','F00');
$user_colour = array_rand($colours);
?>

<script src="js/jquery.min.js"></script>

<script language="javascript" type="text/javascript">  
$(document).ready(function(){
	//criando um novo objeto WebSocket.
	var wsUri = "ws://localhost:9000/demo/server.php"; 	
	websocket = new WebSocket(wsUri); 
	
	websocket.onopen = function(ev) { // abre a coneção 
		$('#message_box').append("<div class=\"system_msg\">Conectado!</div>"); //Notificando o usuario que ele esta conectado
	}

	$('#send-btn').click(function(){ //usando o click para enviar as mensagens	
		var mymessage = $('#message').val(); //pegando o texto das mensagens
		var myname = $('#name').val(); //pegando o nome do usuario
		
		if(myname == ""){ //se o nome estiver vazio
			alert("Digite seu nome!");
			return;
		}
		if(mymessage == ""){ //se a mensagem estiver vazia
			alert("Escreva uma mensagem!");
			return;
		}
		
		//pereparando os dados para json
		var msg = {
		message: mymessage,
		name: myname,
		color : '<?php echo $colours[$user_colour]; ?>'
		};
		//convertendo e enviando os dados para o servidor
		websocket.send(JSON.stringify(msg));
	});
	
	//#### se a mensagem for recebida pelo servidor
	websocket.onmessage = function(ev) {
		var msg = JSON.parse(ev.data); //PHP envia dados Json 
		var type = msg.type; //tipo da mensagem
		var umsg = msg.message; //texto da mensagem
		var uname = msg.name; //nome do usuario
		var ucolor = msg.color; //cor

		if(type == 'usermsg') 
		{
			$('#message_box').append("<div><span class=\"user_name\" style=\"color:#"+ucolor+"\">"+uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");
		}
		if(type == 'system')
		{
			$('#message_box').append("<div class=\"system_msg\">"+umsg+"</div>");
		}
		
		$('#message').val(''); //limpanto o texto
	};
	
	websocket.onerror	= function(ev){$('#message_box').append("<div class=\"system_error\">Ocorreu um erro</div>");}; 
	websocket.onclose 	= function(ev){$('#message_box').append("<div class=\"system_msg\">Conexão Fechada</div>");}; 
});
</script>
<div class="chat_wrapper">
<div class="message_box" id="message_box"></div>
<div class="panel">
<input type="text" name="name" id="name" placeholder="Seu nome" maxlength="10" style="width:20%"  />
<input type="text" name="message" id="message" placeholder="Digite uma mensagem" maxlength="80" style="width:60%" />
<button id="send-btn">Enviar</button>
</div>
</div>
<div>
	Chat desenvolvido para a disciplina de sistemas distribuidos. Ultilizando as seguintes tecnologias, <strong>PHP</strong>, <strong>Javascript</strong>, <strong>HTML5</strong> e <strong>Bootstrap</strong>
</div>
</body>
</html>

