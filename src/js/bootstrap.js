import axios from "axios";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {Capacitor} from "@capacitor/core";
import store from "@/js/store";
import {f7, useStore} from "framework7-vue";
import {BASE_URL} from "@/js/utility";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import * as Ably from 'ably';


let TOKEN = null;

if (Capacitor.getPlatform().toLowerCase() === 'web') {

    TOKEN = localStorage.getItem("token");
} else {

    CapacitorPersistentAccount.readAccount()
        .then(account => {

            if (account.data) {
                TOKEN = account.data.token;

            }
        })

}


window.ablyClient = new Ably.Realtime({

    authCallback: async (_params, callback) => {
        try {
            const res = await fetch(`${BASE_URL}/api/ably/token`, {
                headers: {
                    Authorization: `Bearer ${TOKEN}`,
                },
            });

            callback(null, await res.json());

        } catch (err) {
            callback(err, null);
        }
    },
});



if (Capacitor.getPlatform().toLowerCase() === 'web') {

    axios.defaults.headers.common['Authorization'] = "Bearer " + TOKEN

} else {

        CapacitorPersistentAccount.readAccount()
            .then(account => {

                if (account.data) {
                    axios.defaults.headers.common['Authorization'] = "Bearer " + TOKEN;

                }
            })



}

axios.defaults.baseURL = BASE_URL + '/api';
axios.defaults.headers.common['Accept'] = "application/json";
axios.defaults.headers.post['Content-Type'] = "application/json";

window.axios = axios;

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            console.error("API Error:", error.response.status);

            if (error.response.status === 401) {
                // logout user or refresh token
                console.warn("Unauthorized – redirecting to login");

                store.dispatch("showLogin")
                store.dispatch("endAccountInitState")

            } else if (error.response.status === 422) {

                console.log(error.response.data);

                const errorToast = f7.toast.create({
                    text: error.response.data.message,
                    closeButton: true,
                    cssClass: "toast-red",
                    closeButtonColor: 'white',
                });

                errorToast.open()


            } else {


                const errorToast = f7.toast.create({
                    text: "Something went wrong, please pull to refresh or try again later",
                    closeButton: true,
                    cssClass: "toast-red",
                    closeButtonColor: 'white',
                });

                errorToast.open()

            }
        } else {
            console.error("Network error:", error.message);
        }

        return Promise.reject(error);
    }
);