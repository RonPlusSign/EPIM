<!-- Page of a single product info -->
<template>
  <div class="ProductDetail">
    <v-row cols="12">
      <v-col justify="center" align="center" xs="12" sm="12" md="6" lg="5" xl="4">
        <!-- Product images carousel -->
        <EImagesCarousel :images="product.images" />
      </v-col>
      <v-col xs="12" sm="12" md="6" lg="7" xl="8">
        <!-- Title -->
        <h3 class="title">{{ product.title }}</h3>
        <!-- Brand -->
        <p class="subtitle-2 mb-2">
          di
          <a
            @click="
              $router.push({ path: '/prodotti', query: { b: product.brandId } })
            "
          >{{ product.brandName }}</a>
        </p>
        <!-- Price -->
        <p class="subtitle-1 mb-2">
          Prezzo:
          <span
            class="font-weight-medium accent--text text--darken-3"
          >{{ product.sellPrice }} €</span>
        </p>
        <!-- Category -->
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

        <v-row cols="12">
          <!-- Select quantity input -->
          <v-col xs="12" sm="3" md="4" lg="2" xl="2">
            <ENumberInput
              :value="selectedQuantity"
              :min="0"
              :max="product.quantity"
              :caption="'Quantità'"
              @change="newValue => selectedQuantity = newValue"
            />
          </v-col>
          <!-- Add to cart button -->
          <v-col justify-self="start" align-self="end" xs="12" sm="6" md="8" lg="10" xl="10">
            <v-btn color="success" :disabled="selectedQuantity === 0">
              Aggiungi al carrello
              <v-icon right dark>mdi-cart</v-icon>
            </v-btn>
          </v-col>
        </v-row>

        <!-- Description -->
        <p class="body">{{ product.description }}</p>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Axios from "axios";
import EImagesCarousel from "@/components/EImagesCarousel.vue";
import ENumberInput from "@/components/ENumberInput.vue";

export default {
  name: "ProductDetail",
  components: { EImagesCarousel, ENumberInput },
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
      },
      selectedQuantity: 0
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