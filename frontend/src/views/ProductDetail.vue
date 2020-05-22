<!-- Page of a single product info -->
<template>
  <div>
    <v-row cols="12">
      <v-col cols="1" class="hidden-md-and-down">
        <v-spacer />
      </v-col>
      <v-col justify="center" align="center" cols="12" md="6" lg="5" xl="4">
        <!-- Product images carousel -->
        <EImagesCarousel :images="product.images ? product.images : []" />
      </v-col>
      <v-col cols="12" md="6" lg="6" xl="7">
        <!-- Title -->
        <h3 class="title">{{ product.title }}</h3>
        <!-- Brand -->
        <p class="subtitle-2 mb-2">
          di
          <router-link :to="{ path: '/prodotti', query: { b: product.brand } }">{{ product.brand }}</router-link>
        </p>
        <!-- Price -->
        <p class="subtitle-1 mb-2">
          Prezzo:
          <span class="font-weight-medium accent--text">{{ product.sell_price }} €</span>
        </p>
        <!-- Category -->
        <p class="body mb-2">
          Categoria:
          <router-link
            :to="{ path: '/prodotti', query: { c: product.category } }"
          >{{ product.category }}</router-link>
        </p>
        <!-- Quantity available -->
        <p class="body mb-0">Quantità disponibile: {{ product.quantity }}</p>

        <v-row cols="12">
          <!-- Select quantity input -->
          <v-col cols="12" sm="4" md="4" lg="4" xl="3">
            <ENumberInput
              :value="selectedQuantity"
              :min="0"
              :max="product.quantity"
              :caption="'Quantità'"
              @change="(newValue) => (selectedQuantity = newValue)"
            />
          </v-col>
          <!-- Add to cart button -->
          <v-col justify-self="start" align-self="end" cols="12" sm="6" md="8" lg="8" xl="9">
            <v-btn
              color="success"
              @click="addToCart(product.id, selectedQuantity)"
              :disabled="selectedQuantity === 0"
            >
              Aggiungi al carrello
              <v-icon right dark>mdi-cart</v-icon>
            </v-btn>
          </v-col>
        </v-row>

        <!-- Description -->
        <p class="body subtitle-1 mt-2 mb-1">Descrizione:</p>
        <p class="body px-3">{{ product.description }}</p>
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
          "Ci dispiace, questo prodotto non è disponibile al momento. Riprova in un altro momento.",
        // list to all the images of a product
        images: [],
        sell_price: 0,
        quantity: 0, // Product availability
        category_id: null,
        category: "Nessuna categoria",
        brand_id: null,
        brand: "Nessuna marca"
      },
      selectedQuantity: 0
    };
  },
  computed: {
    logged() {
      return this.$store.getters.logged;
    }
  },
  created() {
    Axios.get(process.env.VUE_APP_API_URL + `products.php`, {
      params: { id: this.$router.history.current.params.id }
    })
      .then(response => {
        this.product = response.data;
      })
      .catch((/* error */) => {});
  },
  methods: {
    // Add a product to the cart
    addToCart(id, quantity) {
      const task = () => {
        Axios.post(process.env.VUE_APP_API_URL + `user.php?cart`, {
          id: id, // Product id
          quantity: quantity // Default quantity
        }).catch(err => {
          console.error(err);
        });
      };
      // Check if the user is logged
      if (this.logged) {
        // If the user is logged, perform the request to add the product
        task();
      } else {
        // If the user is NOT logged, open the login dialog (and then, if the login is ok, run the task)
        this.$store.commit("openLoginDialog");
        this.$store.commit("setActionAfterLogin", task);
      }
    }
  }
};
</script>
