<template>

  <div class="grid grid-cols-2 grid-gap no-padding" v-if="!items.length">
    <div>
      <h3>Add your food store</h3>
      <p>Add raw food food stuff to get personalised meal recipes </p>
      <f7-button large @click="sheetOpened=true" fill style="color: #000000!important;">Add Food</f7-button>
    </div>
    <div>
      <img width="100%" src="/img/add_food_image.svg">
    </div>
  </div>

  <f7-block class="no-padding no-margin" v-else @click="showUserItems=true">
    <f7-list media-list dividers-ios class="no-margin">

    <f7-list-item link="#" >
      <template #media>
        <img
            src="/img/item_samples.svg"
            width="120"
        />
      </template>
      <template #after>
        {{items.length}} Food Items
      </template>
    </f7-list-item>

    </f7-list>

<!--    <f7-chip  v-for="item in items" :key="item.id" :text="item.name" media-bg-color="green">-->
<!--      <template #media>-->
<!--        <f7-icon ios="f7:gift" md="material:gift" />-->
<!--      </template>-->
<!--    </f7-chip>-->


  </f7-block>


  <f7-sheet v-model:opened="sheetOpened" style="height: 90%">
    <f7-toolbar>
      <div class="left"></div>
      Add food items
      <div class="right">
        <f7-link sheet-close><i class="icon icon-close"></i></f7-link>
      </div>
    </f7-toolbar>

    <additem @saved="itemsSaved"></additem>

  </f7-sheet>


  <f7-sheet
      style="height: 70%"
      push
      v-model:opened="showUserItems"  class="user-items-sheet">
    <div class="swipe-handler" style="background-color: transparent"></div>
    <f7-toolbar>
      <div class="left">
        <f7-button @click="showUserItems=false; sheetOpened=true;">Add More</f7-button>
      </div>
      <div class="right">
        <f7-link sheet-close><i class="icon icon-close"></i></f7-link>
      </div>
    </f7-toolbar>

    <user-items :items="items" @item-deleted="i=>items=i">
    </user-items>
  </f7-sheet>
</template>

<script>
import Additem from "@/components/items/additem.vue";
import UserItems from "@/components/items/UserItems.vue";
import {f7} from "framework7-vue";

export default {
  components: {UserItems, Additem},
  props: {
    f7router: Object,
  },
  name: "AddedRecipeCard",
  data() {
    return {
      sheetOpened: false,
      showUserItems:false,
      items: []
    }
  },
  methods: {
    toggleSwipeStep() {
      f7.sheet.stepToggle('.user-items-sheet');
    },
    getItems() {
      axios.get("/user-items")
          .then(res => {
            this.items = res.data.data;
          })
    },
    itemsSaved(items) {
      this.sheetOpened = false;
      this.items = items.data;
    }
  },
  mounted() {
    this.getItems();
  }
}
</script>

<style scoped>

</style>