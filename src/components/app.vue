<template>
  <f7-app v-bind="f7params" :store="{store}">

  <!-- Left panel with cover effect-->
  <f7-panel left cover dark>
    <f7-view>
      <f7-page>
        <f7-navbar title="Left Panel"></f7-navbar>
        <f7-block>Left panel content goes here</f7-block>
      </f7-page>
    </f7-view>
  </f7-panel>


    <f7-views tabs class="safe-areas">
    <f7-toolbar tabbar icons bottom>
      <f7-toolbar-pane>
        <f7-link tab-link="#view-home" tab-link-active icon-ios="f7:house" icon-md="material:home" text="Home"></f7-link>
        <f7-link tab-link="#view-bookmarks" icon-ios="f7:bookmark" icon-md="material:bookmark" text="Bookmarks"></f7-link>
        <f7-link tab-link="#view-settings" icon-ios="f7:square_list" icon-md="material:view_list" text="Explore"></f7-link>
      </f7-toolbar-pane>
    </f7-toolbar>

    <f7-view id="view-home" main tab tab-active url="/"></f7-view>


    <f7-view id="view-bookmarks" name="favorites" tab url="/bookmarks/"></f7-view>

    <f7-view id="view-settings" name="settings" tab url="/settings/"></f7-view>

  </f7-views>


    <f7-login-screen v-model:opened="showLogin">
      <login></login>
    </f7-login-screen>

  </f7-app>
</template>
<script>
  import { ref, onMounted } from 'vue';
  import {f7, f7ready, useStore} from 'framework7-vue';

  import { getDevice }  from 'framework7';
  import capacitorApp from '../js/capacitor-app.js';
  import routes from '../js/routes.js';
  import store from '../js/store';
  import Login from "@/components/auth/login.vue";

  export default {
    components: {Login},


    setup() {


      const showLogin = useStore(store,"loginState");

      const device = getDevice();
      // Framework7 Parameters
      const f7params = {
        name: 'ChefPilot', // App name
        theme: 'auto', // Automatic theme detection
        colors: {
          primary: '#00ff9f',
        },

        // App store
        store: store,
        // App routes
        routes: routes,


        // Input settings
        input: {
          scrollIntoViewOnFocus: device.capacitor,
          scrollIntoViewCentered: device.capacitor,
        },
        // Capacitor Statusbar settings
        statusbar: {
          iosOverlaysWebView: true,
          androidOverlaysWebView: false,
        },
      };
      // Login screen data
      const username = ref('');
      const password = ref('');

      const alertLoginData = () => {
        f7.dialog.alert('Username: ' + username.value + '<br>Password: ' + password.value, () => {
          f7.loginScreen.close();
        });
      }
      onMounted(() => {
        f7ready(() => {

          if (device.capacitor) {
            capacitorApp.init(f7);
          }

          store.dispatch("initUser")


        });
      });

      return {
        f7params,
        username,
        password,
        showLogin,
        alertLoginData,
        store
      }
    }
  }
</script>