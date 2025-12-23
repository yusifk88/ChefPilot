import OneSignal from 'onesignal-cordova-plugin';

const ONESIGNAL_APP_ID = 'YOUR_ONESIGNAL_APP_ID';

export const initOneSignal = () => {
    if (!window.Capacitor?.isNativePlatform()) return;

    OneSignal.setAppId(ONESIGNAL_APP_ID);

    // Request permission (iOS + Android 13+)
    OneSignal.promptForPushNotificationsWithUserResponse((accepted) => {
        console.log('Push permission accepted:', accepted);
    });

    // Foreground notification handler
    OneSignal.setNotificationWillShowInForegroundHandler(event => {
        const notification = event.getNotification();
        console.log('Foreground notification:', notification);
        event.complete(notification);
    });

    // Notification click handler
    OneSignal.setNotificationOpenedHandler(notification => {
        console.log('Notification opened:', notification);

        const data = notification.notification.additionalData;
        if (data?.route) {
            window.location.href = data.route;
        }
    });

    // Debug device ID
    OneSignal.getDeviceState(state => {
        console.log('OneSignal User ID:', state?.userId);
    });
};
