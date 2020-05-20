<template>
  <div>
    <div class="text-center pt-3" v-if="filtersEmpty">
      <!------------------------------>
      <!---- Best sellers (title) ---->
      <!------------------------------>
      <h1>Prodotti più venduti</h1>
    </div>
    <!----------------------->
    <!---- Products list ---->
    <!----------------------->
    <EProductsList :products="products" :loading="loading" />

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
import EProductsList from "@/components/EProductsList";

export default {
  name: "Products",
  components: {
    EProductsList
  },
  data() {
    return {
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
  methods: {
    // Fetch the products list from the server
    fetchProducts() {
      this.loading = true;

      // If the filters are empty, look for best sellers
      let salesString = this.filtersEmpty ? "?sales&desc" : "";

      Axios.get(process.env.VUE_APP_API_URL + `products.php` + salesString, {
        // Add the filters (query string params)
        params: this.filtersEmpty ? {} : this.$route.query
      })
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
    }
  },
  computed: {
    // Check if filters are empty or undefined
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
          p: value
        }
      });
    }
  },
  created() {
    this.fetchProducts();
  }
};
</script>
