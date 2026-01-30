<template>
  <f7-page>
    <f7-navbar @click:back="backClick" title="Profile" back-link></f7-navbar>

    <f7-block inset strong v-if="user">

      <img :src="user.image_url" style="border-radius: 15px; width: 120px">
      <div class="grid grid-cols-3 grid-gap">
        <f7-button
            preloader
            :loading="loadingAvatar"
            :disabled="loadingAvatar" @click="triggerSelect">Change</f7-button>

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
import { Filesystem } from '@capacitor/filesystem';
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";

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
      loadingAvatar:false
    }
  },
  methods: {
    formatDateTIme,
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

      this.loadingAvatar=true;

      axios.post("/change-avatar", {
        avatar:base64Data
      })
          .then(async res => {

            let currentUser = await CapacitorPersistentAccount.readAccount();

            await  CapacitorPersistentAccount.saveAccount({data:null});

            await  CapacitorPersistentAccount.saveAccount({data:{user:res.data.data,token:currentUser.data.token}});

            store.dispatch("setUser",res.data.data);

            this.loadingAvatar=false;
            store.dispatch("initUser");

          })
          .catch(error=>{
            this.loadingAvatar=false;

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

          await  CapacitorPersistentAccount.saveAccount({data:null});

          await  CapacitorPersistentAccount.saveAccount({data:{user:res.data.data,token:currentUser.data.token}});

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