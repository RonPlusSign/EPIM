<template>
  <v-expansion-panels popout hover focusable>
    <v-expansion-panel v-for="order in orders" :key="order.id">
      <v-expansion-panel-header>
        <!-------------------------------------------------->
        <!---- Header of each row (visible when closed) ---->
        <!-------------------------------------------------->
        <v-row no-gutters align="center">
          <!---- Date ---->
          <v-col cols="4">{{order.date}}</v-col>
          <v-col cols="4" align="center">
            <!---- Order status ---->
            <v-chip class="accent">{{order.status}}</v-chip>
          </v-col>
          <v-col cols="4" align="center">
            <!---- Total price ---->
            <v-chip class="pr-4">
              {{orderTotalPrice(order.id)}}
              <v-icon small color="secondary" right>mdi-currency-eur</v-icon>
            </v-chip>
          </v-col>
        </v-row>
      </v-expansion-panel-header>
      <!--------------------------------->
      <!---- Expansion panel content ---->
      <!--------------------------------->
      <v-expansion-panel-content class="pt-4">
        <p class="text-weight-bold">Informazioni sull'ordine</p>
        <v-simple-table>
          <template v-slot:default>
            <tbody>
              <tr>
                <!-- Order id -->
                <td>ID dell'ordine</td>
                <td>{{order.id}}</td>
              </tr>
              <tr v-if="isAdmin">
                <!-- User id -->
                <td>ID utente</td>
                <td>{{order.user.user_id}}</td>
              </tr>
              <tr v-if="isAdmin">
                <!-- User name and surname -->
                <td>Acquirente</td>
                <td>{{order.user.name + " " + order.user.surname}}</td>
              </tr>
              <tr v-if="isAdmin">
                <!-- User email -->
                <td>E-mail acquirente</td>
                <td>{{order.user.email}}</td>
              </tr>
              <tr>
                <!-- Order date -->
                <td>Data dell'ordinazione</td>
                <td>{{order.date}}</td>
              </tr>
              <tr>
                <!-- Order hour -->
                <td>Orario del pagamento</td>
                <td>{{order.hour}}</td>
              </tr>

              <tr>
                <!-- Address -->
                <td>Indirizzo di consegna</td>
                <td>{{order.address}}</td>
              </tr>
              <tr>
                <!-- Phone number -->
                <td>Numero di telefono</td>
                <td>{{order.phoneNumber}}</td>
              </tr>
              <tr>
                <!-- Shipping cost -->
                <td>Costo della spedizione</td>
                <td>{{Number(order.shippingCost).toLocaleString("it-IT", {minimumFractionDigits: 2})}} €</td>
              </tr>
              <tr>
                <!-- Number of items bought (quantity of all the products) -->
                <td>Numero di articoli acquistati</td>
                <td>
                  {{order.products.reduce(
                  (accumulator, product) =>
                  accumulator + product.quantity,
                  0
                  )}}
                </td>
              </tr>
              <tr></tr>
            </tbody>
          </template>
        </v-simple-table>
        <div>
          <!-------------------------->
          <!---- List of products ---->
          <!-------------------------->
          <v-expansion-panels class="mt-6">
            <v-expansion-panel>
              <v-expansion-panel-header>Prodotti acquistati</v-expansion-panel-header>
              <v-expansion-panel-content>
                <!---- Card representing a single product ---->
                <v-card
                  class="my-5"
                  flat
                  outlined
                  v-for="product in order.products"
                  :key="product.title"
                >
                  <v-card-title class="subtitle-1 font-weight-bold">
                    <!-- Product title -->
                    <router-link :to="'/prodotti/' + product.productId">{{product.productTitle}}</router-link>
                  </v-card-title>
                  <v-card-text>
                    <v-simple-table>
                      <tbody>
                        <tr>
                          <!---- Product id ---->
                          <td>ID prodotto</td>
                          <td>{{product.productId ? product.productId : "Non disponibile"}}</td>
                        </tr>
                        <tr>
                          <!---- Brand ---->
                          <td>Marca</td>
                          <td>{{product.brandName}}</td>
                        </tr>
                        <tr>
                          <!---- Purchased quantity ---->
                          <td>Quantità acquistata</td>
                          <td>{{product.quantity}}</td>
                        </tr>
                        <tr>
                          <!---- Price for each product ---->
                          <td>Prezzo per singolo articolo</td>
                          <td>{{product.price}}</td>
                        </tr>
                      </tbody>
                    </v-simple-table>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </div>
      </v-expansion-panel-content>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script>
import userMixin from "@/mixins/userMixin";

export default {
  name: "EOrdersList",

  mixins: [userMixin],

  data: () => ({}),

  props: {
    orders: {
      type: Array,
      required: true
    },
    isAdmin: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    orderTotalPrice(id) {
      // Get the order with that id
      let order = this.orders.filter(order => order.id === id)[0];

      // Calculate the total cost (shipping cost + price of each product * purchased quantity)
      let price = order.shippingCost;

      price += order.products.reduce(
        (accumulator, product) =>
          accumulator + product.price * product.quantity,
        0
      );

      // Convert the number to Italian format
      return Number(price).toLocaleString("it-IT", {
        minimumFractionDigits: 2
      });
    }
  }
};
</script>
