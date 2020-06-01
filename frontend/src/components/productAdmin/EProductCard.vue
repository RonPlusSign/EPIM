<template>
  <v-card class="mx-auto EProductCard" height="100%">
    <v-img
      class="white--text align-end"
      height="200px"
      :src="product.images[0]"
      contain
      alt="Immagine Prodotto"
    ></v-img>

    <v-card-title style="height: 9em;" class="mb-3 mb-xl-0">{{product.title}}</v-card-title>

    <v-card-subtitle class="pb-0">
      di {{product.brand}} (categoria
      {{product.category}})
    </v-card-subtitle>

    <v-card-text class="text--primary pt-4">
      <v-simple-table>
        <thead>
          <tr>
            <th class="text-center">Prezzo vendita</th>
            <th class="text-center">Prezzo consigliato</th>
            <th class="text-center">Prezzo acquisto</th>
            <th class="text-center">Quantità disponibile</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-center">€{{ product.sell_price }}</td>
            <td class="text-center">€{{ product.recommended_price }}</td>
            <td class="text-center">€{{ product.purchase_price }}</td>
            <td class="text-center">{{ product.quantity }}</td>
          </tr>
        </tbody>
      </v-simple-table>

      <!-- <hr /> -->
    </v-card-text>

    <v-card-actions>
      <v-spacer></v-spacer>

      <v-btn color="green" text @click="$emit('edit', product.id)">
        Modifica
        <v-icon>mdi-pencil</v-icon>
      </v-btn>

      <v-btn color="red" text @click="$emit('delete', product.id)">
        elimina
        <v-icon>mdi-delete</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: "EProductCard",
  data() {
    return {
      images: [
        "https://images-na.ssl-images-amazon.com/images/I/71rmiBKEqdL._AC_SX679_.jpg",
        "https://images-na.ssl-images-amazon.com/images/I/6185Fusp4JL._AC_SX522_.jpg"
      ]
    };
  },
  props: {
    product: {
      type: Object,
      required: true
    }
  }
};
</script>

<style lang="scss" scoped>
.v-card__title {
  word-break: normal !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}
</style>