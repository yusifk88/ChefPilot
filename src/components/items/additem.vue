<template>
  <f7-page ptr :ptr-mousewheel="true" @ptr:refresh="getItems">

    <f7-navbar>

      <f7-subnavbar v-if="Capacitor().getPlatform().toLowerCase()==='ios'">
        <f7-searchbar
            placeholder="Search in items"
            @input="searchSubmit"
            @searchbar:search="searchSubmit"
        ></f7-searchbar>
      </f7-subnavbar>

      <f7-searchbar
          v-else
          placeholder="Search in items"
          @input="searchSubmit"
          @searchbar:search="searchSubmit"
      ></f7-searchbar>

    </f7-navbar>

    <f7-block strong style="margin-top: 0!important;" class="no-padding">


      <f7-list style="margin-top: 0!important;" strong inset dividers-ios media-list class="skeleton-text"
               v-if="loading">
        <f7-list-item
            v-for="i in loaderCount"
            title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
            class="skeleton-effect-pulse"
        >
        </f7-list-item>

      </f7-list>
      <f7-list
          v-else-if="items.length>0"
          strong
          inset
          dividers-ios
          virtual-list
          media-list
          :virtual-list-params="{
        filteredItems,
        height: theme().ios ? 63 : theme.md ? 73 : 77,
      }"
      >
        <f7-list-item
            checkbox
            v-for="foodItem in filteredItems"
            :key="foodItem.id"
            :title="foodItem.name"
            :subtitle="foodItem.category"
            :virtual-list-index="foodItem.id"
            :style="`top: ${filteredItems.topPosition}px`"
            checkbox-icon="end"
            @click="foodItem.checked=!foodItem.checked"
        >

          <template #media>
              <span v-if="foodItem.image_type==='emoji'" style="font-size: 30px">
              {{ foodItem.image }}
              </span>
            <img :src="foodItem.image" width="30px;" style="border-radius: 8px;" v-else>
          </template>
        </f7-list-item>

      </f7-list>
      <empty-state
          type="items"
          v-else
          title="No items here"
          details="There are no items to show, you can pull to refresh to reload new items"
      ></empty-state>
    </f7-block>

    <f7-fab
        position="right-bottom"
        :text="'Save '+ selectedItem.length"
        v-if="selectedItem.length"
        @click="saveItems"
    >
      <f7-icon ios="f7:checkmark_2" md="material:checkmark_2"></f7-icon>
    </f7-fab>

  </f7-page>

</template>

<script>
import {f7, f7Block, f7List, f7ListItem, f7Navbar, f7Page, f7Searchbar, f7Subnavbar, theme,} from 'framework7-vue';
import {Capacitor} from "@capacitor/core";
import EmptyState from "@/components/empty/EmptyState.vue";
import {useObservable} from "@vueuse/rxjs";
import {liveQuery} from "dexie";
import {db} from "@/js/db";

export default {
  name: "additem",
  emits: ["saved"],
  props: {
    refreshItems: {
      type: Boolean,
      default: false
    }
  },
  components: {
    EmptyState,
    f7Navbar,
    f7Page,
    f7List,
    f7ListItem,
    f7Subnavbar,
    f7Searchbar,
    f7Block,
  },
  data() {
    return {
      items: useObservable(liveQuery(() => db.items.orderBy("name").toArray())),
      searchKey: "",
      SavingItems: false,
      loading: false,
      loaderCount: 15
    }
  },
  watch: {
    refreshItems() {

      this.getItems();

    }
  },
  computed: {

    selectedItem() {

      return this.filteredItems.filter(item => item.checked);

    },

    filteredItems() {

      if (!this.searchKey) {
        return this.items;
      }

      return this.items.filter(item => {
        return item.name.toLowerCase().includes(this.searchKey.toLowerCase()) || item.category.toLowerCase().includes(this.searchKey.toLowerCase())
      })
    }
  },
  methods: {
    Capacitor() {
      return Capacitor
    },

    saveItems() {

      this.searchKey = null;
      f7.dialog.preloader("Saving your food items...")
      const data = {
        items: this.selectedItem
      };

      axios.post("/items", data)
          .then(res => {

            f7.dialog.close();
            db.userItems.bulkPut(res.data)

            this.$emit("saved");

          })
          .catch(error => {
            f7.dialog.close();

          })


    },


    searchSubmit(searchbar, query, previousQuery) {

      this.searchKey = searchbar.target.value;

    },
    theme() {
      return theme
    },
    getItems(done = null) {

      this.loading = db.items.count() > 0;

      axios.get("/items")
          .then(res => {
            if (done) {
              done();
            }
            this.items = res.data.data.items.map(item => {

              item.checked = false;
              return item;
            })

            this.loading = false;

          })
    },
    searchAll(query, items) {
      const found = [];
      for (let i = 0; i < items.length; i += 1) {
        if (items[i].name.toLowerCase().indexOf(query.toLowerCase()) >= 0 || query.trim() === '')
          found.push(i);
      }

      console.log(found);
      return found;
    },
    renderExternal(vl, items) {
      this.items = items;
    },
  },
  mounted() {
    this.getItems();
  }
}
</script>

<style scoped>

</style>