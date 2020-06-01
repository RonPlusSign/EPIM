<template>
  <div v-if="isAdmin">
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn :to="'/admin/prodotti'" exact text>
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">torna alla pagina dei prodotti</span>
    </v-btn>

    <v-spacer class="my-3" />

    <!---------------------------------->
    <!---- "Edit product" component ---->
    <!---------------------------------->
    <ENewProduct :product="product" />
  </div>
</template>

<script>
import Axios from "axios";

import adminMixin from "@/mixins/adminMixin";
import ENewProduct from "@/components/productAdmin/ENewProduct.vue";

export default {
  name: "EditProduct",
  mixins: [adminMixin],
  components: { ENewProduct },
  data() {
    return {
      product: {}
    };
  },
  created() {
    // Get the product to edit
    Axios.get(process.env.VUE_APP_API_URL + `products.php`, {
      params: { id: this.$route.params.id }
    })
      .then(response => {
        this.product = response.data;
      })
      .catch(err => {
        console.error(err);
      });
  }
};
</script>
