<template>
<f7-page name="notifications">
  <f7-navbar title="Notifications" back-link></f7-navbar>

  <f7-block-title>Unread</f7-block-title>

  <f7-block  strong>

    <f7-list media-list dividers strong>

  <notification-item v-for="item in notifications.unread" :key="item.id" :notification="item"></notification-item>

    </f7-list>

  </f7-block>

  <f7-button fill class="margin-left margin-right" v-if="!showAll" @click="showAll=true">Show All</f7-button>

  <f7-block-title v-if="showAll">All</f7-block-title>

  <f7-block  strong v-if="showAll">
    <f7-list media-list dividers strong>
      <notification-item v-for="item in notifications.all.data" :key="item.id" :notification="item"></notification-item>
    </f7-list>
  </f7-block>


</f7-page>
</template>

<script>
import {useStore} from "framework7-vue";
import store from "@/js/store";
import NotificationItem from "@/components/notifications/NotificationItem.vue";

export default {
  name: "notifications",
  components: {NotificationItem},
  data(){
    return{
      notifications:useStore(store,"getNotifications"),
      showAll:false
    }
  }
}
</script>

<style scoped>

</style>