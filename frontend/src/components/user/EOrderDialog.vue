<template>
  <!------------------------->
  <!---- Ordering dialog ---->
  <!------------------------->
  <v-dialog width="600" v-model="isOpen">
    <v-card width="600">
      <v-card-text class="px-8 pt-3 pb-3">
        <!------------------------------->
        <!------- Loading message ------->
        <!------------------------------->
        <v-row v-if="ordering" cols="12">
          <!-- If the order is loading -->
          <v-col
            cols="12"
            align="center"
            class="headline"
          >Sto simulando il pagamento e l'elaborazione dell'ordine...</v-col>
          <v-col cols="12" align="center">
            <!-- Loading effect -->
            <v-progress-circular color="accent" indeterminate class="mt-3" />
          </v-col>
        </v-row>
        <!----------------------------->
        <!------- Error message ------->
        <!----------------------------->
        <v-row v-else-if="error" cols="12">
          <!-- If the order gone wrong -->
          <v-col cols="12" class="headline">
            <v-alert
              v-model="error"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >Errore durante l'elaborazione dell'ordine.</v-alert>
          </v-col>
        </v-row>
        <!------------------------------->
        <!------- Success message ------->
        <!------------------------------->
        <v-row v-else-if="success" cols="12">
          <!-- If the order is completed -->
          <v-col cols="12" class="headline">Ordine effettuato con successo!</v-col>
          <v-col align="center">
            <v-btn depressed fab dark color="primary">
              <v-icon dark>mdi-check</v-icon>
            </v-btn>
          </v-col>
        </v-row>

        <!------------------------------>
        <!------- Select address ------->
        <!------------------------------>
        <v-row v-else cols="12" class="mt-1">
          <p class="title">Seleziona l'indirizzo di consegna</p>
          <v-col cols="12">
            <!------------------------>
            <!------ Data table ------>
            <!------------------------>
            <v-data-table
              :headers="headers"
              :items="formattedAddress"
              :search="search"
              class="elevation-3"
              :loading="fetchingAddresses"
              loading-text="Caricamento in corso..."
              no-data-text="Nessun indirizzo disponibile!"
              show-select
              single-select
              v-model="selectedAddress"
            >
              <!---------------------------------------------------------------->
              <!------ Header section (title, search box, Add new button) ------>
              <!---------------------------------------------------------------->
              <template v-slot:top>
                <v-row cols="12" align="center" class="px-4 pt-3 pb-1">
                  <v-col cols="12" md="6" class="py-0">
                    <!---- Search box ---->
                    <v-text-field
                      v-model="search"
                      append-icon="mdi-magnify"
                      label="Cerca"
                      single-line
                      hide-details
                      class="mt-0 pt-0"
                    ></v-text-field>
                  </v-col>
                  <v-spacer class="hidden-md-and-down" />
                  <v-col align="right" cols="12" md="6" class="order-first order-md-last py-0">
                    <!-- Go to profile button -->
                    <v-btn
                      :to="'/profilo'"
                      target="_blank"
                      small
                      right
                      outlined
                      color="secondary"
                      class="pl-3 pr-1"
                    >
                      Gestisci i tuoi indirizzi
                      <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
              </template>
            </v-data-table>
          </v-col>
          <!------------------------------------------>
          <!---- Actions: dismiss / confirm order ---->
          <!------------------------------------------>
          <v-col cols="12" class="mt-3 mb-2">
            <v-row>
              <v-btn @click="isOpen = false" color="red" outlined>Annulla</v-btn>
              <v-spacer />
              <v-btn @click="order" color="primary" :disabled="selectedAddress.length === 0">
                Acquista
                <v-icon class="ml-2">mdi-cart</v-icon>
              </v-btn>
            </v-row>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
/*
Example of this component in action:
<EOrderDialog :status="orderDialog" @close="orderDialog = false" @order="products = []" />

Props: 
- "status": controls if the dialog is open or not
Events:
- "close": dialog has been closed
- "order": order was done successfully
*/

import Axios from "axios";

export default {
  name: "EOrderDialog",
  data() {
    return {
      ordering: false,
      error: false,
      success: false,
      addresses: [],

      selectedAddress: [], // v-data-table needs it to be an array, but it will contain just one element
      fetchingAddresses: false,
      search: "",
      headers: [
        // Headers of each column of the table
        {
          // Address column
          text: "Indirizzo",
          align: "start",
          sortable: true,
          value: "address",
          filterable: true
        },
        {
          // Phone number column
          text: "Telefono",
          value: "phoneNumber",
          sortable: true,
          align: "center",
          filterable: true
        }
        // {
        //   // Select address column
        //   text: "Seleziona",
        //   value: "select",
        //   sortable: false,
        //   align: "center",
        //   width: 50,
        //   filterable: false
        // }
      ]
    };
  },

  props: {
    status: {
      type: Boolean,
      required: true
    }
  },

  computed: {
    isOpen: {
      get() {
        return this.status;
      },
      set(open) {
        if (!open) this.$emit("close");
      }
    },

    formattedAddress() {
      let formatted = [];

      this.addresses.forEach(address => {
        formatted.push({
          id: address.id,
          address: `${address.street}, ${address.houseNumber}, ${address.cityName} (${address.postalCode})`,
          phoneNumber: address.phoneNumber
        });
      });

      return formatted;
    }
  },

  created() {
    // Fetch the addresses
    this.fetchingAddresses = true;
    Axios.get(process.env.VUE_APP_API_URL + `user.php?address`)
      .then(response => {
        this.addresses = response.data;
        this.fetchingAddresses = false;
      })
      .catch(err => {
        this.fetchingAddresses = false;
        console.error(err);
      });
  },

  methods: {
    order() {
      this.ordering = true;
      this.products = [];
      Axios.get(process.env.VUE_APP_API_URL + `orders.php`, {
        params: {
          purchase: "",
          address: this.selectedAddress[0].id
        }
      })
        .then(() => {
          setTimeout(() => {
            this.ordering = false;
          }, 300);
          this.$emit("order");
          this.success = true;
          setTimeout(() => (this.isOpen = false), 2500);
        })
        .catch(error => {
          console.error(error);
          this.ordering = false;
          this.error = true;
        });
    }
  },

  watch: {
    error(open) {
      if (!open) this.$emit("close");
    },

    addresses: {
      deep: true,
      handler() {
        if (
          this.selectedAddress.length === 0 &&
          this.formattedAddress.length > 0
        )
          this.selectedAddress.push(this.formattedAddress[0]);
      }
    }
  }
};
</script>

<style>
</style>