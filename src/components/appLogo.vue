<template>
<img :width="width" src="/img/app.svg">
</template>

<script>
export default {
  props:{
    width:{
      type:String,
      default:"16px"
    }
  },
  name: "appLogo"
}
</script>


<style scoped>

</style>