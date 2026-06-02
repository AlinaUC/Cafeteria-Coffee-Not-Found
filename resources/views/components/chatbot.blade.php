{{-- resources/views/components/chatbot.blade.php --}}

<div id="chatbot-container" style="position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;align-items:flex-end;gap:12px;font-family:sans-serif;">

    <!-- Ventana del chat -->
    <div id="chatbot-window" style="display:none;flex-direction:column;width:320px;height:430px;background:#fff;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,0.15);border:1px solid #e5e7eb;overflow:hidden;">

        <!-- Header -->
        <div style="background:#92400e;color:#fff;padding:12px 16px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <img src="{{ asset('images/ChatBot.png') }}" alt="ChatBot" style="width:40px;height:40px;object-fit:contain;">
                <div>
                    <div style="font-size:13px;font-weight:600;">Coffee Not Found</div>
                    <div style="font-size:11px;opacity:0.85;">Asistente virtual ✦ IA</div>
                </div>
            </div>
            <button onclick="cbToggle()" style="background:none;border:none;color:#fff;font-size:22px;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <!-- Mensajes -->
        <div id="chatbot-messages" style="flex:1;overflow-y:auto;padding:12px;display:flex;flex-direction:column;gap:8px;background:#f9fafb;">
            <div style="display:flex;gap:8px;align-items:flex-start;">
                <img src="{{ asset('images/ChatBot.png') }}" alt="ChatBot" style="width: 40px;height: 40px;object-fit:contain;">
                <div style="background:#fff;border-radius:12px;border-top-left-radius:2px;padding:10px 12px;font-size:13px;color:#374151;max-width:230px;line-height:1.5;border:1px solid #e5e7eb;">
                    ¡Hola! 👋 Soy el asistente de <strong>Coffee Not Found</strong>. ¿En qué te puedo ayudar?
                    <div style="margin-top:8px;display:flex;flex-wrap:wrap;gap:4px;">
                        <span onclick="cbSendQuick('¿Cuál es el menú?')" style="font-size:11px;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;border-radius:20px;padding:4px 9px;cursor:pointer;">📋 Menú</span>
                        <span onclick="cbSendQuick('¿Cómo hago un pedido?')" style="font-size:11px;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;border-radius:20px;padding:4px 9px;cursor:pointer;">🛒 Pedido</span>
                        <span onclick="cbSendQuick('¿Cuáles son los horarios?')" style="font-size:11px;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;border-radius:20px;padding:4px 9px;cursor:pointer;">🕐 Horarios</span>
                        <span onclick="cbSendQuick('¿Cómo puedo pagar?')" style="font-size:11px;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;border-radius:20px;padding:4px 9px;cursor:pointer;">💳 Pagos</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div style="padding:10px 12px;border-top:1px solid #e5e7eb;background:#fff;display:flex;gap:8px;align-items:center;">
            <input
                type="text"
                id="chatbot-input"
                placeholder="Escribe un mensaje..."
                style="flex:1;font-size:13px;border:1px solid #d1d5db;border-radius:20px;padding:7px 14px;outline:none;background:#f9fafb;color:#111;"
                onkeydown="if(event.key==='Enter') cbSend()"
                onfocus="this.style.borderColor='#b45309'"
                onblur="this.style.borderColor='#d1d5db'"
            >
            <button onclick="cbSend()" style="width:34px;height:34px;background:#92400e;border:none;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                    <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Botón flotante -->
    <button onclick="cbToggle()" id="chatbot-btn"
        style="width:52px;height:52px;background:#92400e;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,0.2);transition:transform 0.2s ease;"
        onmouseover="this.style.transform='scale(1.1)'"
        onmouseout="this.style.transform='scale(1)'">

        <img id="chatbot-icon"
            src="{{ asset('images/ChatBot.png') }}"
            alt="ChatBot"
            style="width:50px;height:50px;object-fit:contain;">
    </button>
</div>

<style>
    @keyframes cbBounce {
        0%,60%,100% { transform: translateY(0); }
        30% { transform: translateY(-5px); }
    }
</style>

<script>
let cbOpen    = false;
let cbWaiting = false;
let cbHistory = [];

function cbToggle() {
    cbOpen = !cbOpen;
    const win  = document.getElementById('chatbot-window');
    win.style.display = cbOpen ? 'flex' : 'none';
}

function cbAddMsg(text, isUser) {
    const msgs = document.getElementById('chatbot-messages');
    const div  = document.createElement('div');

    if (isUser) {
        div.style.cssText = 'display:flex;justify-content:flex-end;';
        div.innerHTML = `<div style="background:#92400e;color:#fff;border-radius:12px;border-top-right-radius:2px;padding:8px 12px;font-size:13px;max-width:230px;line-height:1.5;">${text}</div>`;
    } else {
        div.style.cssText = 'display:flex;gap:8px;align-items:flex-start;';
        div.innerHTML = `
            <img src="{{ asset('images/ChatBot.png') }}"
            alt="ChatBot"
            style="width:40px;height:40px;object-fit:contain;flex-shrink:0;margin-top:2px;">

            <div style="background:#fff;border-radius:12px;border-top-left-radius:2px;padding:10px 12px;font-size:13px;color:#374151;max-width:230px;line-height:1.5;border:1px solid #e5e7eb;">${text}</div>`;
    }

    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
}

function cbShowTyping() {
    const msgs = document.getElementById('chatbot-messages');
    const div  = document.createElement('div');
    div.id = 'cb-typing';
    div.style.cssText = 'display:flex;gap:8px;align-items:flex-start;';
    div.innerHTML = `
        <img src="{{ asset('images/ChatBot.png') }}"
        alt="ChatBot"
        style="width:40px;height:40px;object-fit:contain;flex-shrink:0;margin-top:2px;">
        <div style="background:#fff;border-radius:12px;border-top-left-radius:2px;padding:10px 14px;border:1px solid #e5e7eb;display:flex;gap:5px;align-items:center;">
            <span style="width:7px;height:7px;border-radius:50%;background:#b45309;display:inline-block;animation:cbBounce 1s infinite 0s;"></span>
            <span style="width:7px;height:7px;border-radius:50%;background:#b45309;display:inline-block;animation:cbBounce 1s infinite 0.2s;"></span>
            <span style="width:7px;height:7px;border-radius:50%;background:#b45309;display:inline-block;animation:cbBounce 1s infinite 0.4s;"></span>
        </div>`;
    msgs.appendChild(div);
    msgs.scrollTop = msgs.scrollHeight;
}

function cbRemoveTyping() {
    const el = document.getElementById('cb-typing');
    if (el) el.remove();
}

async function cbAsk(userMsg) {
    cbHistory.push({ role: 'user', content: userMsg });
    cbShowTyping();
    cbWaiting = true;

    try {
        const response = await fetch('/chatbot/mensaje', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ messages: cbHistory })
        });

        const data = await response.json();
        cbRemoveTyping();

        if (data.error) {
            cbAddMsg('⚠️ ' + data.error, false);
        } else {
            cbHistory.push({ role: 'assistant', content: data.reply });
            cbAddMsg(data.reply, false);
        }

    } catch (e) {
        cbRemoveTyping();
        cbAddMsg('❌ Error de conexión. Intenta de nuevo.', false);
    }

    cbWaiting = false;
}

function cbSend() {
    if (cbWaiting) return;
    const input = document.getElementById('chatbot-input');
    const text  = input.value.trim();
    if (!text) return;
    input.value = '';
    cbAddMsg(text, true);
    cbAsk(text);
}

function cbSendQuick(text) {
    if (cbWaiting) return;
    cbAddMsg(text, true);
    cbAsk(text);
}
</script>