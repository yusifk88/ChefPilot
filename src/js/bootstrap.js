import axios from "axios";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {Capacitor} from "@capacitor/core";


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