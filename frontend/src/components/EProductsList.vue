<template>
  <div v-if="!isEmpty">
    <product-list-item v-for="product in this.products" :product="product" :key="product.id"></product-list-item>
  </div>
  <div v-else>
    <h2 class="text-center">Nessun prodotto trovato!</h2>
  </div>
</template>

<script>
import EProductListItem from "../components/EProductListItem";

export default {
  name: "EProductsList",

  props: ["products"],

  data() {
    return {
      products: []
    };
  },

  computed: {
    isEmpty: function() {
      return this.products === undefined || this.products.length === 0;
    }
  },

  components: {
    "product-list-item": productListItem
  }
};
</script>
