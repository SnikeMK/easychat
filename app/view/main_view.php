<div class="allchat">
<div class="border">
	<div id="WebChatFormForm">
	</div>
</div>
<form method="post" id="chat-form">
	<input type="text" id="nick" placeholder="Nickname">
	<input type="text" id="message-text" autocomplete="off" placeholder="Message">
	<input id="btn" type="submit" value="Send">
</form>
</div>

<script type="text/javascript">
update();
var interval = null; //Переменная с интервалом подгрузки сообщений
var chatplace = document.getElementById("WebChatFormForm");
chatplace.scrollTop = chatplace.scrollHeight;

var send_form = document.getElementById('chat-form'); //Форма отправки
var message_input = document.getElementById('message-text'); //Инпут для текста сообщения
var nickname = document.getElementById('nick');

var msg_index = 0;

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
		var time = new Date(data[index]['time']*1000);
		var options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    timezone: 'UTC',
    hour: 'numeric',
    minute: 'numeric',
  	};
		var msg = document.createElement('div');
		msg.className = "container";
		msg.innerHTML = '<p><span style="color: #04944a">' + data[index]['nick'] + ':</span> ' + data[index]['message'] + '<span class="time-right">' + time.toLocaleDateString("ru", options) + '</span></p>';
		WebChatFormForm.append(msg);
		msg_index = data[index]['msg_id'];
		chatplace.scrollTop = chatplace.scrollHeight;
	}
}

function update() {
	fetch('chat/loadMessages', {
    method: 'post',
		body: JSON.stringify(msg_index)
  })
	.then(function(response) {
		response.json().then(function(data) {
			insertMessage(data);
		});
	});
}
interval = setInterval(update,2500);
</script>
