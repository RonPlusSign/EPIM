<template>
  <div class="EProductCardList">
    <v-row>
      <v-col v-for="product in products" :key="product.id" cols="4">
        <EProductCard :product="product" @delete="deleteProduct" @edit="editProduct"/>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Axios from "axios";
import EProductCard from "@/components/productAdmin/EProductCard.vue";

export default {
  name: "EProductCardList",
  components: {
    EProductCard
  },
  props: {
    products: {
      type: Array,
      required: true
    }
  },
  methods: {
    deleteProduct(e) {
      Axios.delete(process.env.VUE_APP_API_URL + `products.php`, {
        params: {
          id: e
        }
      }).then(() => {
        this.$emit("change");
      });
    },
    editProduct(e) {
      this.$router.push({name: 'editProduct', params: {id: e}});
    }
  }
};
</script>

<style lang="scss" scoped>
</style>