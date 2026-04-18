// resources/js/firebase.js
import { initializeApp } from "firebase/app";
import { getFirestore } from "firebase/firestore";
import { getAuth } from "firebase/auth";

// Configuración de Firebase (Extraída de la consola de Firebase del usuario)
const firebaseConfig = {
  apiKey: "AIzaSyC3_c0n242ffdr2s4vF9H9xEVgs8WD83k4",
  authDomain: "arrendaoco-fad79.firebaseapp.com",
  projectId: "arrendaoco-fad79",
  storageBucket: "arrendaoco-fad79.firebasestorage.app",
  messagingSenderId: "32992727938",
  appId: "1:32992727938:web:22344c7c04f5087d9e359b",
  measurementId: "G-6E53RMRJ56"
};

// Inicializar Firebase
const app = initializeApp(firebaseConfig);

// Inicializar servicios
export const db = getFirestore(app);
export const auth = getAuth(app);

export default app;
