<!--
Example of a product:

{
  "id": 2,
  "title": "My product",
  "description": "...",
  "imageUrl": "my/image/url", // URL to the first image of a product
  "price": 43.21, // ONLY sell_price
  "quantity": 23, // Products availability
  "categoryId": 1,
  "categoryName": "Smartphone",
  "brandId": 2,
  "brandName": "Samsung"
}

 -->
<template>
  <v-card>
    <v-row cols="12">
      <!------------------------>
      <!---- Product image ----->
      <!------------------------>
      <v-col xl="3" lg="4" md="4" sm="5" xs="12" align="center">
        <v-avatar class="ml-1" size="200" tile image left>
          <v-img
            contain
            class="ml-3"
            v-if="product.imageUrl && product.imageUrl !== ''"
            :src="product.imageUrl"
          ></v-img>
          <v-img contain class="ml-3" v-else src="@/assets/Product Not Found.png"></v-img>
        </v-avatar>
      </v-col>
      <!----------------------->
      <!---- Product data ----->
      <!----------------------->
      <v-col xl="9" lg="8" md="8" sm="7" xs="12">
        <!---- Title ----->
        <v-card-title
          class="pt-1 productTitle"
          @click="$router.push({ name: 'productDetail', params: { id: product.id } })"
        >{{ product.title }}</v-card-title>

        <!---- Brand ----->
        <v-card-subtitle class="pb-0">
          di
          <a
            @click="
              $router.push({ path: '/prodotti', query: { b: product.brandId } })
            "
          >{{ product.brandName }}</a>
        </v-card-subtitle>

        <!---- Add to cart button ---->
        <v-btn small class="ml-5 mt-2" color="success" :disabled="selectedQuantity === 0">
          Aggiungi al carrello
          <v-icon small right dark>mdi-cart</v-icon>
        </v-btn>

        <v-card-text>
          <!---- Price ----->
          <p class="subtitle-1 mb-2">
            Prezzo:
            <span
              class="font-weight-medium orange--text text--darken-3"
            >{{ product.price }} €</span>
          </p>
          <!---- Category ---->
          <p class="body mb-2">
            Categoria:
            <a
              @click="
              $router
                .push({ path: '/prodotti', query: { c: product.categoryId } })
                .catch((err) => {})
            "
            >{{ product.categoryName }}</a>
          </p>
          <!-- Quantity available -->
          <p class="body mb-0">Quantità disponibile: {{ product.quantity }}</p>
        </v-card-text>
      </v-col>
    </v-row>
  </v-card>
</template>

<script>
export default {
  name: "EProductListItem",
  props: {
    product: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>
.productTitle {
  cursor: pointer;
}

.productTitle:hover {
  color: darkgreen;
  transition: 0.3s;
}
</style>