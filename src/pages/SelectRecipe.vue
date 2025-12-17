<template>
  <f7-page class="no-padding">
    <f7-navbar class="no-padding" :title="item?.name" back-link></f7-navbar>


    <f7-card flat class="no-margin" style="margin-top: -80px!important;">
      <f7-card-header
          valign="bottom"
          style="background-image: url(https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png); background-size: cover;
               height: 40vh;"
          @click="$refs.standalone.open()"
      >
      </f7-card-header>

      <f7-card-content style="padding-top: 0!important;">

        <p>
          <f7-breadcrumbs>
            <template v-for="tag in item?.tag?.split(',')">

              <f7-breadcrumbs-item>
                <f7-link>{{ tag }}</f7-link>
              </f7-breadcrumbs-item>
              <f7-breadcrumbs-separator/>
            </template>

          </f7-breadcrumbs>
        </p>

        <p style="color: grey">
          {{ item?.description }}
        </p>
        <f7-chip outline :text="'Calories:'+item?.nutrition.calories"></f7-chip>
        <f7-chip outline :text="'Carbohydrates:'+item?.nutrition.carbohydrates"></f7-chip>
        <f7-chip outline :text="'Fat:'+item?.nutrition.fat"></f7-chip>
        <f7-chip outline :text="'Protein:'+item?.nutrition.protein"></f7-chip>

      </f7-card-content>
      <f7-card-footer>
        <p class="date">
          ETA:{{ item?.estimatedTimeMinutes }}Min <difficulty-chip :label="item?.difficulty"></difficulty-chip>
        </p>
        <f7-icon
            @click="bookMark(item.id)"
            :color="bookmarked ? 'blue' : ''"
            :ios="bookmarked ? 'f7:bookmark_filled' : 'f7:bookmark'"
            :md="bookmarked?'material:bookmark_filled' : 'material:bookmark'"
        ></f7-icon>

      </f7-card-footer>

    </f7-card>
    <f7-block-title class="no-margin-top" style="margin-top: 1em !important;">Ingredients</f7-block-title>
    <f7-list strong ividers-ios  v-if="item">
      <f7-list-item v-for="ingredient in item.ingredients" :title="ingredient.name" :footer="ingredient.quantity">
        <template #after>
          <span style="color: #00ff9f; font-size: 10px" v-if="ingredient.fromUserInventory">Available</span>
          <span style="color: #a40000; font-size: 10px" v-else>Unavailable</span>
        </template>
      </f7-list-item>
    </f7-list>

    <f7-block-title>Instructions</f7-block-title>
    <div class="timeline">
      <div class="timeline-item" v-for="(instruction,index) in item?.instructions.split(',')">
        <div class="timeline-item-date " style="color: forestgreen"><small>Step</small> {{index+1}} </div>
        <div class="timeline-item-divider"></div>
        <div class="timeline-item-content" >
          <div class="timeline-item-inner" style="background-color: #00ff9f">{{instruction}}</div>
        </div>
      </div>

    </div>

    <f7-photo-browser ref="standalone" :photos="photos" :thumbs="thumbs" />


  </f7-page>

</template>

<script>
import store from "@/js/store";
import {f7, useStore} from "framework7-vue";
import DifficultyChip from "@/components/recipe/difficultyChip.vue";
import {ref} from "vue";

export default {
  name: "SelectRecipe",
  components: {DifficultyChip},
  setup() {
    const item = useStore(store, "selectedItem");
    const tags = item.tag?.split(",");
    const instructions = item?.instructions?.split(",");

    const bookmarked = ref(item?.bookmarked)
    const photos = [{
      url:"https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png",
      caption:item.name
    }];

   const thumbs = ["https://flobaze.atl1.cdn.digitaloceanspaces.com/public/Gemini_Generated_Image_phib9nphib9nphib.png"];

  const  bookMark=(id)=> {

      bookmarked.value = !bookmarked.value;

      const URL = "/recipes/" + id + "/bookmark";
      axios.patch(URL)
          .then(res => {

            const successToast = f7.toast.create({
              text: bookmarked.value ? 'Dish bookmarked' : "Dish removed from your bookmarks",
              closeTimeout: 2000
            });

            successToast.open();


          })
    };
    return {
      item,
      tags,
      instructions,
      bookmarked,
      photos,
      thumbs,
      bookMark
    }
  }

}
</script>

<style scoped>

</style>