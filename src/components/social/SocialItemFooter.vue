<template>
  <f7-card-footer style="padding-top: 0!important;margin-top: 0!important;">

    <div class="row">
      <div class="col-1">
        <f7-icon
            size="20"
            @click="likePost"
            :f7="hasLiked ? 'heart_filled' : 'heart'"
            :color="hasLiked ? 'red' : ''"
        ></f7-icon>
        {{ formatSocialNumber(likeCount) }}
      </div>
      <div class="col-1">
        <f7-icon
            size="20"
            f7="chat_bubble"
        ></f7-icon>
        {{ formatSocialNumber(commentCount) }}
      </div>
      <div class="col-4">

        <f7-icon
            @click="share"
            size="20"
            f7="square_arrow_up"
            md="material:share"
        ></f7-icon>
      </div>

    </div>


  </f7-card-footer>
</template>

<script>
import {BASE_URL, formatSocialNumber} from "@/js/utility";
import {Share} from "@capacitor/share";

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
    },
    post_id: {
      type: Number
    },
    post_ulid: {
      type: String
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
  watch:{
    like_count(){
      this.likeCount = this.like_count;
    },
    comment_count(){
      this.commentCount = this.comment_count;

    },
    has_commented(){
      this.hasCommented = this.has_commented;

    },
    has_liked(){
      this.hasLiked = this.has_liked;
    }
  },
  methods: {
    formatSocialNumber,
    async share() {

     const URL=BASE_URL+"/p/"+ this.post_ulid

      await Share.share({
        url:URL
      })

    },
    likePost() {
      this.hasLiked = !this.hasLiked;
      if (this.hasLiked) {
        this.likeCount += 1;
      } else {
        this.likeCount -= 1;
      }

      if (this.hasLiked) {


        axios.post("social/like", {
          post_id: this.post_id
        })
      } else {
        axios.post("social/unlike", {
          post_id: this.post_id
        })
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