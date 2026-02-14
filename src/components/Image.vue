<template>
  <div class="container">
    <img :src="src"
         onerror="this.onerror=null; this.src='https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.webp';"
    />
  </div>
</template>

<script>
export default {
  props: {
    src: {
      type: String,
      default:""
    }
  },

  name: "Image"
}
</script>

<style>

.container{
  width: 100%!important;
  height: 300px !important;
  margin: 0!important;
  padding: 0!important;
  border-radius: 15px;
  background-image: url("/img/placeholder.webp");
  background-color: #00ff9f!important;
  background-position: center;
  background-size: cover;
}
.container img{
  height: 350px;
  width: 100%;
  border-radius: 15px;
  background-size: cover;
  background-position: center;

}
</style>