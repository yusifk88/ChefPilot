<template>
  <f7-page>
    <f7-navbar @click:back="backClick" title="Profile" back-link></f7-navbar>

    <f7-block inset strong v-if="user">

      <img :src="user.image_url" style="border-radius: 15px; width: 120px">
      <div class="grid grid-cols-3 grid-gap">
        <f7-button
            preloader
            :loading="loadingAvatar"
            :disabled="loadingAvatar" @click="triggerSelect">Change
        </f7-button>

      </div>

    </f7-block>

    <f7-block v-if="user" inset strong>
      <f7-list inset-ios media-list dividers strong>
        <f7-list-item
            :title="user.bio ?? 'You have no bio'"
            subtitle="Bio"
        >
        </f7-list-item>
        <f7-list-item
            :title="user.name"
            subtitle="Name"
        >
        </f7-list-item>

        <f7-list-item
            subtitle="Location"
        >
          <template #title>
            <FlagIcon v-if="user.country" :code="user.country" :title="user.country"/>
            <span v-else>Not Available</span>
          </template>

        </f7-list-item>

        <f7-list-item
            :title="user.email"
            subtitle="Email"
            disabled=""
        >
        </f7-list-item>

        <f7-list-item
            :title="formatDateTIme(user.created_at)"
            subtitle="Joined At"
        >
        </f7-list-item>


      </f7-list>

      <div class="grid grid-cols-3 grid-gap">
        <f7-button @click="setUser" sheet-open=".update-profile-sheet">Update</f7-button>
      </div>
    </f7-block>

    <f7-button sheet-open=".delete-sheet" color="red">Delete Account</f7-button>

    <f7-sheet
        class="delete-sheet"
        style="height: auto"
        swipe-to-close
        backdrop>
      <div class="swipe-handler"></div>
      <f7-content>
        <f7-block strong-ios dividers-ios inset-ios>

          <div
              style="background-color: rgba(255,0,0,0.13); padding: 10px; color: red; border: 1px solid red; border-radius: 10px">
            <strong>Danger</strong>
            <p>
              You are about to delete your user account permanently. All your posts, recipes, food pantry and
              preferences will be deleted permanently.
            </p>

            <span v-if="!codeSent">
            <f7-button preloader color="red" @click="sendOTPCode" :loading="sendingCode">Delete Account</f7-button>
            <f7-button sheet-close=".delete-sheet" color="green" class="margin-top" fill>Cancel</f7-button>
            </span>

          </div>

          <span v-if="codeSent">
          <p>
            We have sent a verification code to your email, enter the code here to continue.
          </p>
          <f7-list class="no-padding no-margin">
            <label for="codeInput" class="margin-left">Verification Code</label>
            <f7-list-input
                class="no-margin no-padding"
                id="codeInput"
                type="text"
                outline
                auto-focus
                :value="code"
                @input="v=>code=v.target.value"
                placeholder="Enter the verification code here"
            />
          </f7-list>

            <f7-button :disabled="!code" preloader :loading="deletingAccount" color="red" @click="deleteAccount"
                       fill>Delete Account</f7-button>
            <f7-button fill class="margin-top" @click="codeSent=false">Back</f7-button>
          </span>

        </f7-block>
      </f7-content>

    </f7-sheet>

    <f7-sheet class="update-profile-sheet" style="height: auto" swipe-to-close backdrop>
      <div class="swipe-handler"></div>

      <f7-page-content>
        <f7-block-title class="padding-left">Update your profile</f7-block-title>
        <f7-block>
          <f7-list strong-ios dividers-ios inset-ios>

            <f7-list-input
                label="Name"
                type="text"
                placeholder="Your name"
                required
                outline
                :value="name"
                @input="nameChanged"
            ></f7-list-input>

            <f7-list-input
                outline
                :value="bio"
                label="Bio"
                type="textarea"
                placeholder="Your short bio"
                required
                autofocus
                @input="bioChanged"
                clear-button></f7-list-input>

          </f7-list>
          <f7-button @click="savedChanges" preloader :loading="loading" :disabled="loading"
                     class="margin-right margin-left" fill>Update
          </f7-button>
        </f7-block>
      </f7-page-content>

    </f7-sheet>

  </f7-page>
</template>

<script>
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";
import {formatDateTIme} from "@/js/utility";
import 'vue3-flag-icons/styles'
import FlagIcon from 'vue3-flag-icons'
import {FileCompressor} from "@capgo/capacitor-file-compressor";
import {Camera} from '@capacitor/camera';
import {Filesystem} from '@capacitor/filesystem';
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {db} from "@/js/db";

export default {
  name: "profile",
  components: {
    FlagIcon
  },
  data() {
    return {
      user: useStore(store, "getUser"),
      name: null,
      bio: null,
      loading: false,
      selectedPhoto: null,
      loadingAvatar: false,
      sendingCode: false,
      codeSent: false,
      deletingAccount: false,
      code: ""
    }
  },
  methods: {
    formatDateTIme,

    deleteAccount() {
      const URL = "/delete-account";
      this.deletingAccount = true;

      axios.post(URL, {
        code: this.code
      })
          .then(async res => {

            await CapacitorPersistentAccount.saveAccount({data: null});
            store.dispatch("showLogin");
            window.location.reload();

            db.delete();

            this.deletingAccount = false;

          })
          .catch(error => {
            this.deletingAccount = false;
          })
    },
    sendOTPCode() {
      this.sendingCode = true;
      axios.get("request-code")
          .then(res => {
            this.sendingCode = false;
            this.codeSent = true;
          })
          .catch(error => {
            this.sendingCode = false;
          })

    },
    async triggerSelect() {


      const photo = await Camera.getPhoto({
        quality: 100,
        allowEditing: false,
        resultType: 'uri'
      });

      const result = await FileCompressor.compressImage({
        path: photo.path,
        quality: 0.8,
        width: 720,
        mimeType: 'image/jpeg'
      });

      const readFile = await Filesystem.readFile({
        path: result.path
      });

      const base64Data = readFile.data;

      this.loadingAvatar = true;

      axios.post("/change-avatar", {
        avatar: base64Data
      })
          .then(async res => {

            let currentUser = await CapacitorPersistentAccount.readAccount();

            await CapacitorPersistentAccount.saveAccount({data: null});

            await CapacitorPersistentAccount.saveAccount({data: {user: res.data.data, token: currentUser.data.token}});

            store.dispatch("setUser", res.data.data);

            this.loadingAvatar = false;
            store.dispatch("initUser");

          })
          .catch(error => {
            this.loadingAvatar = false;

          })
    },
    backClick() {

      store.dispatch("setMainPanelEffect", 'push')

    },
    bioChanged(e) {
      this.bio = e.target.value;
      console.log(e.target.value);
    },
    nameChanged(e) {
      this.name = e.target.value;
    },
    setUser() {
      this.name = this.user.name;
      this.bio = this.user.bio;
    },
    savedChanges() {
      const payload = {
        name: this.name,
        bio: this.bio
      };

      this.loading = true;
      axios.post("/update-user", payload)
          .then(async res => {

            let currentUser = await CapacitorPersistentAccount.readAccount();

            await CapacitorPersistentAccount.saveAccount({data: null});

            await CapacitorPersistentAccount.saveAccount({data: {user: res.data.data, token: currentUser.data.token}});

            this.loading = false;
            const successToast = f7.toast.create({
              text: 'User profile updated',
              closeButton: true,
            });

            successToast.open();
            store.dispatch("initUser")

          })
          .catch(error => {
            this.loading = false;
          })
    }
  }

}
</script>

<style scoped>

</style>