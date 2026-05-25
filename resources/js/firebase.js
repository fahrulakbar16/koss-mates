import { initializeApp } from "firebase/app";
import { getAuth, GoogleAuthProvider } from "firebase/auth";

const firebaseConfig = {
  apiKey: "AIzaSyDpSvrUMSMq-uSgH0Fm_WltTvHpWzy4w_A",
  authDomain: "kelolakos-9192e.firebaseapp.com",
  projectId: "kelolakos-9192e",
  storageBucket: "kelolakos-9192e.firebasestorage.app",
  messagingSenderId: "54537472126",
  appId: "1:54537472126:web:970a734ffd5e9b00bfb02f",
  measurementId: "G-DJD4WS5QQY"
};

import { getMessaging } from "firebase/messaging";

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const messaging = getMessaging(app);
const googleProvider = new GoogleAuthProvider();

export { auth, googleProvider, messaging };
