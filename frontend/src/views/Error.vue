<template>
  <v-row class="mt-12">
    <v-col class="text-center">
      <!-- Title (big 404) -->
      <p id="title-404" class="display-4 font-weight-bold accent--text">404</p>
      <!-- Subtitle: Page not found -->
      <p class="display-1">
        <b>Oops!</b> Pagina non trovata!
      </p>
      <!-- Explanation -->
      <p
        class="title font-weight-regular"
      >La pagina che stai cercando non esiste o al momento non è disponibile.</p>
      <!-- Go to homepage button -->
      <v-btn large color="primary" :to="'/'">Torna alla home</v-btn>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "Error"
};
</script>

<style scoped>
#title-404 {
  letter-spacing: 0.3rem !important;
}
</style>
