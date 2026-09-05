{{-- Phase 8 — Gemini-backed chat widget. Only rendered for authenticated
     users (guests see a plain login prompt instead of a functional widget —
     chatbot-service is role-aware, so there's no meaningful anonymous mode,
     see docs/chatbot.md). Posts to web-bff's own /chat route, never talks to
     chatbot-service directly — the JWT stays server-side. --}}
<div id="chat-widget" class="cyber-chat-widget">
    <button id="chat-toggle" class="cyber-chat-toggle" aria-label="Open assistant">
        <i class="fas fa-robot"></i>
    </button>

    <div id="chat-panel" class="cyber-chat-panel" style="display: none;" hidden>
        <div class="cyber-chat-header">
            <span><i class="fas fa-robot"></i> NextLevel Assistant</span>
            <button id="chat-close" aria-label="Close">&times;</button>
        </div>
        <div id="chat-messages" class="cyber-chat-messages">
            <div class="cyber-chat-msg cyber-chat-msg-bot">
                Hi {{ Auth::user()->name }}! Ask me about products, your cart, or your orders.
            </div>
        </div>
        <form id="chat-form" class="cyber-chat-form">
            <input type="text" id="chat-input" placeholder="Ask something…" autocomplete="off" maxlength="2000">
            <button type="submit" aria-label="Send"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>
