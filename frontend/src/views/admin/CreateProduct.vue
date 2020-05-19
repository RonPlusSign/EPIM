<template>
  <div>
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn @click="$router.push('/admin/prodotti')" text color="secondary">
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">torna alla pagina dei prodotti</span>
    </v-btn>

    <v-spacer class="my-3" />

    <!---------------------------------------->
    <!------- "Edit product" component ------->
    <!--- Without parameters = new product --->
    <!---------------------------------------->
    <EEditProduct />
  </div>
</template>

<script>
import adminMixin from "@/mixins/adminMixin";
import EEditProduct from "@/components/admin/EEditProduct.vue";

export default {
  name: "CreateProduct",
  mixins: [adminMixin],
  components: { EEditProduct }
};
</script>
