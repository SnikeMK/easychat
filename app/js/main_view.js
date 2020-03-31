update();
var interval = null; //Переменная с интервалом подгрузки сообщений
var chatplace = document.getElementById("WebChatFormForm");
chatplace.scrollTop = chatplace.scrollHeight;

var send_form = document.getElementById('chat-form'); //Форма отправки
var message_input = document.getElementById('message-text'); //Инпут для текста сообщения
var nickname = document.getElementById('nick');

var msg_index = 0;
var on_focus = 1;
var unread = 0;
var title = document.title;

var iconDefault = 'favicon.ico';
var iconNew = 'favicon_unread.ico';

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
		msg.innerHTML = '<p><span style="color: #04944a">' + data[index]['nick'] + ':</span> ' + data[index]['message'] + ' <span class="time-right">' + time.toLocaleDateString("ru", options) + '</span></p>';
		WebChatFormForm.append(msg);
		msg_index = data[index]['msg_id'];
		chatplace.scrollTop = chatplace.scrollHeight;
		if (on_focus == 0) {
			unread++;
			var new_title = '(' + unread + ') ' + title;
			document.title = new_title;
			document.getElementById('favicon').href = iconNew;
		}
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

window.onblur = function () {
	on_focus = 0;
};

window.onfocus = function () {
	on_focus = 1;
	onread = 0;
	document.title = title;
	document.getElementById('favicon').href = iconDefault;
};
interval = setInterval(update,2500);