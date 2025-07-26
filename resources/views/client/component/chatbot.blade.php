<!-- Bootstrap + jQuery -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/socket.io-client@4.6.1/dist/socket.io.min.js"></script>

<style>
body.modal-open {
    overflow: auto !important;
}

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
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin: 5px 0;
}

.chat-msg.user {
    justify-content: flex-end;
}

.chat-msg.bot {
    justify-content: flex-start;
}

.chat-msg .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-msg .bubble {
    max-width: 80%;
    word-wrap: break-word;
    padding: 10px 15px;
    border-radius: 20px;
    font-size: 14px;
}

.chat-msg.user .bubble {
    background-color: #DCF8C6;
    border-top-right-radius: 0;
}

.chat-msg.bot .bubble {
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-top-left-radius: 0;
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
                <div class="chat-msg bot">
                    <img src="{{ asset('images/Chatbot/chatbot.jpg') }}" class="avatar" />
                    <div class="bubble">Xin chào Anh/Chị! Em là trợ lý ảo.</div>
                </div>
                <div class="chat-msg bot">
                    <img src="{{ asset('images/Chatbot/chatbot.jpg') }}" class="avatar" />
                    <div class="bubble">Em rất sẵn lòng hỗ trợ Anh/Chị 😊</div>
                </div>
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

<script>
const chatModalEl = document.getElementById('chatModal');
const chatModal = new bootstrap.Modal(chatModalEl);

<<<<<<< HEAD
function toggleChat() {
    if (chatModalEl.classList.contains('show')) {
        chatModal.hide();
    } else {
        chatModal.show();
        document.body.style.overflow = 'auto';
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

    const avatarSrc = sender === 'user'
        ? '{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset("images/Chatbot/default-user.jpg") }}'
        : '{{ asset("images/Chatbot/chatbot.jpg") }}';

    const bubble = `<div class="bubble">${content}</div>`;
    const avatar = `<img src="${avatarSrc}" class="avatar">`;

    // user: bubble trước, avatar sau (phải)
    // bot: avatar trước, bubble sau (trái)
    div.innerHTML = sender === 'user' ? `${bubble}${avatar}` : `${avatar}${bubble}`;

    document.getElementById('chatbox').appendChild(div);
    scrollToBottom();
}

function showTyping() {
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing';
    typingDiv.className = 'chat-msg bot';
    typingDiv.innerHTML = `
        <img src="{{ asset('images/Chatbot/chatbot.jpg') }}" class="avatar" />
        <div class="bubble"><i>Đang nhập...</i></div>
    `;
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
            removeTyping();
            const reply = data.reply.replace(/\n/g, '<br>');
            appendMessage(reply, 'bot');
        },
        error: function () {
            removeTyping();
            appendMessage('Xin lỗi, hiện không thể phản hồi.', 'bot');
        }
    });
}
    function toggleChat() {
        if (chatModalEl.classList.contains('show')) {
            chatModal.hide();
        } else {
            chatModal.show();
            document.body.style.overflow = 'auto';
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
        typingDiv.innerHTML = `<i>Đang nhập...</i>`;
        document.getElementById('chatbox').appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTyping() {
        const typing = document.getElementById('typing');
        if (typing) typing.remove();
    }

    // Socket.IO setup for Rasa
    const socket = io("http://localhost:5005", {
        transports: ["websocket"]
    });

    const sessionId = "user_" + Math.floor(Math.random() * 1000000);

    socket.on("connect", () => {
        console.log("Connected to Rasa");
        socket.emit("session_request", { session_id: sessionId });
    });

    socket.on("session_confirm", (session) => {
        console.log("Session started:", session.session_id);
    });

    socket.on("bot_uttered", (msg) => {
        removeTyping();
        if (msg.text) {
            const reply = msg.text.replace(/\n/g, '<br>');
            appendMessage(reply, 'bot');
        }
    });

    function sendMessage() {
        const input = document.getElementById('message');
        const message = input.value.trim();
        if (message === '') return;

        appendMessage(message, 'user');
        input.value = '';
        showTyping();

        socket.emit("user_uttered", {
            message: message,
            session_id: sessionId
        });
    }

    document.getElementById('message').addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    document.addEventListener('click', function (event) {
        const modal = document.getElementById('chatModal');
        const toggleBtn = document.getElementById('chat-toggle-btn');
        const dialog = modal.querySelector('.modal-dialog');

        const clickedInsideModal = dialog.contains(event.target);
        const clickedToggleButton = toggleBtn.contains(event.target);

        const isVisible = modal.classList.contains('show');

        if (isVisible && !clickedInsideModal && !clickedToggleButton) {
            chatModal.hide();
        }
    });

</script>
