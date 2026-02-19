<template>
  <f7-block-title style="margin-top: 0!important;" class=" no-margin-top">Recent Bookmarks</f7-block-title>

  <f7-block inset strong class="">

    <f7-list strong inset dividers-ios media-list class="skeleton-text"
             v-if="loading">
      <f7-list-item
          title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
          subtitle="Subtitle"
          text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
          class="skeleton-effect-pulse"
      >
        <template #media>
          <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px"/>
        </template>
      </f7-list-item>
      <f7-list-item
          title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
          subtitle="Subtitle"
          text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
          class="skeleton-effect-pulse"

      >
        <template #media>
          <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px"/>
        </template>
      </f7-list-item>

      <f7-list-item
          title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
          subtitle="Subtitle"
          text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
          class="skeleton-effect-pulse"

      >
        <template #media>
          <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px"/>
        </template>
      </f7-list-item>

    </f7-list>


    <span v-else>


     <empty-state
         v-if="items && items.length==0"
         title="You Have No Bookmarks Yet"
         details="No bookmarks to show, your most recent bookmarked recipes will show here."
     ></empty-state>

  <f7-list media-list dividers-ios strong-ios inset class="no-margin no-padding" style="margin-top: 10px!important;">

    <list-item
        v-for="item in items"
        :key="item.id"
        :item="item"
    ></list-item>

  </f7-list>
  </span>
  </f7-block>
</template>

<script>
import ListItem from "@/components/recipe/ListItem.vue";
import {useStore} from "framework7-vue";
import store from "@/js/store";
import EmptyState from "@/components/empty/EmptyState.vue";
import {useObservable} from "@vueuse/rxjs";
import {liveQuery} from "dexie";
import {db} from "@/js/db";
import {AUTH_HEADERS} from "@/js/utility";

export default {
  name: "recentBookmarks",
  components: {EmptyState, ListItem},
  data() {
    return {
      items: useObservable(liveQuery(() => db.recentBookmarks.orderBy("id").reverse().limit(5).toArray())),
      loading: false,
      shouldRefresh: useStore(store, "getRefresh"),
      bookmarkChanged: useStore(store, "bookMarkState")
    }
  },
  watch: {

    shouldRefresh() {
      this.getItems();

    },
    bookmarkChanged() {
      this.getItems()
    }
  },
  methods: {
    async getItems() {

      this.loading = db.recentBookmarks.count() > 0;

      axios.get("/recent-bookmarks", AUTH_HEADERS)
          .then(res => {

            const items = res.data.data

            db.recentBookmarks.bulkPut(items);

            this.loading = false
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