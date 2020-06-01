<template>
  <div v-if="logged">
    <!------------------------------------->
    <!---- Go back to User page button ---->
    <!------------------------------------->
    <v-btn absolute top right :to="'/profilo'" outlined>
      <span>Profilo utente</span>
      <v-icon class="ml-2">mdi-account</v-icon>
    </v-btn>

    <h2 class="mb-6 mt-10">Carrello di {{ user ? user.name : "" }}</h2>
    <!---------------------------->
    <!---- Empty cart message ---->
    <!---------------------------->
    <v-row v-if="products.length === 0" cols="12" justify="center">
      <h2>Nessun prodotto nel carrello</h2>
    </v-row>

    <!------------------------------------>
    <!---- Order button / total price ---->
    <!------------------------------------>
    <v-row v-else cols="12" justify="center">
      <v-row cols="12" justify="space-around">
        <v-col cols="12" align="center">
          <!-- Order button -->
          <v-btn
            @click="orderDialog = true"
            relative
            right
            color="success"
            :disabled="products.length === 0"
          >
            Effettua l'ordine
            <v-icon class="ml-2">mdi-cart</v-icon>
          </v-btn>
        </v-col>

        <v-col cols="12" align="center" class="pb-0">
          <!-- Total price -->
          <span class="title font-weight-medium">
            Totale:
            <span class="accent--text">
              {{
                Number(totalPrice).toLocaleString("it-IT", {
                  minimumFractionDigits: 2,
                })
              }}
              €
            </span>
          </span>
        </v-col>
      </v-row>

      <!---------------------------->
      <!---- Cart products list ---->
      <!---------------------------->
      <EProductsList
        :products="products"
        :loading="loading"
        :cartVersion="true"
        @deleted="(id) => removeFromList(id)"
        @selected-quantity-changed="
          (changes) => updateSelectedQuantity(changes)
        "
      />
    </v-row>

    <EOrderDialog
      :status="orderDialog"
      @close="orderDialog = false"
      @order="products = []"
    />
  </div>
</template>

<script>
import Axios from "axios";
import userMixin from "@/mixins/userMixin";

import EProductsList from "@/components/EProductsList.vue";
import EOrderDialog from "@/components/user/EOrderDialog.vue";

export default {
  name: "UserCart",
  mixins: [userMixin],
  components: { EProductsList, EOrderDialog },

  data() {
    return {
      products: [],
      loading: false,
      orderDialog: false,
    };
  },

  computed: {
    totalPrice() {
      return this.products.reduce(
        (total, product) =>
          total + product.sell_price * product.selectedQuantity,
        0
      );
    },
  },

  created() {
    if (this.logged) this.fetchCart();
  },

  watch: {
    logged(value) {
      if (value) this.fetchCart();
    },
  },

  methods: {
    /**
     * Fetch the cart products
     */
    fetchCart() {
      this.loading = true;
      Axios.get(process.env.VUE_APP_API_URL + `user.php?cart`)
        .then((response) => {
          this.products = response.data;
          this.loading = false;
        })
        .catch((error) => {
          if (error.response.status === 403)
            this.$store
              .dispatch("checkLogin")
              .catch(this.$store.commit("openLoginDialog", true));
          else console.error(error);
          this.loading = false;
        });
    },

    /**
     * Remove a product from the list of products
     */
    removeFromList(id) {
      this.products = this.products.filter((product) => product.id !== id);
    },
    /**
     * Change the selected quantity of a specific product
     */
    updateSelectedQuantity(updated) {
      this.products = this.products.map((product) => {
        if (product.id === updated.id)
          product.selectedQuantity = updated.newQuantity;
        return product;
      });
    },
  },
};
</script>
