<!-- Chatbot Widget -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">
    
    <!-- Ventana del chat -->
    <div id="chatbot-window" class="hidden flex-col w-80 h-96 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-amber-700 text-white px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center text-sm font-bold">☕</div>
                <div>
                    <p class="font-semibold text-sm">Coffee Not Found</p>
                    <p class="text-xs text-amber-200">Asistente virtual</p>
                </div>
            </div>
            <button onclick="toggleChatbot()" class="text-white hover:text-amber-200 text-xl leading-none">&times;</button>
        </div>

        <!-- Messages -->
        <div id="chatbot-messages" class="flex-1 overflow-y-auto p-3 space-y-2 bg-gray-50">
            <div class="flex gap-2">
                <div class="w-6 h-6 bg-amber-700 rounded-full flex items-center justify-center text-xs text-white flex-shrink-0 mt-1">☕</div>
                <div class="bg-white rounded-2xl rounded-tl-sm px-3 py-2 text-sm text-gray-700 shadow-sm max-w-xs">
                    ¡Hola! 👋 Soy el asistente de <strong>Coffee Not Found</strong>. ¿En qué puedo ayudarte?
                    <div class="flex flex-wrap gap-1 mt-2">
                        <button onclick="sendQuickReply('¿Cuál es el menú?')" class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full hover:bg-amber-200">📋 Ver menú</button>
                        <button onclick="sendQuickReply('¿Cómo hago un pedido?')" class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full hover:bg-amber-200">🛒 Hacer pedido</button>
                        <button onclick="sendQuickReply('¿Cuáles son los horarios?')" class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full hover:bg-amber-200">🕐 Horarios</button>
                        <button onclick="sendQuickReply('¿Cómo pago?')" class="text-xs bg-amber-100 text-amber-800 px-2 py-1 rounded-full hover:bg-amber-200">💳 Pagos</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="px-3 py-2 border-t bg-white flex gap-2 items-center">
            <input 
                type="text" 
                id="chatbot-input" 
                placeholder="Escribe un mensaje..." 
                class="flex-1 text-sm border border-gray-300 rounded-full px-3 py-2 focus:outline-none focus:border-amber-500"
                onkeypress="if(event.key==='Enter') sendMessage()"
            >
            <button onclick="sendMessage()" class="bg-amber-700 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-amber-800 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Botón flotante -->
    <button onclick="toggleChatbot()" id="chatbot-btn" class="w-14 h-14 bg-amber-700 hover:bg-amber-800 text-white rounded-full shadow-lg flex items-center justify-center text-2xl transition-all duration-300 hover:scale-110">
        <span id="chatbot-icon">☕</span>
    </button>
</div>

<script>
const responses = {
    menu: {
        keywords: ['menú', 'menu', 'carta', 'productos', 'comida', 'qué hay', 'que hay', 'qué tienen', 'que tienen'],
        answer: '📋 Tenemos varias categorías en nuestro menú:\n\n🍳 <b>Desayunos</b> - desde Bs. 15<br>🍽️ <b>Almuerzos</b> - desde Bs. 28<br>☕ <b>Bebidas</b> - desde Bs. 6<br>🥪 <b>Snacks</b> - desde Bs. 18<br>🍰 <b>Postres</b> - desde Bs. 10<br><br>👉 <a href="/menu" class="text-amber-600 underline font-semibold">Ver menú completo</a>'
    },
    pedido: {
        keywords: ['pedido', 'pedir', 'ordenar', 'comprar', 'cómo hago', 'como hago', 'solicitar'],
        answer: '🛒 Para hacer un pedido es muy fácil:<br><br>1️⃣ Ve al <a href="/menu" class="text-amber-600 underline">menú</a><br>2️⃣ Agrega productos al carrito<br>3️⃣ Ve al <a href="/carrito" class="text-amber-600 underline">carrito</a><br>4️⃣ Elige tu método de pago<br>5️⃣ ¡Listo! Tu pedido será preparado 🎉'
    },
    horario: {
        keywords: ['horario', 'hora', 'abierto', 'cierra', 'abre', 'cuando', 'cuándo'],
        answer: '🕐 Nuestros horarios de atención:<br><br>📅 <b>Lunes a Viernes:</b> 7:30 AM - 6:00 PM<br>📅 <b>Sábados:</b> 8:00 AM - 2:00 PM<br>📅 <b>Domingos:</b> Cerrado 😴'
    },
    pago: {
        keywords: ['pago', 'pagar', 'precio', 'costo', 'método', 'metodo', 'qr', 'stripe', 'tarjeta', 'efectivo'],
        answer: '💳 Aceptamos los siguientes métodos de pago:<br><br>📱 <b>Pago QR</b> - Sube tu comprobante<br>💳 <b>Tarjeta</b> - Visa/Mastercard vía Stripe<br><br>El pago se verifica antes de confirmar tu pedido ✅'
    },
    estado: {
        keywords: ['estado', 'mi pedido', 'rastrear', 'seguimiento', 'dónde', 'donde', 'listo', 'cuánto tarda'],
        answer: '📦 Para ver el estado de tu pedido:<br><br>👉 Ve a <a href="/pedidos" class="text-amber-600 underline">Mis Pedidos</a><br><br>Los estados son:<br>⏳ Pendiente → ✅ Confirmado → 👨‍🍳 Preparando → 🔔 Listo → ✔️ Completado'
    },
    contacto: {
        keywords: ['contacto', 'teléfono', 'telefono', 'llamar', 'whatsapp', 'hablar', 'persona'],
        answer: '📞 Puedes contactarnos por:<br><br>💬 <b>WhatsApp:</b> <a href="https://wa.me/59167177161" target="_blank" class="text-green-600 underline">+591 67177161</a><br>📧 <b>Email:</b> info@coffeenotfound.edu.bo<br><br>¡Estamos para ayudarte! 😊'
    },
    vegano: {
        keywords: ['vegetariano', 'vegano', 'sin carne', 'saludable', 'dieta'],
        answer: '🌱 ¡Sí tenemos opciones! Contamos con:<br><br>🥗 <b>Plato Vegetariano</b> - Quinoa orgánica, verduras y aguacate (Bs. 28)<br><br>Está marcado con 🌱 en el menú. <a href="/menu" class="text-amber-600 underline">Ver menú</a>'
    },
    default: '🤔 No estoy seguro de cómo ayudarte con eso. Puedes:\n\n👉 Llamarnos al <a href="https://wa.me/59167177161" target="_blank" class="text-green-600 underline">+591 67177161</a><br>👉 Ver nuestro <a href="/menu" class="text-amber-600 underline">menú</a><br>👉 Revisar tus <a href="/pedidos" class="text-amber-600 underline">pedidos</a>'
};

function toggleChatbot() {
    const window_ = document.getElementById('chatbot-window');
    const icon = document.getElementById('chatbot-icon');
    if (window_.classList.contains('hidden')) {
        window_.classList.remove('hidden');
        window_.classList.add('flex');
        icon.textContent = '✕';
    } else {
        window_.classList.add('hidden');
        window_.classList.remove('flex');
        icon.textContent = '☕';
    }
}

function addMessage(text, isUser = false) {
    const messages = document.getElementById('chatbot-messages');
    const div = document.createElement('div');
    div.className = `flex gap-2 ${isUser ? 'justify-end' : ''}`;
    
    if (isUser) {
        div.innerHTML = `<div class="bg-amber-700 text-white rounded-2xl rounded-tr-sm px-3 py-2 text-sm max-w-xs">${text}</div>`;
    } else {
        div.innerHTML = `
            <div class="w-6 h-6 bg-amber-700 rounded-full flex items-center justify-center text-xs text-white flex-shrink-0 mt-1">☕</div>
            <div class="bg-white rounded-2xl rounded-tl-sm px-3 py-2 text-sm text-gray-700 shadow-sm max-w-xs">${text}</div>
        `;
    }
    
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
}

function getBotResponse(message) {
    const lower = message.toLowerCase();
    for (const [key, data] of Object.entries(responses)) {
        if (key === 'default') continue;
        if (data.keywords.some(k => lower.includes(k))) {
            return data.answer;
        }
    }
    return responses.default;
}

function sendMessage() {
    const input = document.getElementById('chatbot-input');
    const text = input.value.trim();
    if (!text) return;
    
    addMessage(text, true);
    input.value = '';
    
    setTimeout(() => {
        const response = getBotResponse(text);
        addMessage(response);
    }, 500);
}

function sendQuickReply(text) {
    addMessage(text, true);
    setTimeout(() => {
        const response = getBotResponse(text);
        addMessage(response);
    }, 500);
}
</script>