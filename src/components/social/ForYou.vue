<template>

  <post-item
      v-for="post in posts"
      :key="post.id"
      :post="post"
  >
  </post-item>


</template>

<script>
import PostItem from "@/components/social/PostItem.vue";
import {AUTH_HEADERS} from "@/js/utility";

export default {
  name: "ForYou",
  components: {PostItem},
  data() {
    return {

      posts: []
    }
  },
  methods: {
    getFeed(done) {

      const api =  "/social/feed";

      axios.get(api,AUTH_HEADERS)
          .then(res => {
            this.posts = res.data.data;
            done()
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