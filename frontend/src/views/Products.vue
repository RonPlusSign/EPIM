<template>
  <div>
    <div class="text-center pt-3" v-if="filtersEmpty">
      <h1>Prodotti più venduti</h1>
    </div>
    <EProductsList class="Products" :products="products" />
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
    };
  },
  methods: {
    fetchProducts() {
      Axios.get(process.env.VUE_APP_API_URL + `products.php`, {
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
    },
  },
  computed: {
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
