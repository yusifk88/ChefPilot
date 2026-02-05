<template>
  <f7-block class="no-margin-top no-padding-top" style="border-bottom: 1px solid lightgrey" strong>
    <f7-card class="no-padding no-margin margin-top">
      <f7-card-header class="no-margin no-padding">
        <div class="row">
          <div class="col-2"><img v-if="post.user" class="message-avatar margin-top" :src="post.user.image_url"></div>
          <div class="col-12">
            <p class="no-padding no-margin margin-left">
              <span style="font-weight: bolder; font-size: 15px" >{{post.user.name}}</span>
              <small style="display: block; font-size: 12px"><visibility-icon></visibility-icon>{{timeFromNow(post.created_at)}}</small>
            </p>
          </div>
        </div>
      </f7-card-header>

      <p class="no-margin" v-html="post.caption"></p>
      <recipe-card :item="post.recipe"></recipe-card>
    </f7-card>
  </f7-block>

</template>

<script>
import RecipeCard from "@/components/social/RecipeCard.vue";
import Avatar from "@/components/social/Avatar.vue";
import {timeFromNow} from "@/js/utility";
import VisibilityIcon from "@/components/social/VisibilityIcon.vue";

export default {
  methods: {timeFromNow},
  components: {VisibilityIcon, Avatar, RecipeCard},
  props: {
    post: {
      type: Object
    }
  },
  name: "PostItem"
}
</script>

<style scoped>
.row {
  display: flex;
  width: 100%;
}

.col-4 {
  flex: 4;
}

.col-8 {
  flex: 8;
}
</style>