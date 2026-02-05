<template>
  <f7-card flat class="no-margin">

    <f7-card-header class="card-title">
      {{item.name}}
    </f7-card-header>
    <!--    <f7-card-header-->
    <!--        valign="top"-->
    <!--    >{{item.name}}</f7-card-header-->
    <!--    >-->
    <f7-card-content style="padding-top: 0!important; padding-bottom: 0!important; margin-bottom: 0!important;">

      <f7-link :href="`/recipe/${item.id}`" @click="setItem">
  <img

      fetchpriority="high"
      loading="lazy"
      style="border-radius: 15px; width: 100%;"
      :src="item.photos && item.photos.length>0 ? item.photos[0].url : PHOTO_PLACEHOLDER"
      onerror="this.onerror=null; this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.webp';"
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

    <f7-card-footer style="padding-top: 0!important;margin-top: 0!important;">
      <p class="date" style="width: 70%!important;">
        PREP:{{ item.estimatedTimeMinutes }}Min <difficulty-chip :label="item.difficulty"></difficulty-chip>
      </p>

        <post-button-component :item="item"></post-button-component>

      <share-button :item="item"></share-button>

      <f7-icon
          size="20"
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
import ShareButton from "@/components/recipe/ShareButton.vue";
import {PHOTO_PLACEHOLDER} from "@/js/utility";
import PostButtonComponent from "@/components/recipe/PostButtonComponent.vue";

export default {
  components: {PostButtonComponent, ShareButton, DifficultyChip},
  props: {
    item: {
      type: Object
    }
  },
  name: "RecipeItem",
  data() {
    return {
      bookmarked: this.item.bookmarked,
      PHOTO_PLACEHOLDER:PHOTO_PLACEHOLDER
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

            store.dispatch("changeBookmarkState");


          })
    }
  },
  mounted() {
    const img = document.querySelector('img')
    img.onload = () => img.classList.add('loaded')
  }
}
</script>


<style>
.image-wrap {
  position: relative;
}

.image-wrap::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
      120deg,
      rgba(255,255,255,0.15),
      rgba(255,255,255,0.35),
      rgba(255,255,255,0.15)
  );
  animation: shimmer 1.4s infinite;
}

.image-wrap img {
  filter: blur(20px);
  transition: filter 0.4s ease;

}

.image-wrap img.loaded {
  filter: blur(0);
}

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
.card-title {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>