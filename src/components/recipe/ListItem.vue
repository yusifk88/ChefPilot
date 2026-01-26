<template>

  <f7-list-item
      :link="`/recipe/${item.id}`"
      :title="item.name"
      :subtitle="item.difficulty + ' . ETA:'+item.estimatedTimeMinutes+'Min'"
      :text="item.description"
      @click="setItem"
      class="no-padding no-margin"
  >
    <template #media>
      <img
          style="border-radius: 8px"
          :src="item.photos && item.photos.length>0 ? item.photos[0].url : PHOTO_PLACEHOLDER()"
          width="80"
      />
    </template>
    <template #after>
      <f7-icon
          color="blue"
          ios="f7:bookmark_filled"
          md="material:bookmark_filled"
          size="15"
      ></f7-icon>
    </template>
  </f7-list-item>

</template>

<script>
import store from "@/js/store";
import {useStore} from "framework7-vue";
import {PHOTO_PLACEHOLDER, timeFromNow} from "@/js/utility";

export default {
  props: {
    item: null
  },
  name: "ListItem",
  methods:{
    PHOTO_PLACEHOLDER() {
      return PHOTO_PLACEHOLDER
    },
    timeFromNow,
    setItem(){
      store.dispatch("setRecipeItem",this.item)
    },
  }
}
</script>

<style scoped>

</style>
