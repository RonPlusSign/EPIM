<template>
  <div>
    <!------------------------------------->
    <!---- Go back to User page button ---->
    <!------------------------------------->
    <v-btn @click="$router.push('/profilo')" text color="secondary">
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">Torna al profilo utente</span>
    </v-btn>
    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2 class="my-3">Ordini di {{ user.name }}</h2>
    <h4 class="subtitle-1">Stiamo lavorando per aggiungere questa funzione! Abbi pazienza...</h4>
  </div>
</template>

<script>
import Axios from "axios";

export default {
  name: "UserOrders",
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
    else {
      this.fetchOrders();
    }
  },

  watch: {
    logged(value) {
      // Persistent login dialog (if not logged)
      if (!value) this.$store.commit("openLoginDialog", true);
      // Close login dialog if the user is logged
      // Must do this because the view when created doesn't see logged === true and opens the dialog
      else {
        this.$store.commit("closeLoginDialog");
        this.fetchOrders();
      }
    }
  },

  methods: {
    fetchOrders() {
      // TODO: Fetch user orders
      Axios;
    }
  }
};
</script>