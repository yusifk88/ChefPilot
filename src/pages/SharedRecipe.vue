<template>
  <f7-page name="SharedRecipe">
    <f7-navbar class="no-padding" back-link>
      {{ item ? item.name : ""}}

      <f7-nav-right >
        <f7-link >
          <img v-if="item" class="message-avatar" :src="item.user.image_url">
        </f7-link>
      </f7-nav-right>
    </f7-navbar>
    <shared-recipe-loading-component v-if="loading"></shared-recipe-loading-component>

    <shared-recipe-component v-else :item="item"></shared-recipe-component>
  </f7-page>
</template>

<script>
import SharedRecipeLoadingComponent from "@/components/recipe/sharedRecipeLoadingComponent.vue";
import SharedRecipeComponent from "@/components/recipe/SharedRecipeComponent.vue";

export default {
  components: {SharedRecipeComponent, SharedRecipeLoadingComponent},
  props: {
    f7route: Object,
    f7router: Object,
  },

  name: "SharedRecipe",
  data() {
    return {
      loading: true,
      item: null
    }
  },
  computed: {
    ulid() {
      return this.f7route.params.ulid;
    }
  },
  watch: {
    ulid() {
      this.getItem();
    }
  },
  methods: {
    getItem() {
      this.loading = true;
      const url = "/public-recipe/" + this.ulid;
      axios.get(url)
          .then(res => {
            this.item = res.data.data;
            this.loading = false;

          })
    }
  },
  mounted() {
    this.getItem();
  }
}
</script>

<style scoped>

</style>