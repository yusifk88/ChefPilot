<template>

  <span v-if="!loading">
  <div class="grid grid-cols-2 grid-gap no-padding" v-if="items && !items.length">
    <div>
      <h3>Add your food store</h3>
      <p>Add raw food to get personalised meal recipes </p>
      <f7-button large @click="sheetOpened=true; refreshItems=!refreshItems" fill style="color: #000000!important;">Add Food</f7-button>
    </div>
    <div>
      <img width="100%" src="/img/chef_hat_food.png">
    </div>
  </div>

  <f7-block class="no-padding no-margin" v-else @click="showUserItems=true; refreshItems=!refreshItems">
    <f7-list media-list dividers-ios class="no-margin no-padding">

    <f7-list-item link="#" style="padding-left: 0!important; margin-left: 0!important;">
      <template #media>
        <img
            src="/img/item_samples.webp"
            width="120"
        />
      </template>
      <template #after>
        {{ items.length }} Food Items
      </template>
    </f7-list-item>

    </f7-list>


  </f7-block>
  </span>
  <f7-list strong inset dividers-ios media-list class="skeleton-text no-margin"
           v-else>
    <f7-list-item
        title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
        subtitle="Subtitle"
        text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
        class="skeleton-effect-pulse no-margin"
    >
      <template #media>
        <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px" />
      </template>
    </f7-list-item>
  </f7-list>

  <f7-sheet v-model:opened="sheetOpened" style="height: 90%">
    <f7-toolbar>
      <div class="left"></div>
      Add food items
      <div class="right">
        <f7-link sheet-close><i class="icon icon-close"></i></f7-link>
      </div>
    </f7-toolbar>

    <additem :refresh-items="refreshItems" @saved="itemsSaved"></additem>

  </f7-sheet>


  <f7-sheet
      style="height: 70%"
      v-model:opened="showUserItems" class="user-items-sheet">
    <div class="swipe-handler" style="background-color: transparent"></div>
    <f7-toolbar>
      <div class="left">
        <f7-button @click="showUserItems=false; sheetOpened=true;">Add More</f7-button>
      </div>
      <div class="right">
        <f7-link sheet-close><i class="icon icon-close"></i></f7-link>
      </div>
    </f7-toolbar>

    <user-items :items="items">
    </user-items>
  </f7-sheet>
</template>

<script>
import Additem from "@/components/items/additem.vue";
import UserItems from "@/components/items/UserItems.vue";
import {useStore} from "framework7-vue";
import store from "@/js/store";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {useObservable} from "@vueuse/rxjs";
import {liveQuery} from "dexie";
import {db} from "@/js/db";

export default {
  components: {UserItems, Additem},
  props: {
    f7router: Object,
  },
  name: "AddedRecipeCard",
  data() {
    return {
      sheetOpened: false,
      showUserItems: false,
      items: useObservable(liveQuery(() =>  db.userItems.orderBy("name").toArray())),
      shouldRefresh: useStore(store, "getRefresh"),
      loading: false,
      refreshItems:false

    }
  },
  watch: {
    shouldRefresh() {
      this.getItems();
    }
  },
  methods: {


    async getItems() {

      const account = await CapacitorPersistentAccount.readAccount()

      const requestHeaders = account.data ? {headers: {Authorization: "Bearer " + account.data.token}} : {headers: {Authorization: null}};

      this.loading = db.userItems.count()>0;

      axios.get("/user-items",requestHeaders)
          .then(res => {
           const items = res.data.data;

           db.userItems.bulkPut(items)

            this.loading = false;
          })
          .catch(error => {
            this.loading = false;

          })
    },
    itemsSaved() {
      this.sheetOpened = false;
    }
  },
  computed: {
    shouldRefresh() {
      this.getItems();
    }
  },
  mounted() {
    this.getItems();
  }
}
</script>

<style scoped>

</style>