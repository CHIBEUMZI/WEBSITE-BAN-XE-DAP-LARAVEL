{{-- <!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Chatbot Hỗ Trợ</title>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #eef1f5;
    }

    #chat-toggle-button {
      position: fixed;
      bottom: 24px;
      right: 24px;
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 14px 20px;
      border-radius: 50px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.25);
      cursor: pointer;
      z-index: 999;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    #chat-toggle-button:hover {
      background-color: #0056b3;
    }

    #chat-container {
      position: fixed;
      bottom: 100px;
      right: 24px;
      width: 380px;
      max-width: 95%;
      border-radius: 16px;
      background-color: #fff;
      box-shadow: 0 10px 30px rgba(0,0,0,0.25);
      display: none;
      flex-direction: column;
      overflow: hidden;
      z-index: 998;
      animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
      from { transform: translateY(40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    #chat-header {
      background: linear-gradient(135deg, #0066ff, #0099ff);
      color: #fff;
      padding: 18px;
      font-size: 18px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    #chat-header::before {
      content: "🤖";
      font-size: 22px;
    }

    #chat-box {
      height: 340px;
      padding: 16px;
      overflow-y: auto;
      background-color: #f7f9fb;
    }

    .message {
      display: flex;
      margin-bottom: 14px;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .message.bot,
    .message.user {
      max-width: 75%;
      padding: 10px 14px;
      border-radius: 16px;
      line-height: 1.5;
    }

    .message.bot {
      background-color: #e3f2fd;
      color: #0d47a1;
      border-top-left-radius: 0;
      margin-right: auto;
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    .message.user {
      background-color: #d4edda;
      color: #155724;
      border-top-right-radius: 0;
      margin-left: auto;
      display: flex;
      align-items: flex-start;
      flex-direction: row-reverse;
      gap: 10px;
      text-align: right;
    }

    .message-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: #ccc;
      flex-shrink: 0;
    }

    .message.bot .message-avatar {
      background: url('https://cdn-icons-png.flaticon.com/512/4712/4712107.png') no-repeat center;
      background-size: cover;
    }

    .message.user .message-avatar {
      background: url('https://cdn-icons-png.flaticon.com/512/9131/9131529.png') no-repeat center;
      background-size: cover;
    }

    #chat-input-container {
      display: flex;
      padding: 12px;
      border-top: 1px solid #ddd;
      background-color: #fff;
    }

    #chat-input {
      flex-grow: 1;
      padding: 10px 14px;
      border: 1px solid #ccc;
      border-radius: 24px;
      outline: none;
      font-size: 14px;
    }

    #send-button {
      padding: 10px 16px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 24px;
      margin-left: 10px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    #send-button:hover {
      background-color: #1e7e34;
    }
  </style>
</head>
<body>

  <button id="chat-toggle-button">💬 Chat với chúng tôi</button>

  <div id="chat-container">
    <div id="chat-header">Chatbot hỗ trợ</div>
    <div id="chat-box">
      <div class="message bot">
        <div class="message-avatar"></div>
        <div>Chào bạn! Tôi là trợ lý ảo, bạn cần giúp gì?</div>
      </div>
    </div>
    <div id="chat-input-container">
      <input type="text" id="chat-input" placeholder="Nhập tin nhắn...">
      <button id="send-button">Gửi</button>
    </div>
  </div>

  <script>
    const chatToggleButton = document.getElementById('chat-toggle-button');
    const chatContainer = document.getElementById('chat-container');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const chatBox = document.getElementById('chat-box');

    chatToggleButton.addEventListener('click', () => {
      chatContainer.style.display = chatContainer.style.display === 'flex' ? 'none' : 'flex';
    });

    sendButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') sendMessage();
    });

    function sendMessage() {
      const message = chatInput.value.trim();
      if (message === '') return;

      const userMessageDiv = document.createElement('div');
      userMessageDiv.classList.add('message', 'user');
      userMessageDiv.innerHTML = `<div class="message-avatar"></div><div>${message}</div>`;
      chatBox.appendChild(userMessageDiv);
      chatInput.value = '';
      chatBox.scrollTop = chatBox.scrollHeight;

      fetch('{{ route('chatbot.send') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
      })
      .then(response => response.json())
      .then(data => {
        const botMessageDiv = document.createElement('div');
        botMessageDiv.classList.add('message', 'bot');
        botMessageDiv.innerHTML = `<div class="message-avatar"></div><div>${data.reply}</div>`;
        chatBox.appendChild(botMessageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
      })
      .catch(error => {
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('message', 'bot');
        errorDiv.innerHTML = `<div class="message-avatar"></div><div>Đã có lỗi xảy ra. Vui lòng thử lại sau.</div>`;
        chatBox.appendChild(errorDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
      });
    }
  </script>
</body>
</html> --}}
