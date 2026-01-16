<template>
  <f7-block-title>Dishes of the day</f7-block-title>

  <f7-block strong>


    <f7-card
        v-if="loading"

        class="skeleton-text no-margin skeleton-effect-pulse"
        title="Card Header"
        content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi lobortis et massa ac interdum. Cras consequat felis at consequat hendrerit."
        footer="Card Footer"
        style="height: 400px"
    ></f7-card>


    <swiper-container
        :pagination="false"
        class="no-padding no-margin"
        :space-between="15"
        :speed="900"
        v-else-if="items && items.length>0"
    >
      <swiper-slide class=" no-padding"
                    v-for="(item,index) in items" :key="item.id">
        <recipe-item :item="item"></recipe-item>
      </swiper-slide>

    </swiper-container>

    <span v-else>

    <empty-state

        type="recipes"
        title="No dishes or recipes yet"
        details="Recipes suggested by chefpilot using your food inventory will show here"
    ></empty-state>
    </span>

  </f7-block>
</template>

<script>
import RecipeItem from "@/components/recipe/RecipeItem.vue";
import {useStore} from "framework7-vue";
import store from "@/js/store";
import EmptyState from "@/components/empty/EmptyState.vue";

export default {
  name: "Recipes",
  components: {EmptyState, RecipeItem},
  data() {
    return {
      items: [],
      shouldRefresh: useStore(store, "getRefresh"),
      loading: false,
    }
  },
  watch: {

    shouldRefresh() {
      this.getItems();

    }
  },
  methods: {
    getItems() {
      this.loading = true;
      axios.get("/recipes")
          .then(res => {
            this.items = res.data.data;
            this.loading = false;

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