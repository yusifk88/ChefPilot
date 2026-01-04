<template>
  <f7-page style="background-color: #00ff9f">


    <img width="80%" class="margin fade-in-up" src="/img/chef_cartoon.webp">

    <f7-block class="margin">
      <p class="fade-in-up" style="font-size: 45px; font-weight: bolder; padding: 0; margin: 0">Plan your meals with
        AI.</p>
      <p class="fade-in-up">Let AI plan your next meal with your preferences and the food in your home,
        discover new recipes, learn how to make them and share your experience with others</p>

      <f7-button v-if="Capacitor().getPlatform().toLowerCase()==='web'" @click="testLogin" class="fade-in-up" large fill
                 color="black">Continue with &nbsp;
        <app-logo></app-logo>
      </f7-button>

      <f7-button
          preloader
          v-if="!currentAccount"
          :loading="loading"
          @click="login"
          large
          fill
          color="red"
          class="margin-top fade-in-up">
        Continue with &nbsp;<google-logo></google-logo>
      </f7-button>


      <f7-button v-if="!currentAccount" url="/" large color="black" class="margin-top fade-in-up">Explore</f7-button>

      <f7-list v-else media-list dividers-ios strong style="border-radius: 15px !important;">
        <f7-list-item @click="setUser" link :title="currentAccount.user.name" :subtitle="currentAccount.user.email">
          <template #media>
            <img
                style="border-radius: 8px"
                :src="currentAccount.user.image_url"
                width="44"
            />
          </template>
        </f7-list-item>
      </f7-list>


    </f7-block>

  </f7-page>
</template>

<script>
import AppLogo from "@/components/appLogo.vue";
import GoogleLogo from "@/components/googleLogo.vue";
import store from "../../js/store";
import {useStore} from "framework7-vue";
import {SocialLogin} from "@capgo/capacitor-social-login";
import {CapacitorPersistentAccount} from '@capgo/capacitor-persistent-account';
import {Capacitor} from "@capacitor/core";

export default {
  name: "login",
  computed: {
    useStore() {
      return useStore
    }
  },
  components: {GoogleLogo, AppLogo},
  data() {
    return {
      email: "test@example.com",
      password: "password",
      loading: false,
      currentAccount: null
    }
  },
  methods: {
    Capacitor() {
      return Capacitor
    },
    store() {
      return store
    },

   async testLogin() {

      if (!this.currentAccount) {
        axios.post("/login", {email: this.email, password: this.password})
            .then(async res => {

              const account = {
                user: res.data.data.user,
                token: res.data.data.token
              };


              await CapacitorPersistentAccount.saveAccount({data: account})

              await store.dispatch("initUser")

            })
      }else {
        await store.dispatch("initUser")
      }
    },

    async login() {

      this.loading = true;


      const OS = Capacitor.getPlatform().toLowerCase();

      if (OS === 'ios') {

        await this.initAuth();


        let response = await SocialLogin.login({
          provider: 'google',
          options: {
            scopes: ['email', 'profile'],
            offline: true
          },
        });

        if (response.result.serverAuthCode) {

          axios.post("/google-login", {code: response.result.serverAuthCode})
              .then(async res => {

                const account = {
                  user: res.data.data.user,
                  token: res.data.data.token
                };


                await CapacitorPersistentAccount.saveAccount({data: account})

                await store.dispatch("initUser")

              })
              .catch(error => {
                console.log(error);
                this.loading = false;
              })
        }


      } else {

        try {


          await this.initAuth("online");

          let response = await SocialLogin.login({
            provider: 'google',
          });

          const profile = response.result.profile;


          const payload = {
            name: profile.name,
            email: profile.email,
            id: profile.id,
            imageUrl: profile.imageUrl
          };


          axios.post("/signup", payload)
              .then(async res => {

                const account = {
                  user: res.data.data.user,
                  token: res.data.data.token
                };


                await CapacitorPersistentAccount.saveAccount({data: account})

                await store.dispatch("initUser")


              })
              .catch(error => {
                console.log(error.response);
                this.loading = false;


              })


        } catch (error) {

          console.log(error);
          this.loading = false;

        }
      }


    },

    async initAuth(mode = 'offline') {
      await SocialLogin.initialize({
        google: {
          webClientId: '51432255365-qpaejpm0gsli0mo76gkbtf4a0apejfhi.apps.googleusercontent.com',
          iOSClientId: '786205750128-6ea445ctatsomsh9qhknl36tmi0ldmdq.apps.googleusercontent.com',        // Required for iOS
          iOSServerClientId: '786205750128-c0tpsm1hgffmjeum8u9ee4kfgclch4jn.apps.googleusercontent.com',  // Required for iOS offline mode and server authorization (same as webClientId)
          mode: mode
        }
      })

    },

    async getPersistentAccount() {

      this.loading = true;

      const account = await CapacitorPersistentAccount.readAccount();
      if (account.data) {
        this.currentAccount = account.data;
      }
      this.loading = false;


    },


    setUser() {

      store.dispatch("setUser", this.currentAccount.user);
      store.dispatch("hideLogin");
      store.dispatch("changeRefreshState");


    }
  },
  mounted() {

    this.getPersistentAccount();

  }
}
</script>


<style scoped>

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Apply the animation */
.fade-in-up {
  animation: fadeInUp 0.6s ease-out forwards;
}

</style>