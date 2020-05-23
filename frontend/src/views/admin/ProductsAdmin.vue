<template>
  <div v-if="isAdmin">
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn :to="'/admin'" exact text>
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">torna alla pagina di admin</span>
    </v-btn>

    <br />
    <br />
    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2>Gestione dei prodotti</h2>

    <v-btn
      :to="{ name: 'createProduct' }"
      color="secondary"
      dark
      class="mt-3 mb-6"
    >
      Crea nuovo
      <v-icon class="ml-2">mdi-plus</v-icon>
    </v-btn>

    <v-row class="ml-4 ml-md-0 mr-sm-n12">
      <v-spacer class="hidden-sm-and-down" />
      <!--------------------->
      <!---- Search bar ----->
      <!--------------------->
      <ESearchProducts :redirectRoute="'/admin/prodotti'" />
      <v-spacer class="hidden-sm-and-down" />
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
  </div>
</template>

<script>
import Axios from "axios";
import adminMixin from "@/mixins/adminMixin";

import EProductsList from "@/components/EProductsList";
import ESearchProducts from "@/components/ESearchProducts.vue";

export default {
  name: "ProductsAdmin",
  components: { EProductsList, ESearchProducts },
  mixins: [adminMixin],
  data() {
    return {
      // List of products
      products: [],

      // Loading effect activation
      loading: false,

      // Pages navigation
      numberOfProductsFound: 0,
      productsPerPage: 0,
      selectedPage: 1,
    };
  },

  computed: {
    // Returns the number of pages (total number of results divided by the number of products per page)
    numberOfPages() {
      return Math.ceil(this.numberOfProductsFound / this.productsPerPage);
    },
  },

  methods: {
    // Fetch the products list from the server
    fetchProducts() {
      this.loading = true;

      // If the filters are empty, look for best sellers
      let salesString = this.filtersEmpty ? "?sales&desc" : "";

      Axios.get(process.env.VUE_APP_API_URL + `products.php` + salesString, {
        // Add the filters (query string params)
        params: this.filtersEmpty ? {} : this.$route.query,
      })
        .then((response) => {
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
        .catch((error) => {
          console.error(error);
        });

      this.loading = false;
    },

    /**
     * Remove a product from the list of products
     */
    removeFromList(id) {
      this.products = this.products.filter((product) => product.id !== id);
    },
  },

  watch: {
    $route() {
      this.fetchProducts();
    },

    selectedPage(value) {
      // Empty the products list
      //in that way the page automatically scrolls up and you see the loading effect
      this.products = [];

      value -= 1; // Page index starts from 0, but the first page is shown as page 1

      this.$router.push({
        path: "/prodotti",
        query: {
          ...this.$route.query,
          p: value,
        },
      });
    },
  },
};
</script>
