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
      <v-btn
        @click="$router.push('/').catch((err) => {})"
        text
        height="44px"
        class="px-0"
      >
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

      <!--------------------->
      <!---- Search bar ----->
      <!--------------------->
      <v-text-field
        align="center"
        light
        v-model="productSearchQuery"
        label="Cerca"
        outlined
        rounded
        solo
        hide-details
        dense
        append-icon="mdi-magnify"
        @click:append="searchProducts"
        @keyup.enter.native="searchProducts"
      />

      <!------------------------------------------------->
      <!---- Product search filters button and menu ----->
      <!------------------------------------------------->
      <EProductsFilterMenu
        @filters-changed="(newFilters) => checkFilters(newFilters)"
        @search="searchProducts()"
      />

      <v-spacer class="hidden-xs-only" />

      <!----------------------------------------------------->
      <!---- User profile / login buttons / cart button ----->
      <!----------------------------------------------------->
      <div class="hidden-xs-only d-flex">
        <div v-if="logged" class="hidden-sm-and-down">
          <!-- User profile button -->
          <v-btn
            @click="$router.replace('/profilo').catch((err) => {})"
            target="_blank"
            class="mt-2"
            text
          >
            <span class="mr-2">Profilo di {{ user.name }}</span>
          </v-btn>
        </div>

        <div v-else>
          <!-- Login button -->
          <v-btn
            target="_blank"
            @click="isLoginDialogActive = true"
            class="px-0 mx-0"
            text
          >
            <h4 class="pt-1">Login</h4>
            <v-icon class="ml-2 hidden-xs-only">mdi-account</v-icon>
          </v-btn>
        </div>

        <!-- Cart button -->
        <v-btn
          v-if="logged"
          @click="$router.replace('/carrello').catch((err) => {})"
          target="_blank"
          icon
        >
          <v-icon>mdi-cart-outline</v-icon>
        </v-btn>
      </div>
    </v-app-bar>
    <!---------------------------->
    <!---- Navigation drawer ----->
    <!---------------------------->
    <ENavigationDrawer
      :drawerExpanded="isDrawerExpanded"
      @toggle="(value) => (isDrawerExpanded = value)"
    />
    <!----------------------->
    <!---- Login dialog ----->
    <!----------------------->
    <ELoginDialog
      :isOpen="isLoginDialogActive"
      @status-changed="
        (value) => {
          this.isLoginDialogActive = value;
        }
      "
    />
  </div>
</template>

<script>
import ELoginDialog from "@/components/ELoginDialog.vue";
import ENavigationDrawer from "@/components/ENavigationDrawer.vue";
import EProductsFilterMenu from "@/components/EProductsFilterMenu.vue";

export default {
  name: "ENavbar",
  components: { ELoginDialog, ENavigationDrawer, EProductsFilterMenu },
  data() {
    return {
      // Login dialog status
      isLoginDialogActive: false,
      // Side menu status
      isDrawerExpanded: false,
      // Research filters
      productSearchQuery: "",
      filtersChanged: false,
      filters: {},
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

  methods: {
    searchProducts() {
      /**
       * Block navigation if route is the same (same search)
       * Also redirects to /prodotti when using searchbar
       */
      if (this.filtersChanged) {
        if (this.productSearchQuery.trim() !== "")
          // Title
          var q = this.productSearchQuery.trim();

        this.$router.push({
          name: "products",
          query: { q, ...this.filters },
        });
      }
      this.filtersChanged = false;
    },
    checkFilters(newFilters) {
      // check if filters are changed
      if (this.filters !== newFilters) {
        this.filtersChanged = true;
        this.filters = newFilters;
      }
    },
  },

  watch: {
    productSearchQuery() {
      this.filtersChanged = true;
    },
  },
  created() {
    if (this.$route.query.q !== undefined)
      this.productSearchQuery = this.$route.query.q;
  },
};
</script>
