<template>
  <f7-card flat class="no-margin" :title="item.name">
    <!--    <f7-card-header-->
    <!--        valign="top"-->
    <!--    >{{item.name}}</f7-card-header-->
    <!--    >-->
    <f7-card-content style="padding-top: 0!important;">

      <f7-link :href="`/recipe/${item.id}`" @click="setItem">

      <img

          style="width: 100%; border-radius: 15px"
           src="https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png"
      />
      </f7-link>

      <p>
        <f7-breadcrumbs>
          <template v-for="tag in tags">

            <f7-breadcrumbs-item>
              <f7-link :href="`/recipe/${item.id}`" @click="setItem">{{ tag }}</f7-link>
            </f7-breadcrumbs-item>
            <f7-breadcrumbs-separator/>
          </template>

        </f7-breadcrumbs>
      </p>
      <f7-link :href="`/recipe/${item.id}`" @click="setItem">

      <p style="color: grey">
        {{ item.description }}
      </p>
      </f7-link>
    </f7-card-content>
    <f7-card-footer>
      <p class="date">
        ETA:{{ item.estimatedTimeMinutes }}Min <difficulty-chip :label="item.difficulty"></difficulty-chip>
      </p>
      <f7-icon
          @click="bookMark"
          :color="bookmarked ? 'blue' : ''"
          :ios="bookmarked ? 'f7:bookmark_filled' : 'f7:bookmark'"
          :md="bookmarked?'material:bookmark_filled' : 'material:bookmark'"
      ></f7-icon>

    </f7-card-footer>
  </f7-card>

</template>

<script>
import DifficultyChip from "@/components/recipe/difficultyChip.vue";
import {f7, useStore} from "framework7-vue";
import store from "@/js/store";

export default {
  components: {DifficultyChip},
  props: {
    item: {
      type: Object
    }
  },
  name: "RecipeItem",
  data() {
    return {
      bookmarked: this.item.bookmarked
    }
  },
  computed: {
    tags() {
      return this.item.tag.split(",");
    }
  },
  methods: {
    setItem(){
     store.dispatch("setRecipeItem",this.item)
    },
    bookMark() {
      this.bookmarked = !this.bookmarked;
      const URL = "/recipes/" + this.item.id + "/bookmark";
      axios.patch(URL)
          .then(res => {

            const successToast = f7.toast.create({
              text: this.bookmarked ? 'Dish bookmarked' : "Dish removed from your bookmarks",
              closeTimeout: 2000
            });

            successToast.open();


          })
    }
  }
}
</script>


<style scoped>

</style>