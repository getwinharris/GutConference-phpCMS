<?php
$supportPlaceholder = $supportPlaceholder ?? 'Ask about events, speakers, tickets, certificates, or improvements';
?>
<div class="support-widget" data-support-widget>
    <div class="support-panel" data-support-panel aria-hidden="true">
        <div class="support-chat">
            <div class="support-chat__head">
                <div><strong>Gemini Support Agent</strong><span>Grounded in GutConference CMS data</span></div>
                <button type="button" data-support-close aria-label="Minimize support">-</button>
            </div>
            <div class="support-messages" data-support-messages></div>
            <form class="support-form" data-support-form>
                <textarea name="message" rows="2" required placeholder="<?= e($supportPlaceholder) ?>"></textarea>
                <button class="support-send" type="submit" data-support-send aria-label="Send message"><span aria-hidden="true">↑</span></button>
            </form>
        </div>
    </div>
</div>
<script>
(() => {
    const widget = document.querySelector('[data-support-widget]');
    if (!widget) return;
    const panel = widget.querySelector('[data-support-panel]');
    const messages = widget.querySelector('[data-support-messages]');
    const form = widget.querySelector('[data-support-form]');
    const sendButton = widget.querySelector('[data-support-send]');
    let started = false;
    let controller = null;
    let workingRow = null;
    const open = () => {
        widget.classList.add('is-open');
        document.body.classList.add('support-open');
        panel?.setAttribute('aria-hidden', 'false');
        if (!started) {
            started = true;
            ask('__intro', false);
        }
    };
    const close = () => {
        widget.classList.remove('is-open');
        document.body.classList.remove('support-open');
        panel?.setAttribute('aria-hidden', 'true');
    };
    const scrollMessages = () => {
        messages.scrollTop = messages.scrollHeight;
    };
    const add = (text, type, links = []) => {
        const item = document.createElement('p');
        item.className = 'support-message support-message--' + type;
        item.textContent = text;
        messages.appendChild(item);
        if (links.length) {
            const actions = document.createElement('div');
            actions.className = 'support-actions';
            links.forEach(link => {
                if (!link.url || !link.label) return;
                const action = document.createElement('a');
                action.href = link.url;
                action.textContent = link.label;
                actions.appendChild(action);
            });
            messages.appendChild(actions);
        }
        scrollMessages();
    };
    const showWorking = () => {
        if (workingRow) return;
        workingRow = document.createElement('div');
        workingRow.className = 'support-message support-message--agent support-message--working';
        workingRow.setAttribute('role', 'status');
        workingRow.setAttribute('aria-live', 'polite');
        workingRow.innerHTML = '<span class="agent-orbit" aria-hidden="true"><i></i></span><span class="agent-working-copy">Agent is checking CMS data</span>';
        messages.appendChild(workingRow);
        scrollMessages();
    };
    const clearWorking = () => {
        workingRow?.remove();
        workingRow = null;
    };
    const setThinking = active => {
        widget.classList.toggle('is-thinking', active);
        if (active) showWorking();
        else clearWorking();
        if (sendButton) {
            sendButton.setAttribute('aria-label', active ? 'Stop response' : 'Send message');
            sendButton.innerHTML = active ? '<span class="agent-orbit agent-orbit--button" aria-hidden="true"><i></i></span>' : '<span aria-hidden="true">↑</span>';
        }
    };
    const ask = async (message, echo = true) => {
        if (echo) add(message, 'user');
        controller = new AbortController();
        setThinking(true);
        try {
            const body = new URLSearchParams({ message });
            const response = await fetch('/support/chat', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body, signal: controller.signal });
            const data = await response.json();
            add(data.answer || 'Support is available. Please try again.', 'agent', data.links || []);
        } catch (error) {
            if (error.name !== 'AbortError') add('Support chat could not connect. Please use the Contact page or try again.', 'agent', [{ label: 'Contact Team', url: '/contact' }]);
        } finally {
            controller = null;
            setThinking(false);
            form?.elements.message?.focus();
        }
    };
    document.querySelectorAll('[data-support-open]').forEach(button => {
        button.addEventListener('click', () => widget.classList.contains('is-open') ? close() : open());
    });
    widget.querySelector('[data-support-close]')?.addEventListener('click', close);
    form?.elements.message?.addEventListener('keydown', event => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });
    sendButton?.addEventListener('click', event => {
        if (controller) {
            event.preventDefault();
            controller.abort();
            controller = null;
            setThinking(false);
        }
    });
    form?.addEventListener('submit', async event => {
        event.preventDefault();
        const input = form.elements.message;
        const message = input.value.trim();
        if (!message) return;
        input.value = '';
        ask(message);
    });
})();
</script>
