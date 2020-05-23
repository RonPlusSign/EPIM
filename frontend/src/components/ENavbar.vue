<template>
  <div>
    <v-app-bar app color="primary" class="align-content-center" dark>
      <!------------------------------------------->
      <!---- Navigation drawer button toggler ----->
      <!------------------------------------------->
      <v-btn class="mr-1" icon @click="isDrawerExpanded = !isDrawerExpanded">
        <v-icon large>mdi-menu</v-icon>
      </v-btn>

      <!--------------->
      <!---- Logo ----->
      <!--------------->
      <v-btn :to="'/'" text height="44px" class="px-0 mr-2">
        <v-img
          alt="EPIM Logo"
          class="shrink"
          contain
          src="/epim-logo.png"
          transition="scale-transition"
          width="38"
        />

        <h1 class="hidden-sm-and-down ml-2">EPIM</h1>
      </v-btn>

      <v-spacer class="hidden-xs-only" />
      <v-spacer class="hidden-xs-only" />

      <!--------------------->
      <!---- Search bar ----->
      <!--------------------->
      <ESearchProducts />

      <v-spacer class="hidden-xs-only" />

      <!----------------------------------------------------->
      <!---- User profile / login buttons / cart button ----->
      <!----------------------------------------------------->
      <v-row class="hidden-xs-only mr-0">
        <v-col v-if="logged" class="pa-0 text-right">
          <!-- User profile button & Cart button -->
          <v-btn :to="'/profilo'" text class="mr-1">
            <span class="hidden-sm-and-down mr-2"
              >Profilo di {{ user ? user.name : "" }}</span
            >
            <v-icon>mdi-account</v-icon>
          </v-btn>
          <v-btn :to="'/carrello'" icon>
            <v-icon>mdi-cart-outline</v-icon>
          </v-btn>
        </v-col>

        <v-col v-else class="pa-0 text-right">
          <!-- Login button -->
          <v-btn @click="$store.commit('openLoginDialog')" text>
            <span class="pt-1">Login</span>
            <v-icon class="ml-2 hidden-xs-only">mdi-account</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-app-bar>
    <!---------------------------->
    <!---- Navigation drawer ----->
    <!---------------------------->
    <ENavigationDrawer
      :drawerExpanded="isDrawerExpanded"
      @toggle="(value) => (isDrawerExpanded = value)"
    />
  </div>
</template>

<script>
import ENavigationDrawer from "@/components/ENavigationDrawer.vue";
import ESearchProducts from "@/components/ESearchProducts.vue";

export default {
  name: "ENavbar",
  components: { ENavigationDrawer, ESearchProducts },
  data() {
    return {
      // Side menu status
      isDrawerExpanded: false,
    };
  },
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
    user() {
      return this.$store.getters.user;
    },
  },
};
</script>
