<template>
  <f7-chip :text="label" :color="colors[label.toLowerCase()].color"></f7-chip>
</template>

<script>
export default {
  props: {
    label: {
      type: String
    }
  },
  name: "difficultyChip",
  data(){
    return{
      colors:{
        easy:{
          color:"green"
        },
        medium:{
          color:"blue"
        },
        hard:{
          color:"orange"
        }
      }
    }
  }
}
</script>

<style scoped>

</style>