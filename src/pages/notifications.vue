<template>
  <f7-page name="notifications" ptr :ptr-mousewheel="true" @ptr:refresh="getNotifications">
    <f7-navbar title="Notifications" back-link></f7-navbar>


    <notifications-loading v-if="loading"></notifications-loading>
    <empty-state v-else-if="showAll && notifications.all.data.length===0" type="notifications" title="No notifications yet" details="Notifications on new recipes and account activities will show here."></empty-state>

    <f7-block-title v-if="!loading && notifications.unread.length>0">Unread</f7-block-title>

    <f7-block v-if="!loading && notifications.unread.length>0" strong>

      <f7-list v-if="!loading" media-list dividers strong>

        <notification-item v-for="item in notifications.unread" :key="item.id" :notification="item"></notification-item>

      </f7-list>

    </f7-block>

    <f7-button fill class="margin-left margin-right" v-if="!showAll && !loading" @click="showAll=true">Show All
    </f7-button>

    <f7-block-title v-if="showAll && !loading && notifications.all.data.length>0">All</f7-block-title>

    <f7-block strong v-if="showAll && !loading">
      <f7-list media-list dividers strong>
        <notification-item v-for="item in notifications.all.data" :key="item.id"
                           :notification="item"></notification-item>
      </f7-list>
    </f7-block>


  </f7-page>
</template>

<script>
import NotificationItem from "@/components/notifications/NotificationItem.vue";
import NotificationsLoading from "@/components/notifications/NotificationsLoading.vue";
import {useStore} from "framework7-vue";
import store from "@/js/store";
import EmptyState from "@/components/empty/EmptyState.vue";

export default {
  name: "notifications",
  components: {EmptyState, NotificationsLoading, NotificationItem},
  data() {
    return {
      notifications: {
        all: {},
        unread: []
      },
      showAll: false,
      loading: false,
      shouldRefresh: useStore(store, "getRefresh")
    }
  },
  watch: {
    shouldRefresh() {
      this.getNotifications();

    }
  },
  methods: {
    getNotifications(done = null) {
      this.loading = true;
      axios.get("/notifications")
          .then(res => {
            this.notifications = res.data.data;
            if (this.notifications.unread.length == 0) {
              this.showAll = true;
            }
            this.loading = false;
            if (done) {
              done();
            }

          })
    }
  },
  mounted() {
    this.getNotifications();
  }
}
</script>

<style scoped>

</style>