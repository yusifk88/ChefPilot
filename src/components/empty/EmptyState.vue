<template>
<f7-block class="margin text-center">
  <div style="text-align: center" class="margin-top padding-top">
    <img :src="types[type]" width="200em" style="margin: auto!important;">

    <f7-block-title class="no-margin">{{title}}</f7-block-title>
    <p class="padding-left padding-right" style="color: grey">{{details}}</p>
  </div>
</f7-block>
</template>

<script>
export default {
  props:{
    type:{
      default:"bookmarks",
      type:String
    },
    title:{
      default:"No Recorde Found",
      type:String
    },
    details:{
      default:"We could not find any information for you at the moment",
      type:String
    }

  },
  name: "EmptyState",
  data(){
    return{
      types:{
        bookmarks:"/img/empty/no_bookmarks.svg",
        items:"/img/empty/empty_items.svg",
        recipes:"/img/empty/empty_recipes.svg",
        notifications:"/img/empty/no_notifications.svg",
        activities:"/img/empty/no_activities.svg",

      }
    }
  }
}
</script>

<style scoped>

</style>