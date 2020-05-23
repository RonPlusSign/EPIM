<!-- 
Search box for products with filters button

props:
- "redirectRoute" (default = "/prodotti"): route to redirect when searching a product
-->

<template>
  <v-row cols="12" align="center" class="d-flex">
    <v-col cols="10" class="pr-0">
      <!--------------------->
      <!---- Search bar ----->
      <!--------------------->
      <v-text-field
        align="center"
        light
        v-model="productSearchQuery"
        label="Cerca"
        solo
        hide-details
        dense
        append-icon="mdi-magnify"
        @click:append="searchProducts"
        @keyup.enter.native="searchProducts"
        class="mr-1 elevation-2"
    /></v-col>

    <v-col cols="2" class="pl-0">
      <!------------------------------------------------->
      <!---- Product search filters button and menu ----->
      <!------------------------------------------------->
      <EProductsFilterMenu
        @filters-changed="(newFilters) => checkFilters(newFilters)"
        @search="searchProducts"
      />
    </v-col>
  </v-row>
</template>

<script>
import EProductsFilterMenu from "@/components/EProductsFilterMenu.vue";

export default {
  name: "ESearchProducts",
  components: { EProductsFilterMenu },
  data() {
    return {
      // Research filters
      filters: {},
      productSearchQuery: "",
      filtersChanged: false,
    };
  },

  props: {
    redirectRoute: {
      type: String,
      default: "/prodotti",
    },
  },

  created() {
    if (this.$route.query.q !== undefined)
      this.productSearchQuery = this.$route.query.q;
  },

  watch: {
    productSearchQuery() {
      this.filtersChanged = true;
    },

    $route() {
      // Title
      if (
        this.$route.query.q !== undefined &&
        this.$route.query.q !== this.productSearchQuery
      )
        this.productSearchQuery = this.$route.query.q;
      else if (this.$route.query.q !== this.productSearchQuery)
        this.productSearchQuery = "";
    },
  },

  methods: {
    checkFilters(newFilters) {
      // check if filters are changed
      if (this.filters !== newFilters) {
        this.filtersChanged = true;
        this.filters = newFilters;
      }
    },

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
          path: this.redirectRoute,
          query: { q, ...this.filters },
        });
      }
      this.filtersChanged = false;
    },
  },
};
</script>
