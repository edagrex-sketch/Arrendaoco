// resources/js/firebase_chat_bridge.js
import { db } from './firebase';
import { 
    collection, 
    addDoc, 
    setDoc, 
    doc, 
    query, 
    orderBy, 
    onSnapshot, 
    serverTimestamp,
    where,
    limit
} from "firebase/firestore";

/**
 * Genera el ID del chat de la misma forma que en la app móvil
 */
export function getChatId(uid1, uid2) {
    const ids = [uid1.toString(), uid2.toString()].sort();
    return ids.join('_');
}

/**
 * Escucha mensajes en tiempo real para un chat específico
 */
export function listenToMessages(uid1, uid2, callback) {
    const chatId = getChatId(uid1, uid2);
    const q = query(
        collection(db, "chats", chatId, "mensajes"),
        orderBy("created_at", "asc")
    );

    return onSnapshot(q, (snapshot) => {
        const messages = [];
        snapshot.forEach((doc) => {
            messages.push({ id: doc.id, ...doc.data() });
        });
        callback(messages);
    });
}

/**
 * Escucha actualizaciones en TODOS los chats de un usuario específico
 */
export function listenToAllChats(userId, callback) {
    const q = query(
        collection(db, "chats"),
        where("usuario_1", "in", [userId.toString()]), // Firebase "in" doesn't work well with OR in some cases, better multiple queries or complex rules
    );

    // En Firestore v9+ podemos usar Filter.or si está disponible, sino hacemos dos suscripciones
    // Para simplificar, escuchamos chats donde participamos
    const q1 = query(collection(db, "chats"), where("usuario_1", "==", userId.toString()));
    const q2 = query(collection(db, "chats"), where("usuario_2", "==", userId.toString()));

    const unsub1 = onSnapshot(q1, (snapshot) => {
        snapshot.docChanges().forEach((change) => {
            if (change.type === "modified") {
                callback(change.doc.data(), change.doc.id);
            }
        });
    });

    const unsub2 = onSnapshot(q2, (snapshot) => {
        snapshot.docChanges().forEach((change) => {
            if (change.type === "modified") {
                callback(change.doc.data(), change.doc.id);
            }
        });
    });

    return () => { unsub1(); unsub2(); };
}

/**
 * Envía un mensaje a Laravel (quien se encargará de MySQL y Firebase)
 */
export async function sendFirebaseMessage(senderId, receiverId, text, extraData = {}) {
    // Nota: El chatId de Laravel es numérico, pero en la vista show.blade.php
    // ya tenemos acceso a la ruta de envío. 
    // Para ser universales, usaremos fetch a la ruta de Laravel.
    
    // El ID del chat de Laravel lo obtenemos de la URL o del contexto
    const chatPath = window.location.pathname;
    const chatIdMatch = chatPath.match(/\/chats\/(\d+)/);
    const laravelChatId = chatIdMatch ? chatIdMatch[1] : null;

    if (!laravelChatId) {
        console.error('No se pudo encontrar el ID del chat de Laravel');
        return;
    }

    const formData = new FormData();
    formData.append('contenido', text);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    const response = await fetch(`/chats/${laravelChatId}/mensaje`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    return await response.json();
}
