<template>
  <f7-block inset class=" no-padding-top no-margin no-padding" strong>

    <f7-card class="no-padding">
      <f7-card-header class="no-margin no-padding">
        <div class="row no-margin">
          <p class="col-1"><img
              v-if="post.user" class="message-avatar no-margin"
              style="scale: 1.2;"
              :src="post.user.image_url"
              onerror="this.onerror=null; this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/public/avatar.webp';"
          >
          </p>
          <div class="col-8 no-margin">
            <div class="no-padding no-margin margin-left margin-top">
              <div style="font-weight: bolder; font-size: 15px">
                {{ post.user.name }}
                <f7-button @click="followUser" v-if="!following" style="display: inline">Follow</f7-button>
                <f7-button @click="unfollowUser" v-else-if="!IS_SAME_USER" style="display: inline; font-size: 10px">
                  Following
                </f7-button>
              </div>
              <small style="display: block; font-size: 12px">
                <visibility-icon :type="post.visibility"></visibility-icon>
                {{ timeFromNow(post.created_at) }}</small>
            </div>
          </div>
          <div class="col-2">
            <post-card-options-component @deleted="postDeleted" :post="post"></post-card-options-component>
          </div>
        </div>
      </f7-card-header>


      <span @click="goToPost" :href="postRoute">
        <p class="no-margin no-padding" v-html="post.caption"></p>

        <recipe-card :item="post.recipe"></recipe-card>
      </span>

      <social-item-footer
          :comment_count="post.comments_count"
          :has_commented="post.has_commented"
          :has_liked="post.has_liked"
          :like_count="post.likes_count"
          :post_id="post.id"
          :post_ulid="post.ulid"
          :post="post"
      ></social-item-footer>
    </f7-card>
  </f7-block>

</template>

<script>
import RecipeCard from "@/components/social/RecipeCard.vue";
import Avatar from "@/components/social/Avatar.vue";
import {timeFromNow} from "@/js/utility";
import VisibilityIcon from "@/components/social/VisibilityIcon.vue";
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";
import ShareButton from "@/components/recipe/ShareButton.vue";
import DifficultyChip from "@/components/recipe/difficultyChip.vue";
import PostButtonComponent from "@/components/recipe/PostButtonComponent.vue";
import SocialItemFooter from "@/components/social/SocialItemFooter.vue";
import PostCardOptionsComponent from "@/components/social/PostCardOptionsComponent.vue";

export default {
  emits: ["postDeleted"],
  components: {
    PostCardOptionsComponent,
    SocialItemFooter, PostButtonComponent, DifficultyChip, ShareButton, VisibilityIcon, Avatar, RecipeCard
  },
  props: {
    post: {
      type: Object
    }
  },
  name: "PostItem",
  data() {
    return {
      following: true,
      currentUser: useStore(store, "getUser")
    }
  },
  computed: {
    IS_SAME_USER() {
      return Number(this.currentUser.id) === Number(this.post.user_id);
    },
    dark() {
      return f7.darkMode
    },
    postRoute() {
      return '/posts/' + this.post.ulid
    }
  },
  methods: {
    timeFromNow,
    postDeleted(post) {
      this.$emit('postDeleted', post);
    },
    goToPost() {

      // f7.views.main.router.navigate(this.postRoute);
      f7.views.current.router.navigate(this.postRoute)

      store.dispatch("setSelectedPost", this.post)
    },
    unfollowUser() {
      this.following = false;
      axios.post("/social/unfollow", {
        user_id: this.post.user_id
      })
          .then(res => {
            const successToast = f7.toast.create({
              text: "You unfollowed " + this.post.user.name,
              closeTimeout: 2000
            });
            successToast.open();

          })
    },
    followUser() {

      this.following = true;
      axios.post("/social/follow", {
        user_id: this.post.user_id
      })
          .then(res => {
            const successToast = f7.toast.create({
              text: "🎉You just followed " + this.post.user.name,
              closeTimeout: 2000
            });
            successToast.open();

          })
    }
  },

  mounted() {
    this.following = this.post.is_following_author || this.IS_SAME_USER;
  }
}
</script>

<style>
.row {
  display: flex;
  width: 100%;
}

.col-1 {
  flex: 1;
}

.col-3 {
  flex: 3;
}

.col-2 {
  flex: 2;
}

.col-4 {
  flex: 4;
}

.col-8 {
  flex: 8;
}
</style>