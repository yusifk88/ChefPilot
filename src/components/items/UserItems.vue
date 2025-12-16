<template>

  <f7-page-content>
    <f7-block>
      <f7-list strong inset dividers-ios>
        <f7-list-item @swipeout:deleted="deleted(item)" v-for="item in items" :key="item.id"
                      swipeout :title="item.name">
          <template #media>
            <f7-icon icon="icon-gift"/>
          </template>
          <f7-swipeout-actions right>
            <f7-swipeout-button delete confirm-text="Are you sure you want to delete this item?">Delete
            </f7-swipeout-button>
          </f7-swipeout-actions>
        </f7-list-item>

      </f7-list>
    </f7-block>
  </f7-page-content>
</template>

<script>
export default {
  emits:["itemDeleted"],
  name: "UserItems",
  props: {
    items: {
      type: Array
    }
  },
  methods: {
    deleted(item) {

      const URL = "/user-items/" + item.id;
      axios.delete(URL)
          .then(res => {

            this.$emit("itemDeleted", res.data.data);
          })
    }
  }
}
</script>

<style scoped>

</style>