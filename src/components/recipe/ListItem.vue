<template>

  <f7-list-item
      :link="`/recipe/${item.id}`"
      :title="item.name"
      :subtitle="item.difficulty + ' . ETA:'+item.estimatedTimeMinutes+'Min'"
      :text="item.description"
      @click="setItem"
  >
    <template #media>
      <img
          style="border-radius: 8px"
          src="https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png"
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
import {timeFromNow} from "@/js/utility";

export default {
  props: {
    item: null
  },
  name: "ListItem",
  methods:{
    timeFromNow,
    setItem(){
      store.dispatch("setRecipeItem",this.item)
    },
  }
}
</script>

<style scoped>

</style>
