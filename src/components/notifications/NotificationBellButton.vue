<template>
  <f7-link href="/notifications" @click="MarkAsRead">
    <f7-icon
        ios="f7:bell"
        md="f7:bell"
        f7="bell"
    >
      <f7-badge v-if="count" color="red">{{ count }}</f7-badge>
    </f7-icon>
  </f7-link>
</template>
<script>
import {useStore} from "framework7-vue";
import store from "@/js/store";
import {CapacitorPersistentAccount} from "@capgo/capacitor-persistent-account";

export default {
  name: "NotificationBellButton",
  data() {
    return {
      count: useStore(store, "getUnreadNotificationsCount"),
      shouldRefresh: useStore(store, "getRefresh")

    }
  },
  watch: {
    shouldRefresh() {
      this.getCount();
    }
  },
  methods: {
    getCount() {
      axios.get("/notifications/count")
          .then(res => {
            store.dispatch("setUnreadNotificationCount", res.data.data.unread)
          })
    },
    MarkAsRead() {
      axios.post("/notifications/mark-as-read")
          .then(res => {

            store.dispatch("setUnreadNotificationCount", 0)

          })


    }
  },
  mounted() {
    CapacitorPersistentAccount.readAccount()
        .then(account => {
          if (account.data) {
            axios.get("/notifications/count", {headers: {Authorization: "Bearer " + account.data.token}})
                .then(res => {
                  store.dispatch("setUnreadNotificationCount", res.data.data.unread)
                })

          }
        })
  }

}
</script>

<style scoped>

</style>