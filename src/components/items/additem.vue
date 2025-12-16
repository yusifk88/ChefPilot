<template>
  <f7-page>

    <f7-navbar>
      <f7-subnavbar :inner="false">
        <f7-searchbar
            placeholder="Search in items"
            @input="searchSubmit"
            @searchbar:search="searchSubmit"
        ></f7-searchbar>
      </f7-subnavbar>
    </f7-navbar>

    <f7-block strong inset style="margin-top: 0!important;">
      <f7-list
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

        <ul>

          <f7-list-item
              v-for="foodItem in filteredItems"
              :key="foodItem.id"
              :title="foodItem.name"
              :subtitle="foodItem.category"
              media-item
              :virtual-list-index="foodItem.id"
              :style="`top: ${filteredItems.topPosition}px`"
              @change="itemChange"
              @click="foodItem.checked=!foodItem.checked"
          >
            <template #after>
              <f7-checkbox v-model:checked="foodItem.checked"/>

            </template>
          </f7-list-item>
        </ul>

      </f7-list>
    </f7-block>


    <f7-fab
        position="right-bottom"
        text="Save"
        v-if="selectedItem.length"
        @click="saveItems"
    >
      <f7-icon ios="f7:checkmark_2" md="material:checkmark_2"></f7-icon>
    </f7-fab>

  </f7-page>

</template>

<script>
import {f7, f7Block, f7List, f7ListItem, f7Navbar, f7Page, f7Searchbar, f7Subnavbar, theme,} from 'framework7-vue';

export default {
  name: "additem",
  emits:["saved"],
  components: {
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
      items: [],
      searchKey: "",
      SavingItems: false
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



    saveItems() {

      f7.dialog.preloader("Saving your food items...")
      const data = {
        items: this.selectedItem
      };

      axios.post("/items", data)
          .then(res => {

            f7.dialog.close();

            this.$emit("saved", res.data);

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
    getItems() {
      axios.get("/items")
          .then(res => {
            this.items = res.data.data.items;
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