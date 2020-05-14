<template>
  <v-container>
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn @click="$router.push('/admin')" text color="secondary">
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">torna alla pagina di admin</span>
    </v-btn>

    <br />
    <br />
    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2>Gestione dei prodotti</h2>

    <v-btn @click="$router.push({ name: 'createProduct' })" color="blue" dark class="my-3">
      Crea nuovo
      <v-icon class="ml-2">mdi-plus</v-icon>
    </v-btn>

    <v-row cols="12">
      <v-col cols="11" class="pr-0">
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
      </v-col>
      <v-col cols="1" class="pl-0 mt-1 align-left">
        <!------------------------------------------------->
        <!---- Product search filters button and menu ----->
        <!------------------------------------------------->
        <EProductsFilterMenu
          @filters-changed="(newFilters) => checkFilters(newFilters)"
          @search="searchProducts()"
        />
      </v-col>
    </v-row>

    <!----------------------->
    <!---- Products list ---->
    <!----------------------->
    <EProductsList
      :products="products"
      :loading="loading"
      :adminVersion="true"
      @deleted="(id) => removeFromList(id)"
    />

    <!----------------------------------->
    <!---- Products pages navigation ---->
    <!----------------------------------->
    <v-pagination
      v-if="numberOfPages > 0"
      v-model="selectedPage"
      :length="numberOfPages"
      total-visible="7"
    />
  </v-container>
</template>

<script>
import Axios from "axios";
import adminMixin from "@/mixins/adminMixin";

import EProductsList from "@/components/EProductsList";
import EProductsFilterMenu from "@/components/EProductsFilterMenu.vue";

export default {
  name: "ProductsAdmin",
  components: { EProductsFilterMenu, EProductsList },
  mixins: [adminMixin],
  data() {
    return {
      productSearchQuery: "",
      filtersChanged: false,
      filters: {},

      // List of products
      products: [],

      // Loading effect activation
      loading: false,

      // Pages navigation
      numberOfProductsFound: 0,
      productsPerPage: 0,
      selectedPage: 1
    };
  },

  computed: {
    filtersEmpty() {
      let isEmpty = true;
      Object.entries(this.$route.query).forEach(([, value]) => {
        if (value !== undefined) isEmpty = false;
      });
      return isEmpty;
    },

    // Returns the number of pages (total number of results divided by the number of products per page)
    numberOfPages() {
      return Math.ceil(this.numberOfProductsFound / this.productsPerPage);
    }
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
          name: "productsAdmin",
          query: { q, ...this.filters }
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

    // Fetch the products list from the server
    fetchProducts() {
      this.loading = true;

      // If the filters are empty, look for best sellers
      let salesString = this.filtersEmpty ? "?sales&desc" : "";

      Axios.get(
        process.env.VUE_APP_API_URL + `products.php` + salesString,
        {
          // Add the filters (query string params)
          params: this.filtersEmpty ? {} : this.$route.query
        }
      )
        .then(response => {
          // Parse the response from the server
          /*
          Response format:
          {
            totalResults: 123,
            productsPerPage: 16,
            data: [
              // Products list
            ]
          }
          */

          this.products = response.data.data ? response.data.data : [];
          this.numberOfProductsFound = response.data.totalResults;
          this.productsPerPage = response.data.productsPerPage;
        })
        .catch(error => {
          console.error(error);
        });

      this.loading = false;
    },

    /**
     * Remove a product from the list of products
     */
    removeFromList(id) {
      this.products = this.products.filter(product => product.id !== id);
    }
  },

  watch: {
    productSearchQuery() {
      this.filtersChanged = true;
    },

    $route() {
      this.fetchProducts();
    },
    selectedPage(value) {
      // Empty the products list
      //in that way the page automatically scrolls up and you see the loading effect
      this.products = [];

      value -= 1; // Page index starts from 0, but the first page is shown as page 1

      this.$router.replace({
        path: "/prodotti",
        query: {
          ...this.$route.query,
          p: value
        }
      });
    }
  },

  created() {
    if (this.$route.query.q !== undefined)
      this.productSearchQuery = this.$route.query.q;
    this.fetchProducts();
  }
};
</script>
