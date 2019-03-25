<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Chat - sistemas distribuidos</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

<?php 
$colours = array('007AFF','FF7000','FF7000','15E25F','CFC700','CFC700','CF1100','CF00BE','F00');
$user_colour = array_rand($colours);
?>

<script src="js/jquery.min.js"></script>

<script language="javascript" type="text/javascript">  
$(document).ready(function(){
  //criando um novo objeto WebSocket.
  var wsUri = "ws://192.168.17.15:9000/chat/server.php";
  websocket = new WebSocket(wsUri); 
  
  websocket.onopen = function() { // abre a coneção
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
  
  websocket.onerror = function(ev){$('#message_box').append("<div class=\"system_error\">Ocorreu um erro</div>");}; 
  websocket.onclose   = function(ev){$('#message_box').append("<div class=\"system_msg\">Conexão Fechada</div>");}; 
});
</script>
<script type="text/javascript">
  document.getElementById("message_box").scrollTop = document.getElementById("message_box").scrollHeight;

</script>
  <body>

    <div class="container">
      <div class="header">
        
        <h3 class="text-muted">Chat - sistemas distribuidos</h3>
      </div>

      <div class="jumbotron" style="padding-top:15px;">
        <div class="chat_wrapper">
          <div class="message_box" id="message_box"></div>
          <br>
          <div class="panel2">
            <div class="col-sm-2">
              <input type="text" class="form-control" name="name" id="name" placeholder="Seu nome" />
            </div>
            <div class="col-sm-8" style = "clear:both; margin-top:20px;">
              <textarea type="text" class="form-control" name="message" id="message" placeholder="Digite uma mensagem" style="margin:0px -0.015625px 0px 0px; height:80px; width: 925px;"></textarea>
            </div>
            <button class="btn btn-success" id="send-btn" style="clear:both; float:left; margin-left: 15px; margin-top: 20px; width: 200px; margin-bottom: 20px;">Enviar</button>
          </div>
        </div>
        
        <hr style="margin-top: 20px; margin-bottom: 20px; width:925px; height:1px; background: #ccc;">
        <div style="padding: 10px; background: #333; color: #fff; display:block; clear:both; margin: 20px 17px 0 17px; border-radius: 3px">
          <h1>Chat - Sistemas Distribuidos</h1>
          <p>Chat desenvolvido para a disciplina de sistemas distribuidos. Ultilizando as seguintes tecnologias, <strong>PHP</strong>, <strong>Javascript</strong>, <strong>HTML5</strong> e <strong>Bootstrap</strong></p>
        </div>

      </div>

      <div class="footer">
        <p>&copy; Leandro Nascimento 2014</p>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
