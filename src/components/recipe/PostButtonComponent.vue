<template>
  <f7-icon @click="sheetOpened=true" size="20" f7="arrow_2_squarepath" style="margin-left: auto !important;">
  </f7-icon>
  <f7-sheet v-model:opened="sheetOpened" style="height: 95%">
    <post-card :item="item" @closed="sheetOpened=false"></post-card>
  </f7-sheet>
</template>

<script>
import PostCard from "@/components/social/PostCard.vue";

export default {
  components: {PostCard},
  props: {
    item: {
      type: Object
    }
  },
  name: "PostButtonComponent",
  data() {
    return {
      sheetOpened: false

    }
  }
}
</script>

<style scoped>

</style>