<template>
<f7-icon v-if="type==='public'" size="10" color="blue" f7="globe"></f7-icon>

<f7-icon v-if="type==='private'" size="10" color="blue" f7="lock"></f7-icon>
<f7-icon v-if="type==='followers'" size="10" color="blue" f7="person_2"></f7-icon>
<f7-icon v-if="type==='following'" size="10" color="blue" f7="person_2_square_stack_fill"></f7-icon>
</template>
<script>
export default {
  props: {
    type: {
      type: String,
      default: "public"
    }
  },
  name: "VisibilityIcon"
}
</script>

<style scoped>

</style>