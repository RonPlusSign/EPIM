<template>
  <v-app>
    <ENavbar />

    <v-content>
      <v-container>
        <router-view></router-view>
      </v-container>
    </v-content>

    <EFooter />
  </v-app>
</template>

<script>
import ENavbar from "@/components/ENavbar";
import EFooter from "@/components/EFooter.vue";

export default {
  name: "App",
  components: {
    ENavbar,
    EFooter
  },

  data: () => ({
    //
  })
};
</script>
