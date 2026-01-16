<template>
  <f7-app v-bind="f7params" :store="{store}">

    <!-- Left panel with cover effect-->
    <f7-panel
        swipe-active-area="5px"
        swipe left id="main-panel" :effect="effect" :class="{'panel-fullscreen':effect==='cover'}">
      <f7-view>
        <f7-page>
          <f7-navbar class="no-padding">

            <f7-list class="no-margin" inset-ios v-if="user" media-list dividers-ios strong
                     style="border-radius: 15px !important; width: 100%!important; margin-top: 35px">
              <f7-list-item @click="expand" link="/profile" :title="user.name"
                            :subtitle="user.bio">
                <template #media>
                  <img
                      style="border-radius: 8px"
                      :src="user.image_url"
                      width="44"
                  />
                </template>
              </f7-list-item>
            </f7-list>

          </f7-navbar>

          <f7-block class="no-padding" inset strong>

            <f7-list inset-ios media-list dividers-ios strong style="border-radius: 15px !important;">
              <f7-list-item

                  link
                  title="About"

              >
              </f7-list-item>

              <f7-list-item
                  link
                  title="Terms & Conditions"
              >
              </f7-list-item>

              <f7-list-item
                  link
                  title="How it works"
              >
              </f7-list-item>


              <f7-list-item
                  link
                  title="Share"
              >
              </f7-list-item>


              <f7-list-item
                  link
                  title="Privacy Policy"
              >
              </f7-list-item>


              <f7-list-item
                  link
                  title="Theme"
                  actions-open="#actions-two-groups"
              >
              </f7-list-item>


              <f7-list-item
                  link
                  color="red"

                  @click="showLogOutDialog"
                  panel-close="#main-panel"
              >
                <template #title>
                  <span style="color: red">Log Out</span>
                </template>
              </f7-list-item>

            </f7-list>


          </f7-block>
        </f7-page>
      </f7-view>
    </f7-panel>


    <f7-views tabs class="safe-areas">
      <f7-toolbar tabbar icons bottom>
        <f7-toolbar-pane>
          <f7-link tab-link="#view-home" tab-link-active icon-ios="f7:house-outline" icon-md="f7:house-outline"
                   text="Home"></f7-link>
          <f7-link tab-link="#view-bookmarks" icon-ios="f7:bookmark-outline" icon-md="f7:bookmark-outline"
                   text="Bookmarks"></f7-link>
          <f7-link tab-link="#view-settings" icon-ios="f7:person_2" icon-md="f7:person_2"
                   text="Discover"></f7-link>
        </f7-toolbar-pane>
      </f7-toolbar>

      <f7-view id="view-home" main tab tab-active url="/"></f7-view>


      <f7-view id="view-bookmarks" name="favorites" tab url="/bookmarks/"></f7-view>

      <f7-view id="view-settings" name="settings" tab url="/settings/"></f7-view>

    </f7-views>


    <f7-login-screen v-model:opened="showLogin">
      <login></login>
    </f7-login-screen>

    <f7-actions id="actions-two-groups">
      <f7-actions-group>
        <f7-actions-label>Choose A Theme</f7-actions-label>
        <f7-actions-button :strong="currentTheme==='system'" @click="setDarkTheme('system')">System</f7-actions-button>
        <f7-actions-button :strong="currentTheme==='dark'" @click="setDarkTheme('dark')">Dark</f7-actions-button>
        <f7-actions-button :strong="currentTheme==='light'" @click="setDarkTheme('light')">Light</f7-actions-button>
      </f7-actions-group>
      <f7-actions-group>
        <f7-actions-button color="red">Cancel</f7-actions-button>
      </f7-actions-group>
    </f7-actions>

  </f7-app>
</template>
<script>
import {onMounted, ref} from 'vue';
import {f7, f7ready, useStore} from 'framework7-vue';

import {getDevice} from 'framework7';
import capacitorApp from '../js/capacitor-app.js';
import routes from '../js/routes.js';
import store from '../js/store';
import Login from "@/components/auth/login.vue";
import Profile from "@/pages/profile.vue";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {App} from '@capacitor/app';


import OneSignal from "onesignal-cordova-plugin";

import {Capacitor} from "@capacitor/core";

import {SystemThemeColor} from 'system-theme-color';


export default {
  components: {Profile, Login},


  setup() {

    const sheetOpened = ref(false);
    const sharedUlid = ref(null);

    const showLogin = useStore(store, "loginState");
    const user = useStore(store, "getUser");

    const currentTheme = ref(user.theme);


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
    const effect = ref(useStore(store, 'mainPanelEffect'));

    const alertLoginData = () => {
      f7.dialog.alert('Username: ' + username.value + '<br>Password: ' + password.value, () => {
        f7.loginScreen.close();
      });
    }

    const setDarkTheme = (mode = 'system') => {

      let value = false;

      if (mode === 'system') {

        value = window.matchMedia('(prefers-color-scheme: dark)').matches;
      }

      if (mode === 'dark') {
        value = true;
      }

      f7.setDarkMode(value)

      if (mode) {

        currentTheme.value = mode;

        axios.post("/set-user-theme", {theme: mode})
            .then(res => {
              CapacitorPersistentAccount.readAccount()
                  .then(account => {
                        if (account.data) {

                          const NewData = {
                            user: res.data.data,
                            token: account.data.token
                          };


                          CapacitorPersistentAccount.saveAccount({data: NewData})


                        }
                      }
                  )
            })
      }


    };
    const showLogOutDialog = () => {

      f7.dialog.confirm('Do you want to log out?', async () => {

        f7.dialog.preloader('Please wait..');

        await CapacitorPersistentAccount.saveAccount({data: null});

        f7.dialog.close();

        store.dispatch("showLogin");
        window.location.reload();


      });
    };


    const handleThemeChange = (e) => {

      CapacitorPersistentAccount.readAccount()
          .then(account => {
            if (account.data) {

              const themeMode = account.data.user.theme;
              if (themeMode === 'system') {
                f7.setDarkMode(e.matches)
              }
            }
          })


    }

    const expand = () => {
      store.dispatch("setMainPanelEffect", 'cover')

    };

    const setThemeColor = () => {

      if (Capacitor.getPlatform() === 'android') {

        SystemThemeColor.getMaterialColors()
            .then(colors => {

              f7.setColorTheme(colors.primary);


            })
            .catch(error => {
              console.log(error);
              alert(error);
            })

      }

    };

    onMounted(() => {
      f7ready(() => {

        if (device.capacitor) {
          capacitorApp.init(f7);
        }

        if (Capacitor.getPlatform() === 'android') {

          setThemeColor();


          OneSignal.initialize("104eb6bd-20ec-4d84-a426-b076741fb531");

          OneSignal.Notifications.requestPermission(true).then((success) => {

            console.log("Notification permission status:", success);
          });

          OneSignal.Notifications.addEventListener('click', (event) => {
            //alert('Notification clicked:'+ JSON.stringify(event.notification.additionalData));

            const recipeRoute = "/recipe/" + event.notification.additionalData.recipe_id;

            f7.views.main.router.navigate(recipeRoute);


          });
        }


        const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

        f7.setDarkMode(isDarkMode)


        store.dispatch("initUser");


      });


      App.addListener('appUrlOpen', (event) => {
        const url = event.url;

        const path = url.split('://')[1];

        const pathArr = path.split("/")

        const ulid = pathArr[pathArr.length - 1];

        sharedUlid.value = "/shared-recipe/" + ulid;

        f7.views.main.router.navigate(sharedUlid.value);


      });

      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

      mediaQuery.addEventListener('change', handleThemeChange);


      /**
       * apply user set dark theme mode
       * @type {string}
       */

      CapacitorPersistentAccount.readAccount()
          .then(account => {
                if (account.data) {

                  const themeMode = account.data.user.theme;

                  setDarkTheme(themeMode);


                }
              }
          )


    });

    return {
      f7params,
      username,
      password,
      showLogin,
      user,
      sheetOpened,
      currentTheme,
      effect,
      currentTheme,
      expand,
      alertLoginData,
      showLogOutDialog,
      setDarkTheme,
      setThemeColor,
      handleThemeChange,
      store
    }
  }
}
</script>
<style>
.toast-red {
  background-color: red !important;
  color: #FFFFFF !important;
}


.panel-fullscreen {

  width: 95% !important;

}
</style>