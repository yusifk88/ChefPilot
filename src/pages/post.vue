<template>
  <f7-page ptr :ptr-mousewheel="true" @ptr:refresh="getPost" @page:afterout="showNav">
    <f7-navbar
        back-link
        @click:back="showNav"
    >
      <f7-nav-left>
        <avatar v-if="post" :src="post.user.image_url"></avatar>
        {{ post?.user.name }}
      </f7-nav-left>
    </f7-navbar>
    <span v-if="loading">
  <f7-card
      class="skeleton-text no-margin skeleton-effect-pulse"
      title="Card Header"
      content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit."
      footer="Card Footer"
      style="height: 400px"
  ></f7-card>
</span>
    <f7-block v-else-if="post" strong inset class="no-margin-top">
      <f7-card  class="no-padding no-margin margin-top">

          <p class="no-margin no-padding" v-html="post?.caption"></p>

          <recipe-card @click="viewRecipe(post.recipe)" :item="post.recipe"></recipe-card>

        <social-item-footer
            :comment_count="post.comments_count"
            :has_commented="post.has_commented"
            :has_liked="post.has_liked"
            :like_count="post.likes_count"
            :post_id="post.id"
            :post_ulid="post.ulid"
        ></social-item-footer>
      </f7-card>
    </f7-block>
    <comments-component
    :post-ulid="ulid"
    @posted="getPost"
    ></comments-component>


    <f7-sheet
        swipe-to-close
        style="height: 95%"
        v-model:opened="showSheet"
    >
      <f7-toolbar
          style="height: 65px"
      >
        <div class="left"></div>

        <div class="right">
          <f7-link sheet-close><i class="icon icon-close"></i></f7-link>
        </div>
      </f7-toolbar>
      <f7-page>
        <shared-recipe-component hideShareButton @photoTapped="showSheet=false" :item="post.recipe"></shared-recipe-component>
      </f7-page>
    </f7-sheet>

  </f7-page>

</template>

<script>
import {useStore} from "framework7-vue";
import store from "@/js/store";
import Avatar from "@/components/social/Avatar.vue";
import {timeFromNow} from "@/js/utility";
import SocialItemFooter from "@/components/social/SocialItemFooter.vue";
import RecipeCard from "@/components/social/RecipeCard.vue";
import CommentsComponent from "@/components/social/CommentsComponent.vue";
import SelectRecipe from "@/pages/SelectRecipe.vue";
import SharedRecipeComponent from "@/components/recipe/SharedRecipeComponent.vue";

export default {
  name: "post",
  components: {SharedRecipeComponent, SelectRecipe, CommentsComponent, RecipeCard, SocialItemFooter, Avatar},
  props: {
    f7route: Object,
    f7router: Object,
  },
  data() {
    return {
      post: useStore(store, "getSelectedPost"),
      loading: false,
      showSheet:false,
    }
  },
  computed: {
    ulid() {
      return this.f7route.params.ulid;
    }
  },
  methods: {
    timeFromNow,

    viewRecipe(recipe){

      store.dispatch("setRecipeItem",recipe);
      this.showSheet=true;
    },
    showNav() {
      store.dispatch("showMainTab");

    },
    getPost(done=null) {

      if (!this.post) {
        this.loading = true;
      }
      const URL = "/social/posts/" + this.ulid;
      axios.get(URL)
          .then(res => {
            this.post = res.data.data;
            
            this.loading = false;
            if (done) {
              done();
            }
          })
    }

  },
  mounted() {

    store.dispatch("hideMainTab");
    this.getPost();

  }

}
</script>

<style scoped>

</style>