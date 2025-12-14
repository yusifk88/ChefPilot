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

    <f7-block strong inset >
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
/>
      </ul>

    </f7-list>
</f7-block>

  </f7-page>

</template>

<script>
import {
  f7Navbar,
  f7Page,
  f7List,
  f7ListItem,
  f7Subnavbar,
  f7Searchbar,
  f7Block,
  theme,
} from 'framework7-vue';

export default {
  name: "additem",
  components:{
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
      searchKey:""
    }
  },
  computed:{
    filteredItems(){

if (!this.searchKey){
  return  this.items;
}

     return  this.items.filter(item=>{
        return item.name.toLowerCase().includes(this.searchKey.toLowerCase()) || item.category.toLowerCase().includes(this.searchKey.toLowerCase())
      })
    }
  },
  methods: {
    searchSubmit(searchbar, query, previousQuery){

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