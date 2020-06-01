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

    <!---------------------------------------->
    <!------- "Edit product" component ------->
    <!--- Without parameters = new product --->
    <!---------------------------------------->
    <ENewProduct />
  </div>
</template>

<script>
import adminMixin from "@/mixins/adminMixin";
import ENewProduct from "@/components/productAdmin/ENewProduct.vue";

export default {
  name: "CreateProduct",
  mixins: [adminMixin],
  components: { ENewProduct }
};
</script>
