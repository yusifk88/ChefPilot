<template>
  <f7-block class="no-margin-top no-padding-top no-padding-left" style="background-color: transparent!important;">

    <f7-list v-if="loading" strong inset dividers-ios media-list class="skeleton-text">
      <f7-list-item
          title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
          subtitle="Subtitle"
          text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
          class="skeleton-effect-pulse"
      >
      </f7-list-item>
      <f7-list-item
          title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
          subtitle="Subtitle"
          text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
          class="skeleton-effect-pulse"
      >
      </f7-list-item>
      <f7-list-item
          title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
          subtitle="Subtitle"
          text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
          class="skeleton-effect-pulse"
      >
      </f7-list-item>

    </f7-list>

    <f7-messages v-else-if="messagesData.length>0" class="no-padding-right">

      <f7-message
          @click="selectedComment=message; showActionSheet=message.user_id===user.id;"
          style="width: 100%!important;"
          v-for="(message, index) in messagesData"
          :key="index"
          type="received"
          :image="message.image"
          :name="message.commenter.name"
          :avatar="message.commenter.image_url"
          :first="isFirstMessage(message, index)"
          :last="isLastMessage(message, index)"
          :tail="isTailMessage(message, index)"
      >
        <template v-if="message.comment" #text>
          <span v-html="message.comment"></span>
        </template>
        <template #text-footer>
          <small>{{ timeFromNow(message.created_at) }}</small>
        </template>
      </f7-message>

    </f7-messages>
    <empty-state
        type="discover"
        title="Be the first to comment"
        details="This post has no comments yet, comments on this post will show here"
        v-else></empty-state>

    <f7-messagebar
        v-model:value="comment"
        :disabled="poasting"
        ref="messagebar"
        class="comment-input-bar"
        placeholder="Post your comment"
    >
      <!--    <template #inner-start>-->
      <!--      <f7-link-->
      <!--          icon-ios="f7:camera_fill"-->
      <!--          icon-md="material:camera_alt"-->
      <!--      />-->
      <!--    </template>-->
      <template #inner-end>
        <f7-button :disabled="poasting" icon-ios="f7:arrow_up_circle_fill" icon-md="material:send"
                   @click="postComment"/>
      </template>

    </f7-messagebar>


  </f7-block>

  <f7-actions v-model:opened="showActionSheet">
    <f7-actions-group>
      <f7-actions-label>Take Action</f7-actions-label>
      <f7-actions-button @click="deleteComment">
        Delete
      </f7-actions-button>
    </f7-actions-group>
    <f7-actions-group>
      <f7-actions-button color="red">Cancel</f7-actions-button>
    </f7-actions-group>
  </f7-actions>

</template>

<script>
import {timeFromNow} from "@/js/utility";
import NotificationsLoading from "@/components/notifications/NotificationsLoading.vue";
import EmptyState from "@/components/empty/EmptyState.vue";
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";

export default {
  emits: ["posted"],
  components: {EmptyState, NotificationsLoading},
  props: {
    postUlid: {
      type: String
    }
  },
  name: "CommentsComponent",
  data() {
    return {
      comment: "",
      messagesData: [],
      loading: false,
      poasting: false,
      selectedComment: null,
      showActionSheet: false,
      user: useStore(store, "getUser")
    }
  },
  methods: {
    timeFromNow,
    deleteComment() {

      f7.dialog.confirm('Do you want to delete this comment?', () => {
        f7.progressbar.show('multi');
        const index = this.messagesData.indexOf(this.selectedComment);
        this.messagesData.splice(index,1)

        const URL = "/social/comments/" + this.selectedComment.id;
        axios.delete(URL)
            .then(res=>{
              this.$emit("posted")
              f7.progressbar.hide()
            })

      });


    },
    postComment() {

      const payload = {
        comment: this.comment,
        post_ulid: this.postUlid
      };
      this.poasting = true;

      axios.post("/social/comment", payload)
          .then(res => {
            this.$emit("posted")
            this.comment = ""
            this.messagesData.unshift(res.data.data);
            this.poasting = false;

          })
          .catch(error => {
            this.poasting = false;
          })

    },
    getComments() {
      this.loading = true;
      const URL = "/social/posts/" + this.postUlid + "/comments";
      axios.get(URL)
          .then(res => {
            this.messagesData = res.data.data.data;
            this.loading = false;
          })

    },
    isFirstMessage(message, index) {
      const self = this;
      const previousMessage = self.messagesData[index - 1];
      if (message.isTitle) return false;
      if (
          !previousMessage ||
          previousMessage.user_id !== message.user_id
      )
        return true;
      return false;
    },
    isLastMessage(message, index) {
      const self = this;
      const nextMessage = self.messagesData[index + 1];
      if (message.isTitle) return false;
      if (!nextMessage || nextMessage.user_id !== message.user_id)
        return true;
      return false;
    },
    isTailMessage(message, index) {
      const self = this;
      const nextMessage = self.messagesData[index + 1];
      if (message.isTitle) return false;
      if (!nextMessage || nextMessage.user_id !== message.user_id)
        return true;
      return false;
    },
  },
  mounted() {

    this.getComments();
  }
}
</script>

<style>
.comment-input-bar {
  position: fixed !important;
}

.message {
  max-width: 100% !important;
}
</style>