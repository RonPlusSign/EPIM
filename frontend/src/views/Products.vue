<template>
  <EProductsList :products="products" v-if="$route.query.q" />
  <div class="text-center pt-3" v-else>
    <h1>Prodotti più venduti</h1>
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
      products: []
    };
  },
  methods: {
    fetchProducts() {
      if (this.$route.query.q) {
        Axios.get(process.env.VUE_APP_API_URL + `products.php`, {
          params: { q: this.$route.query.q }
        })
          .then(response => {
            console.log();
            this.products = response.data.data;
          })
          .catch(error => {
            console.error(error);
          });
      }
    }
  },
  watch: {
    $route() {
      console.log("$routed changed");
      this.fetchProducts();
    }
  },
  created() {
    this.fetchProducts();
  }
};
</script>
