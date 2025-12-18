<template>
<f7-page name="bookmarks" ptr :ptr-mousewheel="true" @ptr:refresh="getItems">

  <f7-navbar class="no-padding" title="Bookmarks"></f7-navbar>


  <f7-list style="margin-top: 0!important;" strong inset dividers-ios media-list class="skeleton-text" v-if="loading">
    <f7-list-item
        v-for="i in loaderCount"
        title="Title rrrrrgrgwgwdgdfgfdgffgfgfgfgffgfg"
        subtitle="Subtitle"
        text="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum."
        class="skeleton-effect-pulse"
    >
      <template #media>
        <f7-skeleton-block style="width: 80px; height: 80px; border-radius: 8px" />
      </template>
    </f7-list-item>

  </f7-list>

  <span v-else>

     <empty-state
         v-if="items.length==0"
         title="You Have No Bookmarks Yet"
         details="No bookmarks to show, your most recent bookmarked recipes will show here."
     ></empty-state>

  <f7-list media-list dividers-ios strong-ios inset v-else >
    <list-item
        v-for="item in items"
        :key="item.id"
        :item="item"
    ></list-item>

  </f7-list>
  </span>

</f7-page>
</template>

<script>
import ListItem from "@/components/recipe/ListItem.vue";
import EmptyState from "@/components/empty/EmptyState.vue";

export default {
  name: "BookMarks",
  components: {EmptyState, ListItem},
  data(){
    return{
      items:[],
      loading:false,
      loaderCount:6
    }
  },
  methods:{
    getItems(done=null){

      this.loading=true;
      axios.get("/bookmarks")
          .then(res=>{
            this.loading=false;
            this.items= res.data.data
            if (done){
              done()
            }
          })
    }
  },
  mounted() {
    this.getItems();
  }
}
</script>

<style scoped>

</style>