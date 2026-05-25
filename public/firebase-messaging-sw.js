importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

const firebaseConfig = {
  apiKey: "AIzaSyDpSvrUMSMq-uSgH0Fm_WltTvHpWzy4w_A",
  authDomain: "kelolakos-9192e.firebaseapp.com",
  projectId: "kelolakos-9192e",
  storageBucket: "kelolakos-9192e.firebasestorage.app",
  messagingSenderId: "54537472126",
  appId: "1:54537472126:web:970a734ffd5e9b00bfb02f",
  measurementId: "G-DJD4WS5QQY"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);

  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/logo-aviako.png' // Optional: path to icon
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
