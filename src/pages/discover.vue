<template>
  <f7-page id="mainPage"  :page-content="false" :ptr-mousewheel="true" ptr @ptr:refresh="refresh">
    <f7-navbar title="Discover Recipes" class="no-margin-bottom no-padding-bottom">
      <f7-nav-right>
        <f7-link style="transition: 0.3s ease-in-out">
          <f7-icon f7="search"></f7-icon>
        </f7-link>

      </f7-nav-right>
    </f7-navbar>
    <f7-toolbar top tabbar class="no-margin-top no-padding-top">
      <f7-toolbar-pane>
        <f7-link @click="activetab='for-you-tab'; scrollToTop()" size="20" tab-link="#for-you-tab"
                 :tab-link-active="activetab==='for-you-tab'">For You
        </f7-link>
        <f7-link @click="activetab='recommended-tab'" :tab-link-active="activetab==='recommended-tab'"
                 tab-link="#recommended-tab">Recommended
        </f7-link>
        <f7-link @click="activetab='explore-tab'" :tab-link-active="activetab==='explore-tab'" tab-link="#explore-tab">
          Explore
        </f7-link>
        <f7-link @click="activetab='following-tab'" :tab-link-active="activetab==='following-tab'"
                 tab-link="#following-tab">Following
        </f7-link>
      </f7-toolbar-pane>
    </f7-toolbar>

    <f7-tabs :swipeable="true" animated class="no-margin" id="tabsContainer">

      <f7-tab id="for-you-tab"
              class="page-content "
              :tab-active="activetab==='for-you-tab'">
        <f7-block strong>

          <for-you v-if="currentUser && activetab==='for-you-tab'"></for-you>
        </f7-block>
      </f7-tab>

      <f7-tab id="recommended-tab" class="page-content" :tab-active="activetab==='recommended-tab'">
        <f7-block strong>

         <recommended v-if="currentUser && activetab==='recommended-tab'"></recommended>
        </f7-block>
      </f7-tab>

      <f7-tab id="explore-tab" class="page-content" :tab-active="activetab==='explore-tab'">
        <f7-block strong>
         <explore v-if="currentUser && activetab==='explore-tab'" ></explore>
        </f7-block>
      </f7-tab>

      <f7-tab id="following-tab" class="page-content" :tab-active="activetab==='following-tab'">
        <f7-block strong>
         <following v-if="currentUser && activetab==='following-tab'" ></following>
        </f7-block>
      </f7-tab>


    </f7-tabs>


  </f7-page>
</template>

<script>
import EmptyState from "@/components/empty/EmptyState.vue";
import ForYou from "@/components/social/ForYou.vue";
import {useStore} from "framework7-vue";
import store from "@/js/store";
import Recommended from "@/components/social/Recommended.vue";
import Explore from "@/components/social/Explore.vue";
import Following from "@/components/social/Following.vue";

export default {
  name: "discover",
  components: {Following, Explore, Recommended, ForYou, EmptyState},
  data() {
    return {
      activetab: "for-you-tab",
      currentUser:useStore(store,"getUser"),
      currentTab:useStore(store,"getMainTab")
    }
  },
  methods:{
    scrollToTop(){
      const el = document.getElementById("mainPage");
      el.scrollTo({ top: 0, behavior: "smooth" });
    },
    refresh(done){

      if (this.activetab==='for-you-tab'){
        store.dispatch("toggleReloadForYou");
      }
      done()
    },
    testBottom(){
      alert("end")
    }
  }
}
</script>

<style scoped>

</style>