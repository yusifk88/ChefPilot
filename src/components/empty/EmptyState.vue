<template>
<f7-block class="margin text-center">
  <div style="text-align: center" class="margin-top padding-top">
    <img src="/img/empty/no_bookmarks.svg" width="200em" style="margin: auto!important; display: inline-block">

    <f7-block-title class="no-margin">{{title}}</f7-block-title>
    <p class="padding-left padding-right" style="color: grey">{{details}}</p>
  </div>
</f7-block>
</template>

<script>
export default {
  props:{
    title:{
      default:"No Recorde Found",
      type:String
    },
    details:{
      default:"We could not find any information for you at the moment",
      type:String
    }

  },
  name: "EmptyState"
}
</script>

<style scoped>

</style>