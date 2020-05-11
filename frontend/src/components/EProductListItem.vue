<!--
Events:
- "deleted" (product has been deleted by an admin, clicking the "Delete" button)
  Admin clicked on "Delete product" button

Props:
- "product" (Object, required): object to display

- "adminVersion" (Boolean, default = False): 
  If true, it shows the delete/edit buttons
  If false, it shows "add to cart" button

Example of a product:

{
  "id": 2,
  "title": "My product",
  "description": "...",
  "imageUrl": "my/image/url", // URL to the first image of a product
  "sellPrice": 43.21,
  "purchasePrice": 30.0, // Only if admin
  "recommendedPrice": 40.0, // Only if admin
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
          <v-img
            contain
            class="ml-3"
            v-else
            src="@/assets/Product Not Found.png"
          ></v-img>
        </v-avatar>
      </v-col>
      <!----------------------->
      <!---- Product data ----->
      <!----------------------->
      <v-col xl="9" lg="8" md="8" sm="7" xs="12">
        <!---------------->
        <!---- Title ----->
        <!---------------->
        <v-card-title
          class="pt-1 productTitle"
          @click="
            $router.push({ name: 'productDetail', params: { id: product.id } })
          "
          >{{ product.title }}</v-card-title
        >

        <!---------------->
        <!---- Brand ----->
        <!---------------->
        <v-card-subtitle class="pb-0">
          di
          <a
            @click="
              $router.push({ path: '/prodotti', query: { b: product.brandId } })
            "
            >{{ product.brandName }}</a
          >
        </v-card-subtitle>
        <!-------------------------->
        <!----- Admin buttons ------>
        <!-------------------------->
        <v-row cols="12" v-if="adminVersion">
          <v-col align="center" xs="12" sm="12" md="6" lg="4" xl="4">
            <!---- Edit button ---->
            <v-btn
              @click="
                $router.push({
                  name: 'editProduct',
                  params: { id: product.id },
                })
              "
              dark
              color="blue"
            >
              Modifica
              <v-icon class="ml-2">mdi-pencil</v-icon>
            </v-btn>
          </v-col>
          <v-col align="center" xs="12" sm="12" md="6" lg="4" xl="4">
            <!---- Delete button ---->
            <v-btn
              @click="deleteProduct(product.id)"
              :loading="deleting"
              dark
              color="red"
            >
              Cancella
              <v-icon class="ml-2">mdi-delete</v-icon>
            </v-btn>
          </v-col>
        </v-row>

        <!---------------------------->
        <!---- Add to cart button ---->
        <!---------------------------->
        <v-btn
          v-if="!adminVersion"
          @click="addToCart(product.id)"
          :loading="addingToCart"
          small
          class="ml-5 mt-2"
          color="primary"
        >
          Aggiungi al carrello
          <v-icon small right dark>mdi-cart</v-icon>
        </v-btn>

        <v-card-text>
          <!---------------->
          <!---- Price ----->
          <!---------------->
          <p v-if="!adminVersion" class="subtitle-1 mb-2">
            Prezzo:
            <span class="font-weight-medium accent--text text--darken-3"
              >{{ product.sellPrice }} €</span
            >
          </p>

          <!----------------------------->
          <!---- Prices (for admins) ---->
          <!----------------------------->
          <div v-if="adminVersion">
            <p class="subtitle-1 mb-2">
              Prezzo di vendita:
              <span class="font-weight-medium accent--text text--darken-3"
                >{{ product.sellPrice }} €</span
              >
            </p>
            <p class="subtitle-1 mb-2">
              Prezzo di acquisto:
              <span class="font-weight-medium accent--text text--darken-3"
                >{{ product.purchasePrice }} €</span
              >
            </p>
            <p class="subtitle-1 mb-2">
              Prezzo consigliato:
              <span class="font-weight-medium accent--text text--darken-3"
                >{{ product.recommendedPrice }} €</span
              >
            </p>
          </div>
          <!------------------>
          <!---- Category ---->
          <!------------------>
          <p class="body mb-2">
            Categoria:
            <a
              @click="
                $router
                  .push({ path: '/prodotti', query: { c: product.categoryId } })
                  .catch((err) => {})
              "
              >{{ product.categoryName }}</a
            >
          </p>
          <!------------------------>
          <!-- Quantity available -->
          <!------------------------>
          <p class="body mb-0">Quantità disponibile: {{ product.quantity }}</p>
        </v-card-text>
      </v-col>
    </v-row>
  </v-card>
</template>

<script>
import Axios from "axios";

export default {
  name: "EProductListItem",
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
  },
  data() {
    return {
      addingToCart: false,
      deleting: false,
    };
  },
  props: {
    product: {
      type: Object,
      required: true,
    },
    adminVersion: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    // Add a product to the cart
    addToCart(id) {
      this.addingToCart = true;
      const task = () => {
        Axios.post(process.env.VUE_APP_API_URL + `user.php?cart`, {
          id: id, // Product id
          quantity: 1, // Default quantity
        }).catch((err) => {
          console.error(err);
        });
      };
      // Check if the user is logged
      if (this.logged) {
        task();
      } else {
        this.$store.commit("openLoginDialog");
        this.$store.commit("setActionAfterLogin", task);
      }
      this.addingToCart = false;
    },

    deleteProduct(id) {
      this.deleting = true;
      Axios.delete(process.env.VUE_APP_API_URL + `products.php`, {
        params: {
          id: id,
        },
      })
        .then(() => {
          this.$emit("deleted", id);
          this.deleting = false;
        })
        .catch((err) => {
          console.error(err);
        });
    },
  },
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
