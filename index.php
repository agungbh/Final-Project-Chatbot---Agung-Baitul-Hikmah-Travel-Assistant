<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agung Chatbot - Travel Assistant</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; display: flex; justify-content: center; }
        .chat-container { width: 100%; max-width: 450px; background: #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column; height: 80vh; }
        .chat-header { background: #008080; color: #fff; padding: 15px; text-align: center; font-weight: bold; }
        .chat-box { flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
        .message { padding: 10px 15px; border-radius: 20px; max-width: 80%; line-height: 1.4; font-size: 14px; word-wrap: break-word;}
        .user-msg { background: #e1f5fe; align-self: flex-end; border-bottom-right-radius: 2px; }
        .bot-msg { background: #f1f1f1; align-self: flex-start; border-bottom-left-radius: 2px; }
        .error-msg { background: #ffebee; color: #c62828; align-self: flex-start; border-bottom-left-radius: 2px; border: 1px solid #ef9a9a; font-family: monospace; font-size: 12px;}
        .input-area { display: flex; padding: 10px; border-top: 1px solid #ddd; background: #fff; }
        input[type="text"] { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 20px; outline: none; }
        button { background: #008080; color: white; border: none; padding: 10px 15px; margin-left: 10px; border-radius: 20px; cursor: pointer; }
        button:hover { background: #006666; }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">🌴 Agung Chatbot - Travel Assistant 🛵</div>
    <div class="chat-box" id="chat-box">
        <div class="message bot-msg">Halo!, Selamat datang di Agung Chatbot - Travel Assistant! Mau cari rekomendasi rute touring spot healing hemat buat weekend ini?</div>
    </div>
    <div class="input-area">
        <input type="text" id="user-input" placeholder="Ketik pesan di sini..." onkeypress="handleEnter(event)">
        <button onclick="sendMessage()">Kirim</button>
    </div>
</div>

<script>
    const chatBox = document.getElementById("chat-box");
    const userInput = document.getElementById("user-input");

    function appendMessage(sender, text, isError = false) {
        const msgDiv = document.createElement("div");
        if (sender === "user") {
            msgDiv.className = "message user-msg";
        } else if (isError) {
            msgDiv.className = "message error-msg"; // Styling khusus untuk pesan error
        } else {
            msgDiv.className = "message bot-msg";
        }
        msgDiv.innerHTML = text.replace(/\n/g, "<br>");
        chatBox.appendChild(msgDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function handleEnter(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    }

    async function sendMessage() {
        const text = userInput.value.trim();
        if (!text) return;

        appendMessage("user", text);
        userInput.value = "";
        
        // Tampilkan indikator typing
        const typingId = "typing-" + Date.now();
        const typingDiv = document.createElement("div");
        typingDiv.className = "message bot-msg";
        typingDiv.id = typingId;
        typingDiv.innerText = "Sedang mengetik...";
        chatBox.appendChild(typingDiv);
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            const response = await fetch("chat.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "message=" + encodeURIComponent(text)
            });
            const data = await response.json();
            
            document.getElementById(typingId).remove();
            
            if (data.status === "success") {
                appendMessage("bot", data.reply);
            } else {
                // MENAMPILKAN PESAN ERROR ASLI DARI PHP
                appendMessage("bot", "⚠️ <b>Error System:</b><br>" + data.message, true);
            }
        } catch (error) {
            document.getElementById(typingId).remove();
            appendMessage("bot", "⚠️ <b>Fatal Error:</b><br>Koneksi ke chat.php terputus atau file mengembalikan format yang salah.", true);
        }
    }
</script>

</body>
</html>