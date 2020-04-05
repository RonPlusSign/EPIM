<template>
  <v-row>
    <v-card>
      <v-card-title>{{ this.product.title }}</v-card-title>
      <v-card-subtitle>{{ this.product.brand }}</v-card-subtitle>
      <v-card-text>{{ this.product.price }} €</v-card-text>
    </v-card>
    <v-divider></v-divider>
  </v-row>
</template>

<script>
export default {
  name: "EProductListItem",

  props: ["product"],

  data() {
    return {
      product: null
    };
  }
};
</script>
