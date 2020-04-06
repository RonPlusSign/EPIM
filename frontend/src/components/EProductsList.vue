<template>
  <div class="EProductListItem">
    <template v-if="!isEmpty">
      <EProductListItem v-for="product in products" :product="product" :key="product.id" />
    </template>
    <h2 class="text-center">Nessun prodotto trovato!</h2>
  </div>
</template>

<script>
import EProductListItem from "@/components/EProductListItem";

export default {
  name: "EProductsList",
  components: {
    EProductListItem
  },
  props: {
    products: {
      type: Array,
      required: true
    }
  },
  computed: {
    isEmpty: function() {
      return this.products.length === 0;
    }
  }
};
</script>
