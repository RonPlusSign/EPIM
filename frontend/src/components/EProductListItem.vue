<template>
  <v-row>
    <v-col>
      <v-card>
        <v-card-title @click="router.push({ name: 'prodotti', params: { id: product.id } })">{{ product.title }}</v-card-title>
        <v-card-subtitle>{{ product.brand }}</v-card-subtitle>
        <v-card-text>{{ product.sell_price }} €</v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "EProductListItem",
  props: {
    product: {
      type: Object,
      required: true
    }
  }
};
</script>
