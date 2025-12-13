<template>
  <img :width="width" src="/img/google.svg">

</template>
<script>
export default {
  props:{
    width:{
      type:String,
      default:"16px"
    }
  },
  name: "googleLogo"
}
</script>


<style scoped>

</style>