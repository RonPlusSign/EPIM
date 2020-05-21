<!-- Cards grid view to show categories and brands list -->
<!--
  Prop "elements" must have this structure:
[
    {
      "id": Number,
      "name": String,
      "type": "category" | "brand", // Used to redirect to the right products search
      "imageUrl": "/url/to/image" // URL to an image of one product of this type
    },
    // ... // Other objects
]
-->

<template>
  <v-container fluid>
    <v-layout cols="12" row wrap>
      <v-flex class="mt-5 mb-3" xs12 sm6 md6 lg4 xl3 v-for="item in elements" :key="item.id">
        <v-card class="mx-auto" max-width="330">
          <v-img v-if="item.imageUrl" :src="item.imageUrl" max-height="200px" />
          <v-img v-else src="@/assets/Product Not Found.png" max-height="200px" contain />
          <v-card-title class="pt-3 pb-1">
            <v-spacer />
            {{item.name}}
            <v-spacer />
          </v-card-title>
          <v-card-actions>
            <v-spacer />
            <v-btn class="mr-2 mb-2" outlined color="accent" :to="routeToProductsWithFilter(item)">
              Esplora
              <v-icon class="ml-2">mdi-arrow-right</v-icon>
            </v-btn>
            <v-spacer />
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  name: "ECardsGrid",

  props: {
    elements: {
      type: Array,
      required: true
    }
  },

  methods: {
    routeToProductsWithFilter(item) {
      var param =
        item.type === "category" ? { c: item.name } : { b: item.name };
      return { path: "/prodotti", query: param };
    }
  }
};
</script>

<style scoped>
</style>