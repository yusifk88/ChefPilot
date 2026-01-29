<template>
  <f7-block-title>

    <div class="grid grid-cols-2 grid-gap">
      <div class="margin-top">Dishes of the day</div>
      <div>
        <f7-button :disabled="loadingRecipes" @click="requestRecipes" fill>Get More (+{{recipeLimit}})</f7-button>
      </div>
    </div>


  </f7-block-title>

  <recipe-loading v-if="loadingRecipes"></recipe-loading>
  <f7-block strong v-else>

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
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";
import EmptyState from "@/components/empty/EmptyState.vue";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";
import {liveQuery} from "dexie";
import {useObservable} from "@vueuse/rxjs";
import {db} from "@/js/db";
import RecipeLoading from "@/components/recipe/RecipeLoading.vue";
import axios from "axios";

export default {
  name: "Recipes",
  components: {RecipeLoading, EmptyState, RecipeItem},
  data() {
    return {
      items: useObservable(liveQuery(() => db.recipes.orderBy("id").reverse().toArray())),
      shouldRefresh: useStore(store, "getRefresh"),
      loading: false,
      loadingRecipes: useStore(store, "loadingRecipesState"),
      recipeLimit: 0
    }
  },

  computed: {

    startOfDay() {
      const startOfToday = new Date();
      startOfToday.setHours(0, 0, 0, 0);

      return startOfToday;
    },
    endOfDay() {
      const endOfToday = new Date();
      endOfToday.setHours(23, 59, 59, 999);
      return endOfToday;
    },

    currentUser() {
      return useStore(store, "getUser");
    }
  },
  watch: {

    shouldRefresh() {
      this.getItems();

    }
  },
  methods: {

   async getLimit() {

      const account = await CapacitorPersistentAccount.readAccount()
      const requestHeaders = account.data ? {headers: {Authorization: "Bearer " + account.data.token}} : {headers: {Authorization: null}};

      axios.get("/gen-recipes-count",requestHeaders)
          .then(res => {
            this.recipeLimit = res.data.data.count;
          })
          .catch(error=>{
          })
    },
    requestRecipes() {
      store.dispatch("startLoadingRecipe");

      axios.get("/gen-recipes")
          .then(res => {

          })
          .catch(error => {
            store.dispatch("stopLoadingRecipe");

          })

    },

    async getItems() {

      const account = await CapacitorPersistentAccount.readAccount()

      const count = await db.recipes.count();

      this.loading = count === 0;

      if (!this.loading) {

        f7.progressbar.show('multi');

      }


      const requestHeaders = account.data ? {headers: {Authorization: "Bearer " + account.data.token}} : {headers: {Authorization: null}};

      axios.get("/recipes", requestHeaders)
          .then(res => {
            const items = res.data.data;

            db.recipes.bulkPut(items)

            this.loading = false;

            f7.progressbar.hide()

            store.dispatch("stopLoadingRecipe");


          })
    }
  },
  async mounted() {
    this.getLimit();
    this.getItems();

    const drone = new Scaledrone('Yh4KOdyE8eyesTXu');

    const account = await CapacitorPersistentAccount.readAccount()

    const room = drone.subscribe('RecipeCreated_' + account.data.user.id);

    room.on('message', message => {

      store.dispatch("stopLoadingRecipe");

      db.recipes.bulkPut([message.data])

      this.getLimit();
    });


  }
}
</script>


<style scoped>

</style>