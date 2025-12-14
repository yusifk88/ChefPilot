<template>
  <div class="grid grid-cols-2 grid-gap no-padding">
    <div>
     <h3>Add your food store</h3>
      <p>Add raw food food stuff to get personalised meal recipes </p>
      <f7-button large @click="sheetOpened=true" fill style="color: #000000!important;">Add Food</f7-button>
    </div>
    <div>
      <img width="100%" src="/img/add_food_image.svg">
    </div>
  </div>

  <f7-sheet v-model:opened="sheetOpened" style="height: 90%">
    <f7-toolbar>
      <div class="left"></div>
      Add food items
      <div class="right">
        <f7-link sheet-close><i class="icon icon-close"></i></f7-link>
      </div>
    </f7-toolbar>

<additem></additem>

  </f7-sheet>
</template>

<script>
import routes from "@/js/routes";
import Additem from "@/components/items/additem.vue";

export default {
  components: {Additem},
  props: {
    f7router: Object,
  },
  name: "AddedRecipeCard",
data(){
    return{
      sheetOpened:false
    }
}
}
</script>

<style scoped>

</style>