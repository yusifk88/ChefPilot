<template>
  <f7-block strong>

  <f7-card @click="showProgress" flat class="recipe-card no-margin">
    <f7-card-content class="image-container" style="padding-top: 0!important; padding-bottom: 0!important; margin-bottom: 0!important;">
      <img
          src="https://pub-582b7213209642b9b995c96c95a30381.r2.dev/flux-schnell-cf/prompt-1769714432137-290535.png"
          alt="Blurred food"
          class="food-image"
      >

      <div class="loading-overlay"></div>
    </f7-card-content>

  </f7-card>
  </f7-block>
<!--    <div class="card-content">-->
<!--      <h2>{{ recipeTitle }}</h2>-->
<!--      <p>{{ recipeDescription }}</p>-->
<!--    </div>-->
</template>
<script>
import {f7} from "framework7-vue";

export default {
  name:"RecipeLoading",
  methods:{
    showProgress(){
      const successToast = f7.toast.create({
        text: 'I am creating your recipes, bear with me 👨‍🍳',
        closeButton: true,
      });

      successToast.open();

    }
  }
}
</script>
<style scoped>


.recipe-card {

  overflow: hidden;
}

.image-container {
  position: relative;
  width: 100%;
  height: 512px;
  overflow: hidden;
}

.food-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: blur(8px);
}

.loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
}

.loading-overlay::before {
  content: '';
  position: absolute;
  top: -2px;
  left: -2px;
  right: -2px;
  bottom: -2px;
  animation: siriEdgeGlow 3s linear infinite;
  background: linear-gradient(
      90deg,
      transparent 0%,
      rgba(140, 200, 34, 0.55) 50%,
      rgba(241, 183, 5, 0.47) 75%,
      rgba(132, 11, 159, 0.55) 25%,
      transparent 100%
  );
  border-radius: 20px;
  filter: blur(8px);
}

.loading-overlay::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
      90deg,
      transparent 0%,
      rgba(132, 11, 159, 0.55) 25%,
      rgba(140, 200, 34, 0.55) 50%,
      rgba(241, 183, 5, 0.6) 75%,
      transparent 100%
  );
  animation: siriEdgeGlow 5s linear infinite;
  animation-delay: 1.5s;
  border-radius: 20px;
  filter: blur(12px);
}

@keyframes siriEdgeGlow {
  0% {
    transform: translateX(-100%) translateY(-100%) rotate(0deg);
    opacity: 0;
  }
  10% {
    opacity: 1;
  }
  25% {
    transform: translateX(0%) translateY(-100%) rotate(90deg);
  }
  50% {
    transform: translateX(100%) translateY(0%) rotate(180deg);
  }
  75% {
    transform: translateX(0%) translateY(100%) rotate(270deg);
  }
  90% {
    opacity: 1;
  }
  100% {
    transform: translateX(-100%) translateY(0%) rotate(360deg);
    opacity: 0;
  }
}

.card-content {
  padding: 24px;
}

.card-content h2 {
  color: #1a1a1a;
  font-size: 24px;
  margin-bottom: 8px;
}

.card-content p {
  color: #666;
  line-height: 1.6;
}
</style>
