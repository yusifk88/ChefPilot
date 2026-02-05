<template>
  <f7-card flat class="no-margin no-padding">

    <f7-card-content class="no-padding no-margin" style="padding-top: 0!important; padding-bottom: 0!important; margin-bottom: 0!important;">

        <img

            fetchpriority="high"
            loading="lazy"
            style="border-radius: 15px; width: 100%;"
            :src="item.photos && item.photos.length>0 ? item.photos[0].url : PHOTO_PLACEHOLDER()"
            onerror="this.onerror=null; this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.webp';"
        />


      <h3 class="no-margin"> {{ item.name }}</h3>
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

export default {
  methods: {
    PHOTO_PLACEHOLDER() {
      return PHOTO_PLACEHOLDER
    }
  },
  props: {
    item: {
      type: Object
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