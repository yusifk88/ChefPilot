<template>
  <f7-button :disabled="loading" v-if="post.user_id===user.id" round actions-open="#post-actions" size="small" class="margin-top">
    <f7-icon size="20" f7="ellipsis"></f7-icon>
  </f7-button>
  <f7-actions id="post-actions">
    <f7-actions-group>
      <f7-actions-label>Take Action</f7-actions-label>
<!--      <f7-actions-button>Edit</f7-actions-button>-->
      <f7-actions-button @click="openConfirmDelete">Delete</f7-actions-button>
    </f7-actions-group>
    <f7-actions-group>
      <f7-actions-button color="red">Cancel</f7-actions-button>
    </f7-actions-group>
  </f7-actions>
</template>

<script>
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";

export default {
  name: "PostCardOptionsComponent",
  emits:["deleted"],
  props: {
    post: {
      type: Object
    }
  },
  data() {
    return {
      user: useStore(store, "getUser"),
      loading:false
    }
  },
  methods:{
    openConfirmDelete() {
      f7.dialog.confirm('Do you want to delete this post?', () => {
        this.loading=true;
        f7.progressbar.show('multi');
        const URL = "/social/posts/"+this.post.id;
        axios.delete(URL)
            .then(res=>{
              f7.progressbar.hide()
              this.loading=false;

              this.$emit("deleted", res.data.data);
            })
      });
    },
  }
}
</script>

<style scoped>

</style>