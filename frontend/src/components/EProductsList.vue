<template>
  <v-container>
    <!------------------------->
    <!---- Loading spinner ---->
    <!------------------------->
    <v-row v-if="loading" cols="12" justify="center">
      <v-progress-circular color="accent" indeterminate class="mb-3" />
    </v-row>
    <!----------------------->
    <!---- Product items ---->
    <!----------------------->
    <template v-if="!isEmpty">
      <v-row cols="12" v-for="product in products" :key="product.id">
        <v-spacer class="hidden-xs-only" />

        <v-col xl="6" lg="8" md="9" sm="10" xs="12" align-self="center">
          <!------------------------->
          <!-- Single product item -->
          <!------------------------->
          <EProductListItem
            :product="product"
            :adminVersion="adminVersion"
            @deleted="(productId) => $emit('deleted', productId)"
          />
        </v-col>
        <v-spacer class="hidden-xs-only" />
      </v-row>
    </template>
    <!--------------------------------->
    <!---- No products in the list ---->
    <!--------------------------------->
    <h2 class="text-center" v-else-if="!loading">Nessun prodotto trovato!</h2>
  </v-container>
</template>

<script>
import EProductListItem from "@/components/EProductListItem";

export default {
  name: "EProductsList",
  components: {
    EProductListItem,
  },
  props: {
    products: {
      type: Array,
      required: true,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    adminVersion: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    isEmpty: function () {
      return this.products.length === 0;
    },
  },
};
</script>
