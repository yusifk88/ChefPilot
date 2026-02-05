<template>
  <f7-page :page-content="false" ptr @ptr:refresh="test">
    <f7-navbar title="Discover">
      <f7-nav-right>
        <f7-link style="transition: 0.3s ease-in-out">
          <f7-icon f7="search"></f7-icon>
        </f7-link>

      </f7-nav-right>
    </f7-navbar>
    <f7-toolbar top tabbar class="no-margin-top no-padding-top">
      <f7-toolbar-pane>
        <f7-link @click="activetab='for-you-tab'" size="20" tab-link="#for-you-tab"
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

    <f7-tabs animated>

      <f7-tab id="for-you-tab"
              class="page-content"
              :tab-active="activetab==='for-you-tab'">
        <f7-block strong>
          <for-you></for-you>
        </f7-block>
      </f7-tab>

      <f7-tab id="recommended-tab" class="page-content" :tab-active="activetab==='recommended-tab'">
        <f7-block strong>
          <empty-state
              type="discover"
              title="Recommended is coming soon."
              details="Interact and share your views on recipes shared by other members of the chefpilot community"
          ></empty-state>
        </f7-block>
      </f7-tab>

      <f7-tab id="explore-tab" class="page-content" :tab-active="activetab==='explore-tab'">
        <f7-block strong>
          <empty-state
              type="discover"
              title="explore is coming soon."
              details="Interact and share your views on recipes shared by other members of the chefpilot community"
          ></empty-state>
        </f7-block>
      </f7-tab>

      <f7-tab id="following-tab" class="page-content" :tab-active="activetab==='following-tab'">
        <f7-block strong>
          <empty-state
              type="discover"
              title="following is coming soon."
              details="Interact and share your views on recipes shared by other members of the chefpilot community"
          ></empty-state>
        </f7-block>
      </f7-tab>


    </f7-tabs>


  </f7-page>
</template>

<script>
import EmptyState from "@/components/empty/EmptyState.vue";
import ForYou from "@/components/social/ForYou.vue";

export default {
  name: "discover",
  components: {ForYou, EmptyState},
  data() {
    return {
      activetab: "for-you-tab"
    }
  },
  methods:{
    test(done){
      alert("test")
      done()
    }
  }
}
</script>

<style scoped>

</style>