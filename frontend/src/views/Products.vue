<template>
  <div class="Products">
    <v-row justify="center" align="center">
      <v-col v-for="i in 20" :key="i" cols="3">
        <v-card>
          <v-card-title>
            Prodotto
          </v-card-title>
          <v-card-text>
            Lorem, ipsum dolor sit amet consectetur adipisicing elit.
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  name: "Products"
};
</script>
