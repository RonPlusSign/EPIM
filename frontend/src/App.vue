<template>
  <v-app>
    <!-- Navbar -->
    <ENavbar />

    <v-content>
      <v-container>
        <!-- Current view -->
        <router-view></router-view>
      </v-container>
    </v-content>
    <!-- Footer -->
    <EFooter class="d-none d-sm-flex" />

    <!-- Bottom Navbar -->
    <EBottomNavbar class="d-flex d-sm-none" />

    <!-- Login dialog -->
    <ELoginDialog />
  </v-app>
</template>

<script>
import ENavbar from "@/components/ENavbar";
import EFooter from "@/components/EFooter.vue";
import EBottomNavbar from "@/components/EBottomNavbar.vue";
import ELoginDialog from "@/components/ELoginDialog.vue";

export default {
  name: "App",
  components: {
    ENavbar,
    EFooter,
    EBottomNavbar,
    ELoginDialog,
  },
  data() {
    return {
      //
    };
  },

  created() {
    // Check if the user is logged when the page is loaded, so we can display user's info
    this.$store.dispatch("checkLogin");
    this.$store.dispatch("getUserData");
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
