<template>
  <f7-page style="background-color: #00ff9f">


    <img width="80%" class="margin fade-in-up" src="/img/chef_cartoon.webp">

    <f7-block class="margin">
      <p class="fade-in-up" style="font-size: 45px; font-weight: bolder; padding: 0; margin: 0">Plan your meals with
        AI.</p>
      <p class="fade-in-up">Let AI plan your next meal with your preferences and the food in your home,
        discover new recipes, learn how to make them and share your experience with others</p>

      <f7-button @click="testLogin" class="fade-in-up" large fill color="black">Continue with &nbsp;
        <app-logo></app-logo>
      </f7-button>
      <f7-button @click="login" large fill color="red" class="margin-top fade-in-up">Continue with &nbsp;<google-logo></google-logo>
      </f7-button>

      <f7-button url="/" large color="black" class="margin-top fade-in-up">Explore</f7-button>

    </f7-block>

  </f7-page>
</template>

<script>
import AppLogo from "@/components/appLogo.vue";
import GoogleLogo from "@/components/googleLogo.vue";
import store from "../../js/store";
import {useStore} from "framework7-vue";
import {SocialLogin} from "@capgo/capacitor-social-login";

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
      email: "okon.lonny@example.org",
      password: "password",
      loading: false
    }
  },
  methods: {
    store() {
      return store
    },

    async login() {

      const response = await SocialLogin.login({
        provider: 'google',
        options: {
          scopes: ['email', 'profile'],
        },
      });

      if (response.result.profile) {


        const payload = response.result.profile;

        axios.post("/signup", payload)
            .then(res => {

              console.log(res.data.data);

              localStorage.setItem("token",res.data.data.token);
              store.dispatch("initUser");

            })

      }else if (response.result.idToken) {

        console.log("results from client:",response.result);

        axios.post("/google-login", {code: response.result.idToken})
            .then(res => {
              console.log("response from server:",res.data.data)
            })
      }




    },

    async initAuth() {
      await SocialLogin.initialize({
        google: {
          webClientId: '51432255365-qpaejpm0gsli0mo76gkbtf4a0apejfhi.apps.googleusercontent.com',
          iOSClientId: '786205750128-6ea445ctatsomsh9qhknl36tmi0ldmdq.apps.googleusercontent.com',        // Required for iOS
          iOSServerClientId: '786205750128-c0tpsm1hgffmjeum8u9ee4kfgclch4jn.apps.googleusercontent.com',  // Required for iOS offline mode and server authorization (same as webClientId)
          mode: 'online',  // 'online' or 'offline'
        }
      })

    },

    testLogin() {

      axios.post("/login", {
        email: this.email,
        password: this.password
      })
          .then(res => {
            localStorage.setItem("token", res.data.data.token);
            store.dispatch("initUser")
          })
    }
  },
  mounted() {

    this.initAuth();

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