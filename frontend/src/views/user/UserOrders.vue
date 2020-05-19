<template>
  <div v-if="logged">
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
import userMixin from "@/mixins/userMixin";

export default {
  name: "UserOrders",
  mixins: [ userMixin ],

  created() {
    if (this.logged) this.fetchOrders();
  },

  watch: {
    logged(value) {
      if (value) this.fetchOrders();
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