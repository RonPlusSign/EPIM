<template>
  <v-app-bar app color="green darken-1" class="align-content-center" dark>
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

    <v-text-field align="center"
      light
      v-model="title"
      label="Cerca un prodotto..."
      outlined
      rounded
      solo
      clearable
    ></v-text-field> <!-- append-icon="search" -->
      

    <v-spacer></v-spacer>

    <!--------------------------------------->
    <!---- User profile / login buttons ----->
    <!--------------------------------------->
    <v-btn v-if="logged" href target="_blank" text>
      <span class="mr-2">Benvenuto, {{ user.name }}</span>
    </v-btn>
    <div v-else>
      <v-btn href target="_blank" text>Registrati</v-btn>
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
      user: { // TODO: make a request to login.php to see if the user is logged (and retrieve its data)
        name: "",
        id: null
      },
      title: ""
    };
  }
};
</script>

<style scoped>
</style>