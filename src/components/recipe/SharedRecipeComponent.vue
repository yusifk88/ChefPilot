<template>
  <f7-card flat class="no-margin" style="margin-top: -80px!important;">
    <f7-card-header
        valign="bottom"
        style="background-image: url(https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png); background-size: cover;
               height: 40vh;"
        @click="$refs.standalone.open()"
    >
    </f7-card-header>

    <f7-card-content style="padding-top: 0!important;">

      <p style="margin-top: 5px">

        <f7-breadcrumbs>
          <template v-for="tag in tags">

            <f7-breadcrumbs-item>
              <f7-link>{{ tag }}</f7-link>
            </f7-breadcrumbs-item>
            <f7-breadcrumbs-separator/>
          </template>

        </f7-breadcrumbs>
      </p>

      <p style="color: grey">
        {{ item.description }}
      </p>
      <f7-chip outline :text="'Calories:'+item.nutrition?.calories"></f7-chip>
      <f7-chip outline :text="'Carbohydrates:'+item.nutrition?.carbohydrates"></f7-chip>
      <f7-chip outline :text="'Fat:'+item.nutrition?.fat"></f7-chip>
      <f7-chip outline :text="'Protein:'+item.nutrition?.protein"></f7-chip>

    </f7-card-content>
    <f7-card-footer>
      <p class="date" style="width: 70%!important;">
        PREP:{{ item.estimatedTimeMinutes }}Min
        <difficulty-chip :label="item.difficulty"></difficulty-chip>
      </p>

      <f7-icon size="20" f7="arrow_2_squarepath" style="margin-left: auto !important;">
      </f7-icon>

      <share-button :item="item"></share-button>

<!--      <f7-icon-->
<!--          size="20"-->
<!--          @click="bookMark"-->
<!--          :color="bookmarked ? 'blue' : ''"-->
<!--          :ios="bookmarked ? 'f7:bookmark_filled' : 'f7:bookmark'"-->
<!--          :md="bookmarked?'material:bookmark_filled' : 'material:bookmark'"-->
<!--      ></f7-icon>-->

    </f7-card-footer>

  </f7-card>
  <f7-block-title class="no-margin-top" style="margin-top: 1em !important;">Ingredients</f7-block-title>
  <f7-list strong ividers-ios>
    <f7-list-item v-for="ingredient in ingredients" :title="ingredient.name" :footer="ingredient.quantity">

    </f7-list-item>
  </f7-list>

  <f7-block-title>Instructions</f7-block-title>
  <div class="timeline">
    <div class="timeline-item" v-for="(instruction,index) in instructions">
      <div class="timeline-item-date " style="color: forestgreen"><small>Step</small> {{ index + 1 }}</div>
      <div class="timeline-item-divider"></div>
      <div class="timeline-item-content">
        <div class="timeline-item-inner" style="background-color: #00ff9f; color: #000000!important;">{{ instruction }}</div>
      </div>
    </div>

  </div>


  <f7-photo-browser ref="standalone" :photos="photos" :thumbs="thumbs"/>
</template>

<script>
import DifficultyChip from "@/components/recipe/difficultyChip.vue";
import {f7} from "framework7-vue";
import store from "@/js/store";
import ShareButton from "@/components/recipe/ShareButton.vue";

export default {
  props: {
    item: Object
  },
  name: "SharedRecipeComponent",
  components: {ShareButton, DifficultyChip},
  data() {
    return {
      loadingUpdate: false,
      bookmarked: this.item?.bookmarked,
      photos: [{
        url: "https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png",
        caption: this.item?.name
      }],
      thumbs: ["https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png"]

    }

  },
  computed: {
    tags() {
      return this.item?.tag?.split(",");
    },
    instructions() {

      return this.item?.instructions?.split(",");

    },
    ingredients() {
      return this.item.ingredients
    }
  },
  methods: {
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
            store.dispatch("changeBookmarkState");

          })

    },
  }
}
</script>

<style scoped>

</style>