<template>
  <f7-card-footer style="padding-top: 0!important;margin-top: 0!important;">

    <div class="row">
      <div class="col-2">
        {{formatSocialNumber(likeCount)}}
        <f7-icon
            @click="likePost"
            :f7="hasLiked ? 'heart_filled' : 'heart'"
            :color="hasLiked ? 'red' : ''"
        ></f7-icon>
      </div>
      <div class="col-2">
        {{formatSocialNumber(commentCount)}}
        <f7-icon
            f7="chat_bubble"
        ></f7-icon>
      </div>
      <div class="col-4">

        <f7-icon
            f7="square_arrow_up"
            md="material:share"
        ></f7-icon>
      </div>

    </div>


  </f7-card-footer>
</template>

<script>
import {formatSocialNumber} from "../../js/utility";

export default {
  props: {
    has_liked: {
      type: Boolean,
      default: false
    },
    has_commented: {
      type: Boolean,
      default: false
    },
    like_count: {
      type: Number,
      default: 0
    },
    comment_count: {
      type: Number,
      default: 0
    }
  },
  name: "SocialItemFooter",
  data() {
    return {
      likeCount: 0,
      commentCount: 0,
      hasLiked: false,
      hasCommented: false
    }
  },
  methods: {formatSocialNumber,
  likePost(){
    this.hasLiked = !this.hasLiked;
    if (this.hasLiked){
      this.likeCount+=1;
    }else{
      this.likeCount-=1;
    }
  }
  },
  mounted() {
    this.likeCount = this.like_count;
    this.commentCount = this.comment_count;
    this.hasCommented = this.has_commented;
    this.hasLiked = this.has_liked;
  }
}
</script>

<style scoped>

</style>