import {createStore} from 'framework7/lite';
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";

const store = createStore({
    state: {
        selectedRecipe: null,
        user: null,
        showLogin: true,
        loginState: true,
        refresh: false,
        bookmarkChanged: false,
        products: [
            {
                id: '1',
                title: 'Apple iPhone 8',
                description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi tempora similique reiciendis, error nesciunt vero, blanditiis pariatur dolor, minima sed sapiente rerum, dolorem corrupti hic modi praesentium unde saepe perspiciatis.'
            },
            {
                id: '2',
                title: 'Apple iPhone 8 Plus',
                description: 'Velit odit autem modi saepe ratione totam minus, aperiam, labore quia provident temporibus quasi est ut aliquid blanditiis beatae suscipit odio vel! Nostrum porro sunt sint eveniet maiores, dolorem itaque!'
            },
            {
                id: '3',
                title: 'Apple iPhone X',
                description: 'Expedita sequi perferendis quod illum pariatur aliquam, alias laboriosam! Vero blanditiis placeat, mollitia necessitatibus reprehenderit. Labore dolores amet quos, accusamus earum asperiores officiis assumenda optio architecto quia neque, quae eum.'
            },
        ]
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
        }

    },
    actions: {

        hideLogin({state}){
          state.loginState=false;
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


            CapacitorPersistentAccount.readAccount()
                .then(account=>{
                    if (account.data){

                        axios.get("/user",{headers:{Authorization:"Bearer "+account.data.token}})
                            .then(res => {
                                state.user = res.data.data.user;
                                state.loginState = false
                                state.refresh = !state.refresh;

                            })
                            .catch(error => {
                                state.user = null;
                                state.loginState = true;

                                console.log(error);


                            })

                    }else {

                        state.loginState = true;


                    }

                })


        },
        addProduct({state}, product) {
            state.products = [...state.products, product];
        },
    },

})
export default store;
