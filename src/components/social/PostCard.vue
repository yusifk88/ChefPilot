<template>
  <f7-page>
    <f7-navbar >

      <f7-nav-right style="height: 40px">
        <f7-link  @click="close()">
          <f7-icon f7="pencil_slash"></f7-icon>
        </f7-link>
      </f7-nav-right>
    </f7-navbar>
    <f7-block strong inset class="no-margin-top">
      <f7-card class="no-padding no-margin">
<!--        <f7-card-header class="no-margin no-padding">-->
<!--          <f7-button outline small actions-open="#show-visibility-action">{{ visibility }}-->
<!--            <f7-icon size="20" f7="chevron_down"></f7-icon>-->
<!--          </f7-button>-->
<!--        </f7-card-header>-->
        <f7-card-content class="no-padding no-margin">

          <f7-text-editor
              :value="textValue"
              class="no-margin"
              resizable
              style="border:none; background-color: transparent; min-height: 120px; -webkit-touch-callout:none; -webkit-user-select: none!important; user-select: none!important;"
              placeholder="What do you think of this recipe?"
              :buttons="[['bold', 'italic', 'underline', 'strikeThrough'],['orderedList', 'unorderedList']]"
              :custom-buttons="customButtons"
              autofocus
              @texteditor:change="value => textValue = value"

          ></f7-text-editor>
        </f7-card-content>
      </f7-card>
      <recipe-card :item="item"></recipe-card>

    </f7-block>


    <f7-fab
        position="right-bottom"
        text="Post"
        @click="postRecipe"
    >
    </f7-fab>

    <f7-actions id="show-visibility-action">
      <f7-actions-group>
        <f7-actions-label>Select Post Visibility</f7-actions-label>
        <f7-actions-button @click="visibility=state" :strong="state===visibility" v-for="state in visibilities">{{
            state
          }}
        </f7-actions-button>
      </f7-actions-group>
      <f7-actions-group>
        <f7-actions-button color="red">Cancel</f7-actions-button>
      </f7-actions-group>
    </f7-actions>
  </f7-page>
</template>

<script>
import Avatar from "@/components/social/Avatar.vue";
import RecipeCard from "@/components/social/RecipeCard.vue";
import {f7} from "framework7-vue";

export default {
  emits: ["closed"],
  props: {
    item: {
      type: Object
    }
  },
  name: "PostCard",
  components: {RecipeCard, Avatar},
  data() {
    return {

      textValue: "",
      visibility: "Public",
      visibilities: [
        "Public", "Private", "Followers","Following"
      ],
      customButtons: {
        imageSelector: {
          content: `<f7-button><f7-icon f7='house' size="10"></f7-icon></f7-button>`
        }

      }
    }
  },
  computed: {
    editor() {
      return document.querySelector(".text-editor-content");
    }
  },
  methods: {

    postRecipe() {

      const progressToast = f7.toast.create({
        text: "Your recipe post is being created",
        closeButton: true
      });

      progressToast.open()

      const payload = {
        caption: this.textValue,
        visibility: this.visibility.toLowerCase(),
        recipe_id: this.item.id,
        tags: this.item.tag.split(",")
      };

      axios.post("/social/post", payload)
          .then(res => {
          })

      this.$emit("closed")

    },
    editorButtonClicked(buttonID) {
      if (buttonID == 'image') {
        alert(buttonID)

        return false;

      }
    },
    close() {

      this.$emit("closed")
    }
  },
  mounted() {
    const editor = document.querySelector(".text-editor-content")
    editor.setAttribute("role", "presentation")
    editor.setAttribute("ref", "captionEditor")
  }
}
</script>

<style>
.text-editor-content {

  -webkit-user-select: none !important;
  -ms-user-select: none !important;
  user-select: none !important;
  -webkit-touch-callout: none !important;
  border-radius: 15px;
  margin-top: 5px !important;
}

</style>