<template>
  <f7-block>
    <f7-block-title>Dishes of the day</f7-block-title>
    <f7-card
        v-if="loading"

        class="skeleton-text no-margin skeleton-effect-pulse"
        title="Card Header"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit."
        footer="Card Footer"
        style="height: 400px"
    ></f7-card>

    <swiper-container
        v-else
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
import {useStore} from "framework7-vue";
import store from "@/js/store";

export default {
  name: "Recipes",
  components: {RecipeItem},
  data() {
    return {
      items: [],
      shouldRefresh: useStore(store, "getRefresh"),
      loading:false,
    }
  },
  watch: {

    shouldRefresh() {
   this.getItems();

    }
  },
  methods: {
    getItems() {
      this.loading=true;
      axios.get("/recipes")
          .then(res => {
            this.items = res.data.data;
            this.loading=false;

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