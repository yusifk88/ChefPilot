<template>
  <f7-card
      @click="viewRecipe"
      flat class="no-margin no-padding">

    <f7-card-content
        class="no-padding no-margin"
        style="padding-top: 0!important; padding-bottom: 0!important; margin-bottom: 0!important;">

      <!--        <img-->

      <!--            fetchpriority="high"-->
      <!--            loading="lazy"-->
      <!--            style="border-radius: 15px; width: 100%;"-->
      <!--            :src="item.photos && item.photos.length>0 ? item.photos[0].url : PHOTO_PLACEHOLDER()"-->
      <!--            onerror="this.onerror=null; this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.webp';"-->
      <!--        />-->
      <!--      -->
      <Image :src="item.photos && item.photos.length>0 ? item.photos[0].url : PHOTO_PLACEHOLDER()"></Image>


      <h3 class="margin-top" style="margin-top: 60px!important;"> {{ item.name }}</h3>
      <p class="no-margin">
        <f7-breadcrumbs>
          <template v-for="tag in tags">

            <f7-breadcrumbs-item>
              <small>{{ tag }}</small>
            </f7-breadcrumbs-item>
            <f7-breadcrumbs-separator/>
          </template>

        </f7-breadcrumbs>
      </p>
      <small style="color: grey">
        {{ item.description }}
      </small>
    </f7-card-content>

  </f7-card>
</template>

<script>
import {PHOTO_PLACEHOLDER} from "@/js/utility";
import Image from "@/components/Image.vue";
import {f7} from "framework7-vue";

export default {
  components: {Image},
  methods: {
    PHOTO_PLACEHOLDER() {
      return PHOTO_PLACEHOLDER
    },
    viewRecipe() {
      if (this.recipeRoute) {
        f7.views.main.navigate(this.recipeRoute)
      }
    }
  },
  props: {
    item: {
      type: Object
    },
    recipeRoute: {
      type: String,
      default: null
    }
  },
  name: "RecipeCard",
  computed: {
    tags() {
      return this.item.tag.split(",");
    }
  },

}
</script>

<style scoped>

</style>