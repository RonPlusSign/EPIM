<template>
  <div>
    <v-container>
      <!------------------------------------->
      <!---- Go back to User page button ---->
      <!------------------------------------->
      <v-btn absolute right @click="$router.push('/profilo')" text color="secondary">
        <span class="mt-1">Profilo utente</span>
        <v-icon class="ml-2">mdi-account</v-icon>
      </v-btn>

      <h2 class="mb-6 mt-10">Carrello di {{ user.name }}</h2>
      <!---------------------------->
      <!---- Empty cart message ---->
      <!---------------------------->
      <v-row v-if="products.length === 0" cols="12" justify="center">
        <h2>Nessun prodotto nel carrello.</h2>
      </v-row>

      <!---------------------------->
      <!---- Cart products list ---->
      <!---------------------------->
      <v-row v-else cols="12" justify="center">
        <v-row justify="space-around">
          <v-btn @click="order" relative right color="success" :disabled="products.length === 0">
            Effettua l'ordine
            <v-icon class="ml-2">mdi-cart</v-icon>
          </v-btn>
        </v-row>

        <EProductsList
          :products="products"
          :loading="loading"
          :cartVersion="true"
          @deleted="(id) => removeFromList(id)"
          @selected-quantity-changed="changes => updateSelectedQuantity(changes)"
        />
      </v-row>

      <!------------------------->
      <!---- Ordering dialog ---->
      <!------------------------->
      <v-dialog width="500" v-model="orderDialog">
        <v-card width="500" class="pa-8">
          <v-card-body>
            <v-row v-if="ordering" cols="12">
              <!-- If the order is loading -->
              <v-col
                cols="12"
                align="center"
                class="headline"
              >Sto simulando il pagamento e la gestione dell'ordine...</v-col>
              <v-col cols="12" align="center">
                <!-- Loading effect -->
                <v-progress-circular color="accent" indeterminate class="mt-3" />
              </v-col>
            </v-row>
            <v-row v-else cols="12">
              <!-- If the order is completed -->
              <v-col cols="12" class="headline">Ordine effettuato con successo!</v-col>
              <v-col align="center">
                <v-btn depressed fab dark color="primary">
                  <v-icon dark>mdi-check</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card-body>
        </v-card>
      </v-dialog>
    </v-container>
  </div>
</template>

<script>
import Axios from "axios";
import EProductsList from "@/components/EProductsList";

export default {
  name: "UserCart",
  components: { EProductsList },

  data() {
    return {
      products: [],
      loading: false,
      orderDialog: false,
      ordering: false
    };
  },
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
    user() {
      return this.$store.getters.user;
    }
  },
  created() {
    // Persistent login dialog (if not logged)
    if (!this.logged) this.$store.commit("openLoginDialog", true);
    else this.fetchCart();
  },

  watch: {
    logged(value) {
      // Persistent login dialog (if not logged)
      if (!value) this.$store.commit("openLoginDialog", true);
      // Close login dialog if the user is logged
      // Must do this because the view when created doesn't see logged === true and opens the dialog
      else {
        this.$store.commit("closeLoginDialog");
        this.fetchCart();
      }
    }
  },

  methods: {
    /**
     * Fetch the cart products
     */
    fetchCart() {
      this.loading = true;
      Axios.get(process.env.VUE_APP_API_URL + `user.php?cart`)
        .then(response => {
          this.products = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.error(error);
          this.loading = false;
        });
    },
    /**
     * Remove a product from the list of products
     */
    removeFromList(id) {
      this.products = this.products.filter(product => product.id !== id);
    },
    /**
     * Change the selected quantity of a specific product
     */
    updateSelectedQuantity(updated) {
      this.products = this.products.map(product => {
        if (product.id === updated.id)
          product.selectedQuantity = updated.newQuantity;
      });
    },

    order() {
      this.orderDialog = true;
      this.ordering = true;
      this.products = [];
      Axios.get(process.env.VUE_APP_API_URL + `orders.php?purchase`)
        .then(() => {
          this.ordering = false;
          setTimeout(() => (this.orderDialog = false), 1500);
        })
        .catch(error => {
          console.error(error);
          this.ordering = false;
          this.orderDialog = false;
        });
    }
  }
};
</script>
