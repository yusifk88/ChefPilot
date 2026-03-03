<template>

<span v-if="loading">
  <f7-card
      v-for="i in 2"
      class="skeleton-text no-margin skeleton-effect-pulse"
      title="Card Header"
      content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit."
      footer="Card Footer"
      style="height: 400px"
  ></f7-card>
</span>

  <div
      v-else-if="posts.length"
  >
<!--    <div style="padding: 10px; background-color: #00ff9f; border-radius: 15px!important; color: #000000!important;">-->
<!--      The discover feature helps you discover recipes from the ChefPilot community and their thoughts and experiences on-->
<!--      some recipes. It is work in progress so we will be improving it as time goes on.-->
<!--      Please check out for updates as we improve it.<br>-->
<!--      Thank you for try it out and happy cooking!-->
<!--    </div>-->
    <f7-button @click="getFeed">Load More</f7-button>

    <post-item
        v-for="post in posts"
        :key="post.id"
        :post="post"
    >
    </post-item>

    <p style="text-align: center">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-emoji-smile"
           viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path
            d="M4.285 9.567a.5.5 0 0 1 .683.183A3.5 3.5 0 0 0 8 11.5a3.5 3.5 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5"/>
      </svg>
      You are all caught up
    </p>
    <f7-button @click="getFeed">Load More</f7-button>

  </div>
  <empty-state
      v-else
      type="discover"
      title="There is nothing to recommend yet"
      details="Check on the 'For you' tab and react to posts you like to make it easier to recommend posts to you"
  ></empty-state>

</template>
<script>
import PostItem from "@/components/social/PostItem.vue";
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";
import {AUTH_HEADERS} from "@/js/utility";
import EmptyState from "@/components/empty/EmptyState.vue";

export default {
  name: "Recommended",
  components: {EmptyState, PostItem},
  emits: ["reload"],
  data() {
    return {

      posts: [],
      loading: false,
      showRefresh: useStore(store, "getReloadForYou")
    }
  },
  watch: {
    showRefresh() {
      this.getFeed();
    }
  },
  methods: {

    showNotification() {
      const notification = f7.notification.create({
        icon: '<i class="icon icon-f7"></i>',
        title: 'Framework7',
        titleRightText: 'now',
        subtitle: 'Notification with close on click',
        text: 'Click me to close',
        closeOnClick: true,
        on: {
          close() {
            f7.dialog.alert('Notification closed');
          },
        },
      });
      notification.open();


    },

    getFeed() {

      this.loading = true;
      const api = "/social/recommended";

      axios.get(api, AUTH_HEADERS)
          .then(res => {
            this.posts = res.data.data.data;
            this.loading = false;

          })
    }
  },
  mounted() {
    this.getFeed();
  }
}
</script>

<style scoped>

</style>