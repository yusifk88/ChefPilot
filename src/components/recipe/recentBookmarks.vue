<template>
  <f7-block-title style="margin-top: 0!important;" class="no-margin-bottom no-padding-bottom">Recent Bookmarks</f7-block-title>

  <f7-list strong inset dividers-ios media-list class="skeleton-text"
           v-if="loading">
    <f7-list-item
        title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
        subtitle="Subtitle"
        text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
        class="skeleton-effect-pulse"
    >
      <template #media>
        <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px" />
      </template>
    </f7-list-item>
    <f7-list-item
        title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
        subtitle="Subtitle"
        text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
        class="skeleton-effect-pulse"

    >
      <template #media>
        <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px" />
      </template>
    </f7-list-item>

      <f7-list-item
        title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
        subtitle="Subtitle"
        text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
        class="skeleton-effect-pulse"

    >
      <template #media>
        <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px" />
      </template>
    </f7-list-item>

  </f7-list>
  <span v-else>


     <empty-state
         v-if="items.length==0"
         title="You Have No Bookmarks Yet"
         details="No bookmarks to show, your most recent bookmarked recipes will show here."
     ></empty-state>

  <f7-list media-list dividers-ios strong-ios inset class="no-margin-top" style="margin-top: 10px!important;"  >

    <list-item
    v-for="item in items"
    :key="item.id"
    :item="item"
    ></list-item>

  </f7-list>
  </span>

</template>

<script>
import ListItem from "@/components/recipe/ListItem.vue";
import {useStore} from "framework7-vue";
import store from "@/js/store";
import EmptyState from "@/components/empty/EmptyState.vue";

export default {
  name: "recentBookmarks",
  components: {EmptyState, ListItem},
  data() {
    return {
      items: [],
      loading: false,
      shouldRefresh: useStore(store, "getRefresh"),
      bookmarkChanged:useStore(store,"bookMarkState")
    }
  },
  watch: {

    shouldRefresh() {
      this.getItems();

    },
    bookmarkChanged(){
      this.getItems(false)
    }
  },
  methods: {
    getItems(state=true) {
      this.loading = state;
      axios.get("/recent-bookmarks")
          .then(res => {

            this.items = res.data.data
            this.loading=false
          })
    }
  },
  mounted() {
    this.getItems();
  }
}
</script>

<style scoped>

</style>