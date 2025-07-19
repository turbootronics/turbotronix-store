<!-- AI Chat Assistant -->
<div class="chat-box">
  <h2>💬 Chat With AI Assistant</h2>
  <div id="chatDisplay" style="height: 300px; overflow-y: auto; background: rgba(0,0,0,0.3); padding: 10px; border-radius: 10px; margin-bottom: 10px;">
    <div class="ai-bubble">👋 Hi! I’m your AI Assistant. Ask me anything related to your subjects.</div>
  </div>
  <form id="chatForm">
    <input type="text" id="userMessage" placeholder="Ask something..." autocomplete="off" required style="width: 75%; padding: 10px; border-radius: 8px; border: none;">
    <button type="submit" style="padding: 10px; border-radius: 8px; border: none; background: #00f7ff; color: black; font-weight: bold;">Send</button>
  </form>
</div>

<script>
  const chatForm = document.getElementById('chatForm');
  const chatDisplay = document.getElementById('chatDisplay');
  const userInput = document.getElementById('userMessage');

  function appendBubble(content, isUser = true) {
    const bubble = document.createElement('div');
    bubble.className = isUser ? 'user-bubble' : 'ai-bubble';
    bubble.innerText = content;
    chatDisplay.appendChild(bubble);
    chatDisplay.scrollTop = chatDisplay.scrollHeight;
  }

  chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const msg = userInput.value.trim();
    if (!msg) return;

    appendBubble(msg, true);
    userInput.value = '';

    appendBubble('⏳ Thinking...', false);

    try {
      const response = await fetch('https://your-render-url.onrender.com/gpt_proxy.php', {  // 🟢 REPLACE THIS
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: msg })
      });

      const data = await response.json();
      chatDisplay.lastChild.remove();

      const reply = data.choices?.[0]?.message?.content || '⚠️ No response received.';
      appendBubble(reply, false);
    } catch (err) {
      chatDisplay.lastChild.remove();
      appendBubble('❌ Failed to connect to AI. Try again.', false);
    }
  });
</script>

<style>
  .user-bubble, .ai-bubble {
    padding: 10px 15px;
    margin: 8px 0;
    border-radius: 15px;
    max-width: 80%;
    white-space: pre-wrap;
  }

  .user-bubble {
    background: #00f7ff;
    color: black;
    align-self: flex-end;
    margin-left: auto;
  }

  .ai-bubble {
    background: #1e1e2f;
    color: white;
    align-self: flex-start;
    margin-right: auto;
  }
</style>
