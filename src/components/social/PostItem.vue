<template>
  <f7-block class="no-margin-top no-padding-top" strong>
    <f7-card class="no-padding no-margin margin-top">
      <f7-card-header class="no-margin no-padding">
        <div class="row">
          <p class="col-1"><img v-if="post.user" class="message-avatar no-margin" style="scale: 1.2;" :src="post.user.image_url">
          </p>
          <div class="col-8 no-margin">
            <div class="no-padding no-margin margin-left margin-top">
              <div style="font-weight: bolder; font-size: 15px">
                {{ post.user.name }}
                <f7-button @click="followUser" v-if="!following" style="display: inline">Follow</f7-button>
              </div>
              <small style="display: block; font-size: 12px">
                <visibility-icon :type="post.visibility"></visibility-icon>
                {{ timeFromNow(post.created_at) }}</small>
            </div>
          </div>
        </div>
      </f7-card-header>

      <p class="no-margin no-padding" v-html="post.caption"></p>
      <recipe-card :item="post.recipe"></recipe-card>

      <social-item-footer></social-item-footer>
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

export default {
  components: {SocialItemFooter, PostButtonComponent, DifficultyChip, ShareButton, VisibilityIcon, Avatar, RecipeCard},
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
  methods: {
    timeFromNow,
    followUser() {

      this.following=true;
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
    this.following = this.post.is_following_author;
    this.following =  Number(this.currentUser.id) === Number(this.post.user_id);
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