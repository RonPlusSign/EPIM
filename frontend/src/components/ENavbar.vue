<template>
  <div>
    <v-app-bar app color="primary" class="align-content-center" dark>
      <!-------------------------------->
      <!---- Drawer button toggler ----->
      <!-------------------------------->
      <v-btn
        class="mr-1 hidden-xs-only"
        icon
        @click="drawerIsExpanded = !drawerIsExpanded"
      >
        <v-icon large>mdi-menu</v-icon>
      </v-btn>

      <!--------------->
      <!---- Logo ----->
      <!--------------->
      <v-btn
        @click="$router.push('/').catch((err) => {})"
        text
        height="44px"
        class="px-0"
      >
        <v-img
          alt="EPIM Logo"
          class="shrink"
          contain
          src="https://www.freepnglogos.com/uploads/letter-e-design-logo-png-27.png"
          transition="scale-transition"
          width="38"
        />

        <h1 class="hidden-sm-and-down ml-2">EPIM</h1>
      </v-btn>

      <v-spacer class="hidden-xs-only"></v-spacer>

      <!--------------------->
      <!---- Search bar ----->
      <!--------------------->
      <v-text-field
        align="center"
        light
        v-model="productSearchQuery"
        label="Cosa stai cercando?"
        outlined
        rounded
        solo
        hide-details
        dense
        append-icon="mdi-magnify"
        @click:append="searchProduct"
        @keyup.enter.native="searchProduct"
      ></v-text-field>

      <v-btn icon>
        <v-icon>mdi-filter</v-icon>
      </v-btn>

      <v-spacer class="hidden-xs-only"></v-spacer>

      <!----------------------------------------------------->
      <!---- User profile / login buttons / cart button ----->
      <!----------------------------------------------------->
      <div class="hidden-xs-only">
        <div v-if="logged" class="hidden-sm-and-down">
          <v-btn
            @click="$router.replace('/profilo').catch((err) => {})"
            target="_blank"
            text
          >
            <span class="mr-2">Profilo di {{ user.name }}</span>
          </v-btn>
        </div>

        <div v-else>
          <v-btn target="_blank" class="px-0 mx-0" text>
            <h4 class="pt-1">Login</h4>
            <v-icon class="ml-2">mdi-account</v-icon>
          </v-btn>
        </div>

        <v-btn
          v-if="logged"
          @click="$router.replace('/carrello').catch((err) => {})"
          target="_blank"
          icon
        >
          <v-icon>mdi-cart-outline</v-icon>
        </v-btn>
      </div>
    </v-app-bar>

    <!--------------------------------->
    <!------ Drawer (side menu) ------->
    <!--------------------------------->
    <v-navigation-drawer
      light
      v-model="drawerIsExpanded"
      class="hidden-xs-only"
      app
    >
      <v-container width="100%" class="headline">{{
        logged ? "Ciao," + user.name + "!" : "Benvenuto!"
      }}</v-container>
      <v-divider></v-divider>

      <!-- List of items -->
      <v-list dense nav class="py-0">
        <div v-for="item in drawerItems" :key="item.title">
          <v-list-item
            @click="$router.replace(item.route).catch((err) => {})"
            class="py-1"
            link
          >
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title class="subtitle-1">{{
                item.title
              }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
        </div>
      </v-list>
    </v-navigation-drawer>
  </div>
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
        id: null,
      },
      productSearchQuery: "",
      drawerIsExpanded: false,
      drawerItems: [
        { title: "Home", icon: "mdi-home", route: "/" },
        { title: "Prodotti", icon: "mdi-package-variant", route: "/prodotti" },
        {
          title: "Categorie",
          icon: "mdi-format-list-bulleted-square",
          route: "/categorie",
        },
        { title: "Il tuo profilo", icon: "mdi-account", route: "/profilo" },
        { title: "Logout", icon: "mdi-logout", route: "/logout" },
      ],
    };
  },
  methods: {
    searchProduct() {
      /**
       * Block navigation if route is the same (same search) or search is empty string
       * Also redirects to /prodotti when using searchbar
       */
      if (
        this.productSearchQuery.trim() !== "" &&
        this.$route.query.q !== this.productSearchQuery.trim()
      ) {
        this.$router.push({
          name: "products",
          query: { q: this.productSearchQuery.trim() },
        });
        console.log(this.$router.history.current.fullPath);
      }
    },
  },
  created() {
    this.productSearchQuery = this.$route.query.q;
  },
};
</script>
