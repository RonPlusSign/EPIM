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
    <EEditProduct :productObject="product" />
  </div>
</template>

<script>
import Axios from "axios";

import adminMixin from "@/mixins/adminMixin";
import EEditProduct from "@/components/admin/EEditProduct.vue";

export default {
  name: "EditProduct",
  mixins: [adminMixin],
  components: { EEditProduct },
  data() {
    return {
      product: null
    };
  },
  created() {
    // Get the product to edit
    Axios.get(process.env.VUE_APP_API_URL + `products.php`, {
      // ID = id: this.$router.history.current.params.id
      params: { id: this.$router.history.current.params.id }
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
