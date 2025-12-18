<template>
  <f7-block-title>Recent Bookmarks</f7-block-title>
  <f7-list strong inset dividers-ios media-list class="skeleton-text" v-if="loading">
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
  <f7-list media-list dividers-ios strong-ios inset v-else >

    <list-item
    v-for="item in items"
    :key="item.id"
    :item="item"
    ></list-item>

    <f7-list-item class="text-center">
      <f7-link>All Bookmarks</f7-link>
    </f7-list-item>

  </f7-list>
</template>

<script>
import ListItem from "@/components/recipe/ListItem.vue";
import {useStore} from "framework7-vue";
import store from "@/js/store";

export default {
  name: "recentBookmarks",
  components: {ListItem},
  data() {
    return {
      items: [],
      loading: false,
      shouldRefresh: useStore(store, "getRefresh"),
    }
  },
  watch: {

    shouldRefresh() {
      this.getItems();

    }
  },
  methods: {
    getItems() {
      this.loading = true;
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