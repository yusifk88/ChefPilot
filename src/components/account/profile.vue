<template>
  <f7-page>
    <f7-block inset strong v-if="user">

      <img :src="user.image_url" style="border-radius: 15px">
      <div class="grid grid-cols-3 grid-gap">
      <f7-button>Change</f7-button>
      </div>

      <f7-list-item
          :title="user.name ?? 'You have no bio'"
          subtitle="Bio"
      >
      </f7-list-item>
    </f7-block>

    <f7-block v-if="user" inset strong>
      <f7-list inset-ios media-list dividers strong>

        <f7-list-item
            :title="user.name"
            subtitle="Name"
        >
        </f7-list-item>

        <f7-list-item
            :title="user.email"
            subtitle="Email"
            disabled=""
        >
        </f7-list-item>

        <f7-list-item
            :title="formatDateTIme(user.created_at)"
            subtitle="Joined At"
        >
        </f7-list-item>


      </f7-list>

      <div class="grid grid-cols-3 grid-gap">
        <f7-button>Update</f7-button>
      </div>
    </f7-block>
  </f7-page>
</template>

<script>
import {useStore} from "framework7-vue";
import store from "@/js/store";
import {formatDateTIme} from "@/js/utility";

export default {
  name: "profile",
  methods: {formatDateTIme},
  data(){
    return{
      user:useStore(store,"getUser")
    }
  }
}
</script>

<style scoped>

</style>