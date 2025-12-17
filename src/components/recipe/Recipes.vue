<template>
<f7-block>
  <f7-block-title>Dishes of the day</f7-block-title>

  <swiper-container
      :pagination="false"
      class="no-padding no-margin"
      :space-between="15"
      :speed="900"
  >
    <swiper-slide class=" no-padding"
                  v-for="(item,index) in items" :key="item.id">
  <recipe-item :item="item"></recipe-item>
    </swiper-slide>

  </swiper-container>

</f7-block>
</template>

<script>
import RecipeItem from "@/components/recipe/RecipeItem.vue";

export default {
  name: "Recipes",
  components: {RecipeItem},
  data(){
    return{
      items:[]
    }
  },
  methods:{
    getItems(){
      axios.get("/recipes")
          .then(res=>{
            this.items=res.data.data;
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