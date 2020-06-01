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

    <EProductCardList :products="products" @change="fetchProducts" />

    <!----------------------------------->
    <!---- Products pages navigation ---->
    <!----------------------------------->
    <v-pagination
      v-if="numberOfPages > 0"
      v-model="selectedPage"
      :length="numberOfPages"
      total-visible="7"
    />

    <v-btn
      class="mx-2 text-capitalize textFab"
      color="secondary"
      style="bottom: 70px; z-index: 4"
      rounded
      fixed
      dark
      right
      bottom
      :to="{ name: 'createProduct' }"
    >
      <v-icon light class="mr-1">mdi-plus</v-icon>Nuovo prodotto
    </v-btn>
  </div>
</template>

<script>
/* eslint-disable */
import Axios from "axios";
import adminMixin from "@/mixins/adminMixin";

// New
import EProductCardList from "@/components/productAdmin/EProductCardList.vue";

export default {
  name: "ProductsAdmin",
  components: {
    EProductCardList
  },
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
      selectedPage: 1
    };
  },

  computed: {
    // Returns the number of pages (total number of results divided by the number of products per page)
    numberOfPages() {
      return Math.ceil(this.numberOfProductsFound / this.productsPerPage);
    }
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
