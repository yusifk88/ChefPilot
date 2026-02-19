<template>
<f7-link class="no-margin">
  <img
      v-if="user" class="message-avatar"
      style="margin-bottom: 7px"
      :src="src ? src : user.image_url"
      onerror="this.onerror=null; this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/public/avatar.webp';"
  >
</f7-link>

</template>

<script>
import {useStore} from "framework7-vue";
import store from "@/js/store";

export default {
  name: "Avatar",
  props:{
    src:{
      type:String,
      default:null
    }
  },
   data() {
    return {
      user: useStore(store,"getUser")
    }
  }
}
</script>

<style scoped>

</style>