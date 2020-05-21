<template>
  <div v-if="logged">
    <!------------------------------------->
    <!---- Go back to User page button ---->
    <!------------------------------------->
    <v-btn :to="'/profilo'" exact text color="secondary">
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">Torna al profilo utente</span>
    </v-btn>
    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2 class="my-3">Ordini di {{ user ?user.name : "" }}</h2>
    <v-row cols="12" justify="center">
      <v-col v-if="orders.length > 0" xl="8" lg="8" md="10" cols="12">
        <!--------------------->
        <!---- Orders list ---->
        <!--------------------->
        <EOrdersList :orders="orders" />
      </v-col>
      <v-col v-else align="center">
        <h3>Nessun ordine presente!</h3>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Axios from "axios";
import userMixin from "@/mixins/userMixin";
import EOrdersList from "@/components/EOrdersList.vue";

export default {
  name: "UserOrders",
  mixins: [userMixin],
  components: { EOrdersList },

  data() {
    return {
      orders: []
    };
  },

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
      // Get the user orders
      Axios.get(process.env.VUE_APP_API_URL + `orders.php`)
        .then(response => {
          this.orders = response.data;
        })
        .catch(err => {
          console.error(err);
        });
    }
  }
};
</script>