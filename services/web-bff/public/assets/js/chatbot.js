// Phase 8 — chat widget. Vanilla JS, no build step (consistent with the
// rest of this Blade-only front end) — posts to web-bff's own /chat route,
// never to chatbot-service directly (the JWT stays server-side, see
// App\Http\Controllers\ChatController).
(function () {
    const widget = document.getElementById('chat-widget');
    if (!widget) return; // guest — the partial isn't rendered at all

    const toggle = document.getElementById('chat-toggle');
    const panel = document.getElementById('chat-panel');
    const closeBtn = document.getElementById('chat-close');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    function open() {
        panel.hidden = false;
        input.focus();
    }

    function close() {
        panel.hidden = true;
    }

    toggle.addEventListener('click', () => (panel.hidden ? open() : close()));
    closeBtn.addEventListener('click', close);

    function appendMessage(text, cssClass) {
        const el = document.createElement('div');
        el.className = 'cyber-chat-msg ' + cssClass;
        el.textContent = text;
        messages.appendChild(el);
        messages.scrollTop = messages.scrollHeight;
        return el;
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const text = input.value.trim();
        if (!text) return;

        appendMessage(text, 'cyber-chat-msg-user');
        input.value = '';
        input.disabled = true;

        const typing = document.createElement('div');
        typing.className = 'cyber-chat-typing';
        typing.textContent = 'Thinking…';
        messages.appendChild(typing);
        messages.scrollTop = messages.scrollHeight;

        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message: text }),
            });

            typing.remove();

            if (!response.ok) {
                appendMessage("Sorry, that didn't work — please try again.", 'cyber-chat-msg-error');
                return;
            }

            const data = await response.json();
            appendMessage(data.reply || '…', 'cyber-chat-msg-bot');
        } catch (error) {
            typing.remove();
            appendMessage('Connection issue — please try again.', 'cyber-chat-msg-error');
        } finally {
            input.disabled = false;
            input.focus();
        }
    });
})();
