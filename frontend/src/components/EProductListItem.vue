<!--
Events:
- "deleted" (product has been deleted by an admin, clicking the "Delete" button)
  Returns the id of the deleted product

- "selected-quantity-changed" (From the cart, the user changed the selected quantity and the value has been sent to the server)
  Returns an object like this:
  { id: 12, selectedQuantity: 3 }

Props:
- "product" (Object, required): object to display

- "adminVersion" (Boolean, default = False): 
  If true, it shows the delete/edit buttons

- "cartVersion" (Boolean, default = False): 
  If true, it shows the delete/selectedQuantity actions

Example of a product:

{
  "id": 2,
  "title": "My product",
  "description": "...",
  "imageUrl": "my/image/url", // URL to the first image of a product
  "sell_price": 43.21,
  "purchase_price": 30.0, // Only if admin
  "recommended_price": 40.0, // Only if admin
  "quantity": 23, // Products availability
  "category_id": 1,
  "category": "Smartphone",
  "brand_id": 2,
  "brand": "Samsung"
}

If cartVersion is true, the product will also have a "selectedQuantity" attribute

 -->
<template>
  <v-card :loading="deleting" class="product-card">
    <v-row cols="12">
      <!------------------------>
      <!---- Product image ----->
      <!------------------------>
      <v-col xl="3" lg="4" md="4" sm="5" cols="12" align="center">
        <router-link
          :to="{ name: 'productDetail', params: { id: product.id } }"
        >
          <v-avatar class="ml-1 productPreview" size="200" tile image left>
            <v-img
              contain
              class="ml-3"
              v-if="product.images[0] && product.images[0] !== ''"
              :src="product.images[0]"
            />
            <v-img
              contain
              class="ml-3"
              v-else
              src="@/assets/Product Not Found.png"
            />
          </v-avatar>
        </router-link>
      </v-col>
      <!----------------------->
      <!---- Product data ----->
      <!----------------------->
      <v-col xl="9" lg="8" md="8" sm="7" cols="12">
        <!---------------->
        <!---- Title ----->
        <!---------------->
        <v-card-title class="pt-1 productTitle">
          <router-link
            tag="span"
            :to="{ name: 'productDetail', params: { id: product.id } }"
          >
            {{ product.title }}
          </router-link>
        </v-card-title>

        <!---------------->
        <!---- Brand ----->
        <!---------------->
        <v-card-subtitle class="pb-0">
          di
          <router-link
            :to="{ path: '/prodotti', query: { b: product.brand } }"
            >{{ product.brand }}</router-link
          >
        </v-card-subtitle>
        <!-------------------------->
        <!----- Admin buttons ------>
        <!-------------------------->
        <v-row cols="12" v-if="adminVersion">
          <v-col align="center" cols="12" sm="12" md="6" lg="4" xl="4">
            <!---- Edit button ---->
            <v-btn
              :to="{ name: 'editProduct', params: { id: product.id } }"
              dark
              color="secondary lighten-1"
            >
              Modifica
              <v-icon class="ml-2">mdi-pencil</v-icon>
            </v-btn>
          </v-col>
          <v-col align="center" cols="12" sm="12" md="6" lg="4" xl="4">
            <!---- Delete button ---->
            <v-btn
              @click="deleteProduct(product.id)"
              :loading="deleting"
              dark
              color="red lighten-1"
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
          v-if="!adminVersion && !cartVersion"
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
            <span class="font-weight-medium accent--text"
              >{{ product.sell_price }} €</span
            >
          </p>

          <!----------------------------->
          <!---- Prices (for admins) ---->
          <!----------------------------->
          <div v-if="adminVersion">
            <p class="subtitle-1 mb-2">
              Prezzo di vendita:
              <span class="font-weight-medium accent--text"
                >{{ product.sell_price }} €</span
              >
            </p>
            <p class="subtitle-1 mb-2">
              Prezzo di acquisto:
              <span class="font-weight-medium accent--text"
                >{{ product.purchase_price }} €</span
              >
            </p>
            <p class="subtitle-1 mb-2">
              Prezzo consigliato:
              <span class="font-weight-medium accent--text"
                >{{ product.recommended_price }} €</span
              >
            </p>
          </div>

          <!------------------------->
          <!----- Cart actions ------>
          <!------------------------->
          <v-row cols="12" align="center" v-if="cartVersion">
            <v-col
              align="center"
              cols="12"
              sm="12"
              md="12"
              lg="4"
              xl="4"
              class="pt-0"
            >
              <!---- Selected quantity ---->
              <ENumberInput
                :value="product.selectedQuantity"
                :min="1"
                :max="product.quantity"
                :caption="'Quantità'"
                @change="(newValue) => updateCartQuantity(newValue)"
              />
            </v-col>
            <v-col
              align="center"
              cols="12"
              sm="12"
              md="12"
              lg="8"
              xl="8"
              class="pt-0"
            >
              <!---- Delete button ---->
              <v-btn
                @click="deleteFromCart(product.id)"
                :loading="deleting"
                dark
                small
                color="red lighten-1"
              >
                Rimuovi dal carrello
                <v-icon class="ml-2">mdi-delete</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <!------------------>
          <!---- Category ---->
          <!------------------>
          <p class="body mb-2">
            Categoria:
            <router-link
              :to="{ path: '/prodotti', query: { c: product.category } }"
              >{{ product.category }}</router-link
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
import ENumberInput from "@/components/ENumberInput.vue";

export default {
  name: "EProductListItem",
  components: { ENumberInput },
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
    cartVersion: {
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

    // Delete an item from the cart
    deleteFromCart(id) {
      this.deleting = true;
      Axios.delete(process.env.VUE_APP_API_URL + `user.php?cart`, {
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

    // Change the selected quantity of a product. If the new quantity is 0, remove the product from the cart
    updateCartQuantity(newQuantity) {
      if (this.product.selectedQuantity !== newQuantity) {
        if (newQuantity === 0) {
          // If the quantity is 0, remove the item from the cart
          this.deleteFromCart(this.product.id);
        } else {
          // Request to change the selected quantity on server side
          Axios.patch(process.env.VUE_APP_API_URL + `user.php?cart`, {
            id: this.product.id,
            quantity: newQuantity,
          })
            .then(() => {
              this.$emit("selected-quantity-changed", {
                id: this.product.id,
                newQuantity: newQuantity,
              });
            })
            .catch((err) => {
              console.error(err);
            });
        }
      }
    },
  },
};
</script>

<style scoped>
.product-card:hover {
  box-shadow: 0 6px 7px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12),
    0 2px 4px -1px rgba(0, 0, 0, 0.2);
  transition: 0.15s;
}

.productTitle,
.productPreview {
  cursor: pointer;
}

.productTitle {
  word-break: break-word;
}

.product-card:hover .productTitle {
  transform: translateX(20px);
  color: #6b00b6;
  transition: 0.3s;
}

.productTitle:not(:hover) {
  transition: 0.3s;
}
</style>
