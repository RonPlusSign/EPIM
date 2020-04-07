<template>
  <v-app-bar app color="primary" class="align-content-center" dark>
    <!--------------->
    <!---- Logo ----->
    <!--------------->
    <div class="d-flex align-center">
      <v-img
        alt="EPIM Logo"
        class="shrink mr-2"
        contain
        src="https://www.freepnglogos.com/uploads/letter-e-design-logo-png-27.png"
        transition="scale-transition"
        width="40"
      />

      <h1 class="hidden-sm-and-down">EPIM</h1>
    </div>

    <v-spacer></v-spacer>

    <!--------------------->
    <!---- Search bar ----->
    <!--------------------->
    <v-text-field
      align="center"
      light
      v-model="productSearchQuery"
      label="Cerca un prodotto..."
      outlined
      rounded
      solo
      hide-details
      dense
      append-icon="mdi-magnify"
      @click:append="searchProduct"
      @keyup.enter.native="searchProduct"
    ></v-text-field>

    <v-spacer></v-spacer>

    <!--------------------------------------->
    <!---- User profile / login buttons ----->
    <!--------------------------------------->
    <v-btn v-if="logged" href target="_blank" text>
      <span class="mr-2">Benvenuto, {{ user.name }}</span>
    </v-btn>
    <div v-else>
      <v-btn href target="_blank" text>Login</v-btn>
    </div>
  </v-app-bar>
</template>



<script>
export default {
  name: "ENavbar",
  data() {
    return {
      logged: false,
      user: {
        // TODO: make a request to login.php to see if the user is logged (and retrieve its data)
        name: "",
        id: null
      },
      productSearchQuery: ""
    };
  },
  methods: {
    searchProduct() {
      /**
       * Block navigation if route is the same (same search) or search is empty string
       */
      if (
        this.productSearchQuery.trim() !== "" &&
        this.$route.name === "products" &&
        this.$route.query.q !== this.productSearchQuery
      ) {
        this.$router.push({
          name: "products",
          query: { q: this.productSearchQuery }
        });
      }
    }
  },
  created() {
    this.productSearchQuery = this.$route.query.q;
  }
};
</script>

