<!-- Bootstrap CSS + JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
/* Cho phép cuộn khi modal mở */
body.modal-open {
    overflow: auto !important;
}

/* Nút mở chat */
#chat-toggle-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
    width: 60px;
    height: 60px;
    background-color: #0066cc;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    color: white;
    font-size: 13px;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

#chat-toggle-btn img {
    width: 25px;
    height: 25px;
    margin-bottom: 4px;
}
.chat-box {
    width: 450px;
    height: 500px;
    display: flex;
    flex-direction: column;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.chat-header {
    background-color: #0095ff;
    color: white;
    padding: 12px 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chat-header img {
    width: 24px;
    height: 24px;
    margin-right: 8px;
}

#chatbox {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
    background: #f9f9f9;
}

.chat-msg {
    max-width: 80%;
    word-wrap: break-word;
    padding: 10px 15px;
    border-radius: 20px;
    margin: 5px 0;
    font-size: 14px;
}

.chat-msg.user {
    background-color: #DCF8C6;
    align-self: flex-end;
}

.chat-msg.bot {
    background-color: #ffffff;
    border: 1px solid #ccc;
    align-self: flex-start;
}

.chat-footer {
    padding: 10px;
    background: #fff;
    border-top: 1px solid #ccc;
}
</style>

<!-- Nút mở chat -->
<div id="chat-toggle-btn" onclick="toggleChat()">
    <img src="{{ asset('images/Chatbot/chatbot.jpg') }}" alt="Bot Icon">
    Trợ lý ảo
</div>

<!-- Khung Chat -->
<div class="modal" id="chatModal" tabindex="-1" data-bs-backdrop="false" data-bs-scroll="true">
  <div class="modal-dialog m-0 position-fixed" style="right: 20px; bottom: 100px; width: 500px; max-width: none;">
    <div class="modal-content chat-box">
      <div class="chat-header">
        <div class="d-flex align-items-center">
          <img src="{{ asset('images/Logo/Logo.png') }}" alt="logo">
          <span>Xedap.com</span>
        </div>
        <button class="btn btn-sm btn-light" onclick="toggleChat()">−</button>
      </div>
      <div id="chatbox" class="d-flex flex-column">
        <div class="chat-msg bot">Xin chào Anh/Chị! Em là trợ lý ảo.</div>
        <div class="chat-msg bot">Em rất sẵn lòng hỗ trợ Anh/Chị 😊</div>
      </div>
      <div class="chat-footer">
        <div class="input-group">
          <input type="text" id="message" class="form-control" placeholder="Nhập tin nhắn...">
          <button class="btn btn-primary" onclick="sendMessage()">Gửi</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script>
    const chatModalEl = document.getElementById('chatModal');
    const chatModal = new bootstrap.Modal(chatModalEl);

    function toggleChat() {
        if (chatModalEl.classList.contains('show')) {
            chatModal.hide();
        } else {
            chatModal.show();
            document.body.style.overflow = 'auto'; // Cho phép scroll khi mở chat
            scrollToBottom();
        }
    }

    function scrollToBottom() {
        const chatbox = document.getElementById('chatbox');
        chatbox.scrollTop = chatbox.scrollHeight;
    }

    function appendMessage(content, sender) {
        const div = document.createElement('div');
        div.className = `chat-msg ${sender}`;
        div.innerHTML = content;
        document.getElementById('chatbox').appendChild(div);
        scrollToBottom();
    }
    function showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing';
        typingDiv.className = 'chat-msg bot';
        typingDiv.innerHTML = `<img src="{{ asset('images/Chatbot/chatbot.jpg') }}" class="avatar" /><div><i>Đang nhập...</i></div>`;
        document.getElementById('chatbox').appendChild(typingDiv);
        scrollToBottom();
    }
    function removeTyping() {
        const typing = document.getElementById('typing');
        if (typing) typing.remove();
    }
    function sendMessage() {
        const input = document.getElementById('message');
        const message = input.value.trim();
        if (message === '') return;

        appendMessage(message, 'user');
        input.value = '';
        showTyping();
        $.ajax({
            url: '{{ url("/chat-gemini/send") }}',
            method: 'POST',
            data: {
                message: message,
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                const reply = data.reply.replace(/\n/g, '<br>');
                appendMessage(reply, 'bot');
                removeTyping();
            },
            error: function () {
                removeTyping();
                appendMessage('Xin lỗi, hiện không thể phản hồi.', 'bot');
            }
        });
    }
</script>
