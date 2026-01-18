<template>
  <f7-page>
    <f7-navbar title="Discover"></f7-navbar>
    <f7-block strong>

      <empty-state
          type="discover"
          title="Discover is coming soon."
          details="Interact and share your views on recipes shared by other members of the chefpilot community"
      ></empty-state>
    </f7-block>
  </f7-page>
</template>

<script>
import EmptyState from "@/components/empty/EmptyState.vue";

export default {
  name: "discover",
  components: {EmptyState}
}
</script>

<style scoped>

</style>