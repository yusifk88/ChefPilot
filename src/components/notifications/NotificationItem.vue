<template>
  <f7-list-item
      :title="notification.data.message"
      :text="notification.data.description"
      @click="goToDetails"
  >
    <template #media>
      <img
          style="border-radius: 8px"
          :src="notification.data.image_url"
          width="80"
          v-if="notification.data.type=='recipe'"
      />
      <div class="svg-container" v-if="notification.data.type.toLowerCase()=='like_interaction'"
           style="width: 80px; height: 80px; background-color: rgba(1,255,160,0.05); border-radius: 8px">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#01ffa0"
             viewBox="0 0 16 16">
          <path
              d="M9 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h10s1 0 1-1-1-4-6-4-6 3-6 4m13.5-8.09c1.387-1.425 4.855 1.07 0 4.277-4.854-3.207-1.387-5.702 0-4.276Z"/>
        </svg>
      </div>

      <div class="svg-container" v-if="notification.data.type=='follow'"
           style="width: 80px; height: 80px; background-color: rgba(1,255,160,0.05); border-radius: 8px">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#01ffa0" class="bi bi-person-vcard"
             viewBox="0 0 16 16">
          <path
              d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5"/>
          <path
              d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z"/>
        </svg>
      </div>

    </template>
    <template #after>
      <span v-if="!notification.read_at"
            style="width: 10px; height: 10px; border-radius: 50%; background-color: dodgerblue"></span>
    </template>
  </f7-list-item>
</template>

<script>
import {f7} from "framework7-vue";

export default {
  props: {
    notification: {
      type: Object
    }
  },
  name: "NotificationItem",
  computed: {
    detailRoute() {

      const map = {
        recipe: '/recipe/' + this.notification.data.recipe_id,
        like_interaction: "/posts/" + this.notification.data.post_ulid,
        comment_interaction: "/posts/" + this.notification.data.post_ulid,
        follow: null,
      };

      return map[this.notification.data.type]
    }
  },
  methods: {
    goToDetails() {
      f7.views.current.router.navigate(this.detailRoute)
    }
  }
}
</script>

<style scoped>

.svg-container {
  display: flex;
  justify-content: center; /* horizontal center */
  align-items: center; /* vertical center */
  height: 300px; /* must have height */
}

</style>