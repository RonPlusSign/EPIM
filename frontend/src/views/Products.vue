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
    <EProductsList class="Products" :products="products" :loading="loading" />
  </div>
</template>

<script>
import Axios from "axios";
import EProductsList from "@/components/EProductsList";

export default {
  name: "Products",
  components: {
    EProductsList,
  },
  data() {
    return {
      products: [],
      loading: false,
    };
  },
  methods: {
    // Fetch the products list from the server
    fetchProducts() {
      this.loading = true;

      Axios.get(process.env.VUE_APP_API_URL + `products.php`, {
        // Add the filters (query string params)
        params: this.filtersEmpty
          ? { sort: "sales", desc: null }
          : this.$route.query,
      })
        .then((response) => {
          if (response.data.data) this.products = response.data.data;
          else this.products = [];
        })
        .catch((error) => {
          console.error(error);
        });

      this.loading = false;
    },
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
  },
  watch: {
    $route() {
      this.fetchProducts();
    },
  },
  created() {
    this.fetchProducts();
  },
};
</script>
