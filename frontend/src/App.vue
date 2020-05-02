<template>
  <v-app>
    <ENavbar />

    <v-content>
      <v-container>
        <router-view></router-view>
      </v-container>
    </v-content>

    <EFooter class="d-none d-sm-flex" />

    <EBottomNavbar class="d-flex d-sm-none" />
  </v-app>
</template>

<script>
import ENavbar from "@/components/ENavbar";
import EFooter from "@/components/EFooter.vue";
import EBottomNavbar from "@/components/EBottomNavbar.vue";

export default {
  name: "App",
  components: {
    ENavbar,
    EFooter,
    EBottomNavbar,
  },
  data: () => ({
    //
  }),
  created() {
    this.$store.dispatch("checkLogin");
  },
};
</script>

<style>
/*
  Append icon not centering when v-text-input-field dense
 */
.v-text-field.v-input--dense .v-input__icon--append .v-icon {
  margin-top: 1px !important;
}
</style>
