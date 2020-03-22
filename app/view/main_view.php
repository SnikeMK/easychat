<!-- <h1>Добро пожаловать!</h1>
<p>
<a href="/">ЗАГОЛОВОК</a><br/>
	И какой-то текст....
</p> -->
<div style="border: 1px solid #ccc;padding:10px; height: 500px;">
	<div id="WebChatFormForm" style="overflow: auto; height: 500px;">
	<?php foreach($messages as $message) { ?>
		<div class="container">
		  <p><span style="color: #04944a"><?php echo $message['nick']; ?>:</span> <?php echo $message['message']; ?></p>
		</div>
	<?php } ?>
	</div>
</div>
<form method="post" id="chat-form">
<input type="text" id="nick" placeholder="Nickname"> <input type="text" id="message-text" autocomplete="off" placeholder="Message"> <input type="submit" value="Send">
</form>

<script type="text/javascript">
var interval = null; //Переменная с интервалом подгрузки сообщений
var chatplace = document.getElementById("WebChatFormForm");
chatplace.scrollTop = chatplace.scrollHeight;

var send_form = document.getElementById('chat-form'); //Форма отправки
var message_input = document.getElementById('message-text'); //Инпут для текста сообщения
var nickname = document.getElementById('nick');

var msg_index = <?php echo end($messages)["msg_id"] ?>;

	send_form.onsubmit = function () {
	var form_data = [];
	var temp;
	form_data.push(nickname.value, message_input.value);
	fetch('chat/index', {
    method: 'post',
    body: JSON.stringify(form_data)
  })
	.then(function(response) {
		response.json().then(function(data) {
			insertMessage(data);
		});
	});
    message_input.value = '';
	return false; //Возвращаем ложь, чтобы остановить классическую отправку формы
};

function insertMessage(data) {
	for (index = 0, len = data.length; index < len; ++index) {
		if (msg_index >= data[index]['msg_id']) {
			continue;
		}
		var msg = document.createElement('div');
		msg.className = "container";
		msg.innerHTML = '<p><span style="color: #04944a">' + data[index]['nick'] + ':</span> ' + data[index]['message'] + '</p>';
		WebChatFormForm.append(msg);
		msg_index++;
		chatplace.scrollTop = chatplace.scrollHeight;
	}
}

function update() {
	fetch('chat/loadMessages', {
    method: 'post'
  })
	.then(function(response) {
		response.json().then(function(data) {
			insertMessage(data);
		});
	});
}
interval = setInterval(update,2000);
</script>
