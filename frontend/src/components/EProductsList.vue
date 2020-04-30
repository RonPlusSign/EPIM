<template>
  <v-container>
    <template v-if="!isEmpty">
      <v-row cols="12" v-for="product in products" :key="product.id">
        <v-spacer class="hidden-xs-only" />

        <v-col xl="6" lg="8" md="9" sm="10" xs="12" align-self="center">
          <EProductListItem :product="product" />
        </v-col>
        <v-spacer class="hidden-xs-only" />
      </v-row>
    </template>
    <h2 class="text-center" v-else>Nessun prodotto trovato!</h2>
  </v-container>
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
