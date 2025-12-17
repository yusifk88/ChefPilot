<template>
  <f7-card flat class="no-margin" :title="item.name" >
<!--    <f7-card-header-->
<!--        valign="top"-->
<!--    >{{item.name}}</f7-card-header-->
<!--    >-->
    <f7-card-content style="padding-top: 0!important;">

      <img style="width: 100%; border-radius: 15px" src="https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_u7djpiu7djpiu7dj.png"/>

      <p>
        <f7-breadcrumbs>
          <template v-for="tag in tags">

          <f7-breadcrumbs-item >
            <f7-link>{{tag}}</f7-link>
          </f7-breadcrumbs-item>
          <f7-breadcrumbs-separator />
          </template>

        </f7-breadcrumbs>
        </p>
      <p>
       {{item.description}}
      </p>
    </f7-card-content>
    <f7-card-footer>
      <p class="date">
        ETA:{{item.estimatedTimeMinutes}}Min
      </p>
      <f7-icon ios="f7:bookmark" md="material:bookmark"></f7-icon>
      <difficulty-chip :label="item.difficulty"></difficulty-chip>
    </f7-card-footer>
  </f7-card>
</template>

<script>
import DifficultyChip from "@/components/recipe/difficultyChip.vue";

export default {
  components: {DifficultyChip},
  props:{
    item:{
      type:Object
    }
  },
  name: "RecipeItem",
  computed:{
    tags(){
      return this.item.tag.split(",");
    }
  }
}
</script>


<style scoped>

</style>