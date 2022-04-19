const form = document.querySelector(".typing-area"),
	in_id = form.querySelector(".in_id").value,
	input_field = form.querySelector(".input-field"),
	sendButton = form.querySelector("button"),
	chatBox = document.querySelector(".chat-box");

input_field.focus();
input_field.onkeyup = () => {
	if (input_field.value != "") {
		sendButton.classList.add("active");
	} else {
		sendButton.classList.remove("active");
	}
};

chatBox.onmouseenter = () => {
	chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
	chatBox.classList.remove("active");
};

function scrollToBottom() {
	chatBox.scrollTop = chatBox.scrollHeight;
}

setInterval(() => {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "get_chat.php", true);
	xhr.onload = () => {
		if (xhr.readyState === XMLHttpRequest.DONE) {
			if (xhr.status === 200) {
				let data = xhr.response;
				chatBox.innerHTML = data;
				if (!chatBox.classList.contains("active")) {
					scrollToBottom();
				}
			}
		}
	};
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send("in_id=" + in_id);
}, 500);
