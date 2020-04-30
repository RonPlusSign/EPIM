<!-- Page of a single product info -->
<template>
  <div class="ProductDetail">
    <v-row cols="12">
      <v-col justify="center" align="center" xs="12" sm="12" md="6" lg="5" xl="4">
        <!----------------------------------->
        <!----- Product images carousel ----->
        <!----------------------------------->
        <EImagesCarousel :images="product.images" />
      </v-col>
      <v-col xs="12" sm="12" md="6" lg="7" xl="8">
        <!------ Product info ------>

        <!-- Title -->
        <h3 class="title">{{ product.title }}</h3>
        <!-- Brand -->
        <p class="subtitle-2">
          di
          <a
            @click="
              $router.push({ path: '/prodotti', query: { b: product.brandId } })
            "
          >{{ product.brandName }}</a>
        </p>
        <!-- Price -->
        <p class="subtitle-1">
          Prezzo:
          <span class="font-weight-medium">{{ product.sellPrice }} €</span>
        </p>
        <!-- Category -->
        <p class="body">
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
        <p class="body">Quantità disponibile: {{ product.quantity }}</p>
        <!-- Description -->
        <p class="body">{{ product.description }}</p>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Axios from "axios";
import EImagesCarousel from "@/components/EImagesCarousel.vue";

export default {
  name: "ProductDetail",
  components: { EImagesCarousel },
  data() {
    return {
      product: {
        id: this.$router.history.current.params.id,
        title: "Oh no! Il prodotto non è stato trovato!",
        description:
          "Ci dispiace, questo prodotto non è disponibile su questo sito. Riprova in un altro momento.",
        // list to all the images of a product
        images: [],
        sellPrice: 0,
        quantity: 0, // Product availability
        categoryId: null,
        categoryName: "Nessuna categoria",
        brandId: null,
        brandName: "Nessuna marca"
      }
    };
  },
  created() {
    Axios.get(process.env.VUE_APP_API_URL + `products.php`, {
      params: { id: this.$router.history.current.params.id }
    })
      .then(response => {
        this.product = response.data;
      })
      .catch((/* error */) => {});
  }
};
</script>