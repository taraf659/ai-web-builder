@extends('layouts.app')

@section('title', 'Home - AI Web Builder')

@section('content')
<div class="hero-section">
  <h1 class="hero-title">Build Websites with AI</h1>
  <p class="hero-subtitle">Transform your ideas into stunning websites using the power of artificial intelligence. Get help with any web technology stack.</p>
  <a href="#chat-section" class="btn-primary">Start Building</a>
</div>

<div id="chat-section" class="chat-wrapper">
  <!-- Chat History Sidebar -->
  <div class="chat-sidebar" id="chatSidebar">
    <div class="sidebar-header">
      <h3>Chat History</h3>
      <button class="btn-icon" id="newChatBtnSidebar" onclick="startNewChat()" title="New Chat">
        <i class='bx bx-plus'></i>
      </button>
    </div>
    <div class="chat-sessions-list" id="chatSessionsList">
      <div class="loading-sessions">Loading chats...</div>
    </div>
  </div>

  <!-- Main Chat Area -->
  <div class="chat-container">
    <div class="chat-header">
      <button class="btn-icon mobile-menu-btn" id="toggleSidebar">
        <i class='bx bx-menu'></i>
      </button>
      <div>
        <h2>AI Assistant</h2>
        <p>Ask me anything about web development!</p>
      </div>
      <button class="btn-secondary" id="newChatBtn" onclick="startNewChat()">
        <i class='bx bx-plus'></i>
        New Chat
      </button>
    </div>

  <div class="chat-messages" id="chat-messages">
    <div class="message ai-message">
      <div class="message-avatar">
        <i class='bx bx-bot'></i>
      </div>
      <div class="message-content">
        <p>Hello! I'm your AI web development assistant. I can help you with any technology - HTML, CSS, JavaScript, React, Vue, Node.js, Python, PHP, databases, and more. How can I help you build your website today?</p>
      </div>
    </div>
  </div>

  <div class="chat-input-container">
    <form id="chat-form" class="chat-form">
      <input type="text" id="message-input" class="chat-input" placeholder="Type your message here..." required>
      <button type="submit" class="chat-send-btn">
        <i class='bx bx-send'></i>
      </button>
    </form>
  </div>
  </div>
</div>

<div class="features-section">
  <h2>Why Choose AI Web Builder?</h2>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">
        <i class='bx bx-brain'></i>
      </div>
      <h3>AI-Powered</h3>
      <p>Advanced AI algorithms create beautiful, functional websites tailored to your needs.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon">
        <i class='bx bx-code-alt'></i>
      </div>
      <h3>AI-Powered Development</h3>
      <p>Get intelligent assistance with any web technology - from beginner-friendly guidance to advanced coding help.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon">
        <i class='bx bx-mobile'></i>
      </div>
      <h3>Mobile Responsive</h3>
      <p>Your websites automatically adapt to all screen sizes and devices.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon">
        <i class='bx bx-rocket'></i>
      </div>
      <h3>Lightning Fast</h3>
      <p>Get your website up and running in minutes, not hours or days.</p>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* Simple ChatGPT-like code blocks */
.message-content pre {
  background: #f6f8fa;
  border: 1px solid #d1d9e0;
  border-radius: 6px;
  padding: 16px;
  margin: 8px 0;
  overflow-x: auto;
  font-size: 14px;
  line-height: 1.45;
  position: relative;
}

.message-content code {
  font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
  background: transparent;
  border: 0;
  padding: 0;
  margin: 0;
  word-wrap: normal;
  white-space: pre;
}

.message-content p {
  margin: 8px 0;
  line-height: 1.5;
}

.message-content p:first-child {
  margin-top: 0;
}

.message-content p:last-child {
  margin-bottom: 0;
}

/* Copy button styling */
.copy-code-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  background: #f6f8fa;
  border: 1px solid #d1d9e0;
  border-radius: 6px;
  padding: 4px 8px;
  font-size: 12px;
  color: #656d76;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 4px;
  transition: all 0.2s ease;
  opacity: 0;
}

.message-content pre:hover .copy-code-btn {
  opacity: 1;
}

.copy-code-btn:hover {
  background: #0969da;
  color: white;
  border-color: #0969da;
}

.copy-code-btn.copied {
  background: #238636;
  color: white;
  border-color: #238636;
}

.copy-code-btn i {
  font-size: 14px;
}

/* Language label */
.code-language-label {
  position: absolute;
  top: 8px;
  left: 8px;
  background: #656d76;
  color: white;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Chat History Sidebar - Override public CSS */
#chat-section.chat-wrapper {
  display: flex !important;
  flex-direction: row !important;
  gap: 0 !important;
  background: white !important;
  border-radius: 12px !important;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
  overflow: hidden !important;
  max-width: 1400px !important;
  margin: 2rem auto !important;
  height: 600px !important;
  width: auto !important;
}

#chat-section .chat-sidebar {
  width: 280px !important;
  min-width: 280px !important;
  background: #f8f9fa !important;
  border-right: 1px solid #e0e0e0 !important;
  display: flex !important;
  flex-direction: column !important;
  height: 100% !important;
  flex-shrink: 0 !important;
}

#chat-section .chat-container {
  flex: 1 !important;
  display: flex !important;
  flex-direction: column !important;
  min-width: 0 !important;
  height: 100% !important;
  margin: 0 !important;
  max-width: none !important;
  border-radius: 0 !important;
  box-shadow: none !important;
  background: white !important;
  border: none !important;
  overflow: hidden !important;
}

#chat-section .chat-header {
  padding: 1.5rem !important;
  border-bottom: 1px solid #e0e0e0 !important;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  color: white !important;
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  gap: 1rem !important;
  flex-shrink: 0 !important;
  text-align: left !important;
}

#chat-section .chat-header h2 {
  font-size: 1.5rem !important;
  margin: 0 !important;
  color: white !important;
}

#chat-section .chat-header p {
  font-size: 0.875rem !important;
  margin: 0 !important;
  opacity: 0.9 !important;
  color: white !important;
}

#chat-section .chat-messages {
  flex: 1 !important;
  overflow-y: auto !important;
  padding: 1.5rem !important;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
  scroll-behavior: smooth !important;
  height: auto !important;
  max-height: none !important;
}

#chat-section .chat-input-container {
  padding: 1rem !important;
  border-top: 1px solid #e0e0e0 !important;
  background: white !important;
  flex-shrink: 0 !important;
}

#chat-section .chat-form {
  display: flex !important;
  gap: 0.75rem !important;
}

#chat-section .chat-input {
  flex: 1 !important;
  padding: 0.875rem 1rem !important;
  border: 2px solid #e0e0e0 !important;
  border-radius: 12px !important;
  font-size: 1rem !important;
  transition: all 0.3s ease !important;
}

#chat-section .chat-input:focus {
  border-color: #6366f1 !important;
  outline: none !important;
}

#chat-section .chat-send-btn {
  padding: 0.875rem 1.5rem !important;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  color: white !important;
  border: none !important;
  border-radius: 12px !important;
  cursor: pointer !important;
  transition: all 0.3s ease !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

#chat-section .chat-send-btn:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
}

#chat-section .message {
  display: flex !important;
  gap: 0.75rem !important;
  margin-bottom: 1rem !important;
  animation: slideIn 0.3s ease !important;
}

#chat-section .message-avatar {
  width: 36px !important;
  height: 36px !important;
  border-radius: 50% !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  font-size: 1.25rem !important;
  flex-shrink: 0 !important;
}

#chat-section .user-message .message-avatar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  color: white !important;
}

#chat-section .ai-message .message-avatar {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
  color: white !important;
}

#chat-section .message-content {
  flex: 1 !important;
  padding: 0.875rem 1rem !important;
  border-radius: 12px !important;
  background: white !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}

#chat-section .user-message .message-content {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  color: white !important;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.typing-indicator {
  display: flex;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.typing-dots {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 0.875rem 1rem;
}

.typing-dots span {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #6366f1;
  animation: typing 1.4s infinite;
}

.typing-dots span:nth-child(2) {
  animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes typing {
  0%, 60%, 100% {
    opacity: 0.3;
    transform: scale(0.8);
  }
  30% {
    opacity: 1;
    transform: scale(1);
  }
}

.sidebar-header {
  padding: 1rem;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: white;
}

.sidebar-header h3 {
  font-size: 1rem;
  margin: 0;
  color: #333;
}

.chat-sessions-list {
  flex: 1;
  overflow-y: auto;
  padding: 0.5rem;
}

.chat-session-item {
  padding: 0.75rem;
  margin-bottom: 0.5rem;
  background: white;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  border: 1px solid transparent;
}

.chat-session-item:hover {
  border-color: #6366f1;
  transform: translateX(2px);
}

.chat-session-item.active {
  background: #6366f1;
  color: white;
  border-color: #6366f1;
}

.session-preview {
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 0.25rem;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.session-meta {
  font-size: 0.75rem;
  opacity: 0.7;
  display: flex;
  justify-content: space-between;
}

.loading-sessions {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-size: 0.875rem;
}

.empty-sessions {
  text-align: center;
  padding: 2rem 1rem;
  color: #999;
  font-size: 0.875rem;
}

.mobile-menu-btn {
  display: none;
}

@media (max-width: 768px) {
  .chat-wrapper {
    margin: 1rem;
    flex-direction: column;
  }

  .chat-sidebar {
    position: fixed;
    left: -280px;
    top: 0;
    height: 100vh;
    z-index: 1000;
    transition: left 0.3s ease;
    max-height: 100vh;
  }

  .chat-sidebar.active {
    left: 0;
  }

  .mobile-menu-btn {
    display: flex;
  }

  .chat-container {
    width: 100%;
    height: 500px;
  }
}
</style>
@endpush

@push('scripts')
<script>
const chatForm = document.getElementById('chat-form');
const messageInput = document.getElementById('message-input');
const chatMessages = document.getElementById('chat-messages');
let currentSessionId = null;

// Generate a unique session ID
function generateSessionId() {
  return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

// Load chat history and sessions list on page load
// Check if there's a stored session, otherwise start fresh
const storedSessionId = localStorage.getItem('currentSessionId');
if (storedSessionId) {
  currentSessionId = storedSessionId;
  loadChatHistory();
} else {
  // Start with a fresh session
  currentSessionId = generateSessionId();
  localStorage.setItem('currentSessionId', currentSessionId);
}
loadChatSessions();

// Mobile sidebar toggle
document.getElementById('toggleSidebar')?.addEventListener('click', function() {
  document.getElementById('chatSidebar').classList.toggle('active');
});

chatForm.addEventListener('submit', async function(e) {
  e.preventDefault();

  const message = messageInput.value.trim();
  if (!message) return;

  // Add user message
  addMessage(message, 'user');
  messageInput.value = '';

  // Show typing indicator
  const typingIndicator = addTypingIndicator();

  try {
    const response = await fetch('{{ route("api.chat") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ 
        message: message,
        session_id: currentSessionId
      })
    });

    const data = await response.json();

    // Save session ID
    if (data.session_id) {
      currentSessionId = data.session_id;
      localStorage.setItem('currentSessionId', currentSessionId);
    }

    // Remove typing indicator
    typingIndicator.remove();

    // Add AI response
    addMessage(data.response, 'ai');
    
    // Reload sessions list
    loadChatSessions();

  } catch (error) {
    console.error('Error:', error);
    typingIndicator.remove();
    addMessage('Sorry, I encountered an error. Please try again.', 'ai');
  }
});

// Load chat history
async function loadChatHistory() {
  // Get session ID from localStorage
  currentSessionId = localStorage.getItem('currentSessionId');
  
  if (!currentSessionId) {
    return; // No history to load
  }

  try {
    const response = await fetch(`{{ route("api.chat.history") }}?session_id=${currentSessionId}`);
    const data = await response.json();

    if (data.success && data.messages.length > 0) {
      // Clear default message
      chatMessages.innerHTML = '';

      // Add all messages
      data.messages.forEach(msg => {
        addMessage(msg.message, msg.role);
      });
    }
  } catch (error) {
    console.error('Error loading chat history:', error);
  }
}

// Load chat sessions list
async function loadChatSessions() {
  try {
    const response = await fetch('{{ route("api.chat.sessions") }}');
    const data = await response.json();

    const sessionsList = document.getElementById('chatSessionsList');
    
    if (data.success && data.sessions.length > 0) {
      sessionsList.innerHTML = '';
      
      data.sessions.forEach(session => {
        const sessionItem = document.createElement('div');
        sessionItem.className = 'chat-session-item' + (session.session_id === currentSessionId ? ' active' : '');
        sessionItem.dataset.sessionId = session.session_id;
        sessionItem.onclick = () => loadSessionChat(session.session_id);
        
        const date = new Date(session.last_message_at);
        const timeAgo = getTimeAgo(date);
        
        sessionItem.innerHTML = `
          <div class="session-preview">${session.preview}</div>
          <div class="session-meta">
            <span>${session.message_count} messages</span>
            <span>${timeAgo}</span>
          </div>
        `;
        
        sessionsList.appendChild(sessionItem);
      });
    } else {
      sessionsList.innerHTML = '<div class="empty-sessions">No chat history yet.<br>Start a new conversation!</div>';
    }
  } catch (error) {
    console.error('Error loading sessions:', error);
    document.getElementById('chatSessionsList').innerHTML = '<div class="empty-sessions">Error loading chats</div>';
  }
}

// Load a specific session's chat
async function loadSessionChat(sessionId) {
  currentSessionId = sessionId;
  localStorage.setItem('currentSessionId', sessionId);
  
  // Clear current messages
  chatMessages.innerHTML = '';
  
  try {
    const response = await fetch(`{{ route("api.chat.history") }}?session_id=${sessionId}`);
    const data = await response.json();

    if (data.success && data.messages.length > 0) {
      data.messages.forEach(msg => {
        addMessage(msg.message, msg.role);
      });
    }
    
    // Update active state
    document.querySelectorAll('.chat-session-item').forEach(item => {
      item.classList.remove('active');
      if (item.dataset.sessionId === sessionId) {
        item.classList.add('active');
      }
    });
    
    // Close mobile sidebar if open
    document.getElementById('chatSidebar').classList.remove('active');
  } catch (error) {
    console.error('Error loading session:', error);
  }
}

// Helper function to get time ago
function getTimeAgo(date) {
  const seconds = Math.floor((new Date() - date) / 1000);
  
  if (seconds < 60) return 'Just now';
  if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
  if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
  if (seconds < 604800) return Math.floor(seconds / 86400) + 'd ago';
  return date.toLocaleDateString();
}

// Start new chat
function startNewChat() {
  // Generate a NEW unique session ID
  currentSessionId = generateSessionId();
  localStorage.setItem('currentSessionId', currentSessionId);
  
  // Clear chat messages
  chatMessages.innerHTML = '';
  
  // Remove active state from all sessions
  document.querySelectorAll('.chat-session-item').forEach(item => {
    item.classList.remove('active');
  });
  
  // Add default AI message
  const defaultMessage = document.createElement('div');
  defaultMessage.className = 'message ai-message';
  defaultMessage.innerHTML = `
    <div class="message-avatar">
      <i class='bx bx-bot'></i>
    </div>
    <div class="message-content">
      <p>Hello! I'm your AI web development assistant. I can help you with any technology - HTML, CSS, JavaScript, React, Vue, Node.js, Python, PHP, databases, and more. How can I help you build your website today?</p>
    </div>
  `;
  chatMessages.appendChild(defaultMessage);
  
  // Focus on input
  messageInput.focus();
  
  // Close mobile sidebar if open
  document.getElementById('chatSidebar').classList.remove('active');
}

function addMessage(content, type) {
  const messageDiv = document.createElement('div');
  messageDiv.className = `message ${type}-message`;

  const avatarDiv = document.createElement('div');
  avatarDiv.className = 'message-avatar';

  const icon = document.createElement('i');
  icon.className = type === 'user' ? 'bx bx-user' : 'bx bx-bot';
  avatarDiv.appendChild(icon);

  const contentDiv = document.createElement('div');
  contentDiv.className = 'message-content';

  // Convert markdown-style code blocks to HTML
  const formattedContent = formatMessageContent(content);
  contentDiv.innerHTML = formattedContent;

  messageDiv.appendChild(avatarDiv);
  messageDiv.appendChild(contentDiv);

  chatMessages.appendChild(messageDiv);
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

function formatMessageContent(content) {
  // DEBUG: Log what we received
  console.log('AI Response:', content);

  // More aggressive HTML detection - if it looks like HTML at all, treat as code
  const hasHtmlTags = /<[^>]+>/.test(content);
  const hasDoctype = /<!DOCTYPE/i.test(content);
  const hasHtmlTag = /<html/i.test(content);
  const hasBodyTag = /<body/i.test(content);
  const hasDivTag = /<div/i.test(content);

  if ((hasHtmlTags || hasDoctype || hasHtmlTag || hasBodyTag || hasDivTag) && !/```/.test(content)) {
    console.log('Detected HTML content, wrapping in code block');
    // Looks like HTML - escape and wrap it in a code block
    const escapedContent = content.trim().replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const codeId = 'code-' + Math.random().toString(36).substr(2, 9);
    return `<pre><code id="${codeId}">${escapedContent}</code><span class="code-language-label">html</span><button class="copy-code-btn" onclick="copyCode('${codeId}')">
      <i class='bx bx-copy'></i>
      Copy code
    </button></pre>`;
  }

  // Check if content contains CSS but no code blocks - auto-wrap in code blocks
  if (/\{[^}]*\}/.test(content) && !/```/.test(content)) {
    console.log('Detected CSS content, wrapping in code block');
    // Looks like plain CSS - escape and wrap it in a code block
    const escapedContent = content.trim().replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const codeId = 'code-' + Math.random().toString(36).substr(2, 9);
    return `<pre><code id="${codeId}">${escapedContent}</code><span class="code-language-label">css</span><button class="copy-code-btn" onclick="copyCode('${codeId}')">
      <i class='bx bx-copy'></i>
      Copy code
    </button></pre>`;
  }

  let result = '';

  // Process code blocks first
  const codeBlockRegex = /```(\w+)?\n?([\s\S]*?)```/g;
  let lastIndex = 0;
  let match;

  while ((match = codeBlockRegex.exec(content)) !== null) {
    // Add any text before this code block, escaped
    const beforeText = content.slice(lastIndex, match.index);
    if (beforeText.trim()) {
      result += `<p>${beforeText.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>')}</p>`;
    }

    // Add the code block - ESCAPE HTML TAGS TO PREVENT RENDERING
    const language = match[1] || '';
    const code = match[2].trim();
    // Escape HTML in code to display as text, not render it
    const escapedCode = code.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const codeId = 'code-' + Math.random().toString(36).substr(2, 9);
    result += `<pre><code id="${codeId}">${escapedCode}</code>${
      language ? `<span class="code-language-label">${language}</span>` : ''
    }<button class="copy-code-btn" onclick="copyCode('${codeId}')">
      <i class='bx bx-copy'></i>
      Copy code
    </button></pre>`;

    lastIndex = codeBlockRegex.lastIndex;
  }

  // Add any remaining text after the last code block, escaped
  const remainingText = content.slice(lastIndex);
  if (remainingText.trim()) {
    result += `<p>${remainingText.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>')}</p>`;
  }

  // If no code blocks were found, escape everything
  if (!result) {
    console.log('No code blocks found, escaping all content');
    result = `<p>${content.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>')}</p>`;
  }

  console.log('Final result:', result);
  return result;
}

function addTypingIndicator() {
  const indicatorDiv = document.createElement('div');
  indicatorDiv.className = 'message ai-message typing-indicator';

  const avatarDiv = document.createElement('div');
  avatarDiv.className = 'message-avatar';
  avatarDiv.innerHTML = "<i class='bx bx-bot'></i>";

  const contentDiv = document.createElement('div');
  contentDiv.className = 'message-content';
  contentDiv.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';

  indicatorDiv.appendChild(avatarDiv);
  indicatorDiv.appendChild(contentDiv);

  chatMessages.appendChild(indicatorDiv);
  chatMessages.scrollTop = chatMessages.scrollHeight;

  return indicatorDiv;
}

// Copy code to clipboard (ChatGPT style)
function copyCode(codeId) {
  const codeElement = document.getElementById(codeId);
  const copyBtn = codeElement.parentElement.querySelector('.copy-code-btn');

  if (codeElement) {
    // Get the text content (which has escaped HTML entities)
    const textContent = codeElement.textContent;
    // Unescape HTML entities to get the actual code
    const actualCode = textContent.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');

    navigator.clipboard.writeText(actualCode).then(function() {
      // Change button text to show success
      const originalHTML = copyBtn.innerHTML;
      copyBtn.innerHTML = '<i class="bx bx-check"></i> Copied!';
      copyBtn.classList.add('copied');

      // Reset after 2 seconds
      setTimeout(() => {
        copyBtn.innerHTML = originalHTML;
        copyBtn.classList.remove('copied');
      }, 2000);
    }).catch(function(err) {
      console.error('Failed to copy: ', err);
      // Fallback for older browsers
      const textArea = document.createElement('textarea');
      textArea.value = actualCode;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand('copy');
      document.body.removeChild(textArea);

      const originalHTML = copyBtn.innerHTML;
      copyBtn.innerHTML = '<i class="bx bx-check"></i> Copied!';
      copyBtn.classList.add('copied');

      setTimeout(() => {
        copyBtn.innerHTML = originalHTML;
        copyBtn.classList.remove('copied');
      }, 2000);
    });
  }
}
</script>
@endpush
