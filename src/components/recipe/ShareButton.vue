<template>
  <f7-icon
      size="20"
      f7="square_arrow_up"
      style="margin-left: auto"
      class="margin-right"
      @click="openShareSheet"
  >
  </f7-icon>
</template>

<script>
import { Share } from '@capacitor/share';
import {BASE_URL} from "@/js/utility";

export default {
  name: "ShareButton",
  props:{
    item:{
      type:Object
    }
  },
  methods:{
  async  openShareSheet(){

    await Share.share({
      url: BASE_URL+"/res/"+this.item.ulid
    });

    }
  }
}
</script>

<style scoped>

</style>