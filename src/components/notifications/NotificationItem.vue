<template>
  <f7-list-item
      :link="notification.data.route"
      :title="notification.data.message"
      :text="notification.data.description"
  >
    <template #media>
      <img
          style="border-radius: 8px"
          :src="notification.data.image_url"
          width="80"
      />
    </template>
    <template #after>
      <span v-if="!notification.read_at" style="width: 10px; height: 10px; border-radius: 50%; background-color: dodgerblue"></span>
    </template>
  </f7-list-item>
</template>

<script>
export default {
  props: {
    notification: {
      type: Object
    }
  },
  name: "NotificationItem"
}
</script>

<style scoped>

</style>