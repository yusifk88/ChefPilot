import {createStore} from 'framework7/lite';
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {Capacitor} from "@capacitor/core";
import OneSignal from "onesignal-cordova-plugin";

const store = createStore({
    state: {
        selectedRecipe: null,
        user: null,
        showLogin: true,
        loginState: true,
        refresh: false,
        bookmarkChanged: false,
        initializingAccount: false,
        unreadNotificationsCount: 0,
        notifications: {
            all: [],
            unread: []
        }
    },
    getters: {
        products({state}) {
            return state.products;
        },
        loginState({state}) {
            return state.loginState;
        },
        selectedItem({state}) {
            return state.selectedRecipe;
        },
        getUser({state}) {
            return state.user;
        },
        getRefresh({state}) {
            return state.refresh;
        },
        bookMarkState({state}) {
            return state.bookmarkChanged;
        },
        getInitState({state}) {
            return state.initializingAccount;
        },
        getUnreadNotificationsCount({state}) {

            return state.unreadNotificationsCount;
        },
        getNotifications({state}) {
            return state.notifications;
        }

    },
    actions: {

        hideLogin({state}) {
            state.loginState = false;
        },

        showLogin({state}) {
            state.loginState = true;
        },

        changeBookmarkState({state}) {
            state.bookmarkChanged = !state.bookmarkChanged;
        },

        changeRefreshState({state}) {
            state.refresh = !state.refresh;
        },

        setRecipeItem({state}, item) {
            state.selectedRecipe = item;
        },

        setUser({state}, user) {

            state.user = user;
        },

        initUser({state}) {

            state.initializingAccount = true;

            if (Capacitor.getPlatform().toLowerCase() === 'web') {

                axios.get("/user")
                    .then(res => {
                        state.user = res.data.data.user;
                        state.loginState = false
                        state.refresh = !state.refresh;
                        state.initializingAccount = false;

                        //get number of unread notifications

                        axios.get("/notifications/count")
                            .then(res => {
                                state.unreadNotificationsCount = res.data.data.unread;
                            })

                        //get all and unread notifications
                        axios.get("/notifications")
                            .then(res => {
                                state.notifications = res.data.data;
                            })


                    })
                    .catch(error => {
                        state.user = null;
                        state.loginState = true;
                        state.initializingAccount = false;


                    })


            } else {

                state.initializingAccount = true;

                CapacitorPersistentAccount.readAccount()
                    .then(account => {
                        if (account.data) {

                            axios.get("/user", {headers: {Authorization: "Bearer " + account.data.token}})
                                .then(res => {
                                    state.user = res.data.data.user;
                                    state.loginState = false
                                    state.refresh = !state.refresh;
                                    state.initializingAccount = false;

                                    OneSignal.login(state.user.id.toString());


                                })
                                .catch(async error => {
                                    state.user = null;
                                    state.loginState = true;
                                    state.initializingAccount = false;
                                    // await CapacitorPersistentAccount.saveAccount({data:null});
                                    //  window.location.reload();

                                })

                        } else {

                            state.loginState = true;


                        }

                    })
            }


        },
        addProduct({state}, product) {
            state.products = [...state.products, product];
        },
    },

})
export default store;
