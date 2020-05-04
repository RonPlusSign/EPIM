<template>
  <v-footer padless class="primary darken-1 white--text">
    <v-spacer></v-spacer>
    <v-col class="text-center pb-0" cols="12" lg="4" md="6" sm="10" xs="12">
      {{ new Date().getFullYear() }} — <strong>E.P.I.M.</strong> (E-commerce
      platform ITIS Meucci)
      <v-divider dark class="my-2"></v-divider>
      <p class="font-weight-light body-2">
        Developed by: Carletti, Delli, Gaetano, Vannucci (5A-IA)
      </p>
    </v-col>
    <v-spacer></v-spacer>
  </v-footer>
</template>

<script>
export default {
  name: "EFooter",
};
</script>
