import axios from "axios";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {Capacitor} from "@capacitor/core";
import store from "@/js/store";
import {f7} from "framework7-vue";


if (Capacitor.getPlatform().toLowerCase()==='web') {

    axios.defaults.headers.common['Authorization'] = "Bearer " + localStorage.getItem("token")

}else {
    CapacitorPersistentAccount.readAccount()
        .then(account => {

            if (account.data) {
                axios.defaults.headers.common['Authorization'] = "Bearer " + account.data.token;

            }
        })

}

axios.defaults.baseURL = 'https://cpapi.flobaze.com/api';
axios.defaults.headers.common['Accept'] = "application/json";
axios.defaults.headers.post['Content-Type']="application/json";

window.axios=axios;

axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // ❌ Handle errors globally
        if (error.response) {
            console.error("API Error:", error.response.status);

            if (error.response.status === 401) {
                // logout user or refresh token
                console.warn("Unauthorized – redirecting to login");

                store.dispatch("showLogin")
            }else if (error.response.status === 422) {

                console.log(error.response.data);

               const errorToast = f7.toast.create({
                    text: error.response.data.message,
                    closeButton: true,
                   cssClass:"toast-red",
                   closeButtonColor: 'white',
                });

               errorToast.open()


            }else {


                const errorToast = f7.toast.create({
                    text: "Something went wrong, please pull to refresh or try again later",
                    closeButton: true,
                    cssClass:"toast-red",
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