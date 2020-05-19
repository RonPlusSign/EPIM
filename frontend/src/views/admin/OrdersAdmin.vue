<template>
  <div v-if="logged">
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn @click="$router.push('/admin')" text color="secondary">
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">torna alla pagina di admin</span>
    </v-btn>

    <br />
    <br />
    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2 class="pb-6">Gestione degli ordini</h2>

    <v-row cols="12" justify="center">
      <v-col xl="8" lg="8" md="10" sm="12" xs="12">
        <!--------------------->
        <!---- Orders list ---->
        <!--------------------->
        <EOrdersList :orders="orders" :isAdmin="true" />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import Axios from "axios";
import adminMixin from "@/mixins/adminMixin";
import EOrdersList from "@/components/EOrdersList.vue";

export default {
  name: "OrdersAdmin",
  mixins: [adminMixin],
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
      Axios.get(process.env.VUE_APP_API_URL + `orders.php?admin`)
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
