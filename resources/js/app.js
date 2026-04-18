import './bootstrap';
import { listenToMessages, sendFirebaseMessage, listenToAllChats } from './firebase_chat_bridge';

// Exponer a global para uso en Blade si es necesario
window.FirebaseChat = {
    listenToMessages,
    sendFirebaseMessage,
    listenToAllChats
};
