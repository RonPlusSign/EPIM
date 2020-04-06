<template>
  <EProductsList :products="products" v-if="$route.query.q" />
  <div class="text-center pt-3" v-else>
    <h1>BEST SELLER...</h1>
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
  },
  watch: {
    $route() {
      if (this.$route.query.q) {
        this.fetchProducts();
      }
    }
  }

  // created() {
  //
  // }
};
</script>
