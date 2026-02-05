<template>
   <img v-if="user" class="message-avatar" :src="user.image_url">
</template>

<script>
import {useStore} from "framework7-vue";
import store from "@/js/store";

export default {
  name: "Avatar",
   data() {
    return {
      user: useStore(store,"getUser")
    }
  }
}
</script>

<style scoped>

</style>