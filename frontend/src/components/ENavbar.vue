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
        @click:append="searchProducts"
        @keyup.enter.native="searchProducts"
      ></v-text-field>

      <!---------------------------------------->
      <!---- Filter button & filter inputs ----->
      <!---------------------------------------->
      <div class="text-center">
        <v-menu offset-y :close-on-content-click="false">
          <!-- filters menu toggler -->
          <template v-slot:activator="{ on }">
            <v-btn icon dark v-on="on">
              <v-icon>mdi-filter</v-icon>
            </v-btn>
          </template>

          <!------------------------------->
          <!----------- filters ----------->
          <!------------------------------->
          <v-container class="white">
            <h3 class="mb-1">Filtri di ricerca</h3>
            <v-divider></v-divider>

            <!-- Category -->
            <v-select
              clearable
              v-model="selectedCategory"
              :items="categories"
              item-text="name"
              item-value="id"
              no-data-text="Nessuna categoria presente"
              prepend-icon="mdi-format-list-bulleted-square"
              label="Categoria"
            >
            </v-select>

            <!-- Brand -->
            <v-select
              clearable
              v-model="selectedBrand"
              :items="brands"
              item-text="name"
              item-value="id"
              no-data-text="Nessuna marca presente"
              prepend-icon="mdi-tag"
              label="Marca"
            >
            </v-select>

            <!-- Price range -->
            <p class="body mt-2 mb-1">Fascia di prezzo</p>
            <v-range-slider
              v-model="priceRange.selectedRange"
              :max="priceRange.max"
              :min="priceRange.min"
              :step="priceRange.interval"
              hide-details
              thumb-label="always"
              class="align-center mt-12"
            >
              <!-- thumb labels value -->
              <template v-slot:thumb-label="{ value }">
                {{ value === priceRange.max ? "MAX" : value }}
              </template>
            </v-range-slider>

            <!-- Products order -->
            <v-select
              clearable
              v-model="selectedOrder"
              :items="typesOfOrder"
              item-text="name"
              item-value="type"
              no-data-text="Nessun ordinamento possibile"
              prepend-icon="mdi-sort"
              label="Ordina per..."
            >
            </v-select>

            <!-- Filters OK button -->
            <v-btn
              class="float-right mt-4 mb-3"
              dark
              color="blue"
              @click="searchProducts"
            >
              Cerca
              <v-icon class="ml-2" dark>mdi-magnify</v-icon>
            </v-btn>
          </v-container>
        </v-menu>
      </div>

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
      // Side menu
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
      // User values
      logged: false,
      user: {
        // TODO: make a request to login.php to see if the user is logged (and retrieve its data)
        name: "",
        id: null,
      },

      // ----- Filters attributes -----
      // Title
      productSearchQuery: "",
      // Categories
      categories: [
        // { id: 5, name: "Phon" },
        // { id: 6, name: "Auricolari" },
        // { id: 7, name: "TV" },
      ],
      selectedCategory: null,
      // Brands
      brands: [
        // { id: 1, name: "Samsung" },
        // { id: 2, name: "Apple" },
        // { id: 3, name: "Xiaomi" },
      ],
      selectedBrand: null,
      // Price range
      priceRange: {
        min: 0,
        max: 500,
        interval: 25,
        selectedRange: [0, 500],
      },
      // Order types
      typesOfOrder: [
        // TODO: Add order types filter
      ],
      selectedOrder: null,
    };
  },
  methods: {
    filtersChanged() {
      let changed =
        // Title
        (this.productSearchQuery !== "" &&
          this.$route.query.q !== this.productSearchQuery.trim()) ||
        // Category
        (this.selectedCategory !== null &&
          this.$route.query.c !== this.selectedCategory) ||
        // Brand
        (this.selectedBrand !== null &&
          this.$route.query.b !== this.selectedBrand);

      // console.log("1: ", changed);
      if (changed) return changed;

      // Price range
      // min
      changed =
        (this.priceRange.selectedRange[0] !== this.priceRange.min &&
          this.$route.query.ps === undefined) ||
        this.priceRange.selectedRange[0] == this.$route.query.ps;

      // console.log("2: ", changed);
      if (changed) return changed;

      // max
      changed =
        (this.priceRange.selectedRange[1] !== this.priceRange.max &&
          this.$route.query.pe === undefined) ||
        this.priceRange.selectedRange[1] == this.$route.query.pe;

      // console.log("3: ", changed);
      return changed;
      // Order type
    },

    searchProducts() {
      /**
       * Block navigation if route is the same (same search)
       * Also redirects to /prodotti when using searchbar
       */
      if (this.filtersChanged()) {
        var parameters = {};

        if (this.productSearchQuery.trim() !== "")
          parameters.q = this.productSearchQuery.trim();

        if (this.selectedCategory !== null) {
          parameters.c = this.selectedCategory;
        }

        if (this.selectedBrand !== null) {
          parameters.b = this.selectedBrand;
        }

        if (this.priceRange.selectedRange[0] !== this.priceRange.min) {
          parameters.ps = this.priceRange.selectedRange[0];
        }

        if (this.priceRange.selectedRange[1] !== this.priceRange.max) {
          parameters.pe = this.priceRange.selectedRange[1];
        }

        this.$router.push({
          name: "products",
          query: parameters,
        });
        console.log(this.$router.history.current.fullPath);
      }
    },
  },
  created() {
    // TODO: query to get all categories (to fill filters)
    // TODO: query to get all brands (to fill filters)
    if (this.$route.query.q !== undefined)
      this.productSearchQuery = this.$route.query.q;
    if (this.$route.query.ps !== undefined)
      this.priceRange.selectedRange[0] = this.$route.query.ps;
    if (this.$route.query.pe !== undefined)
      this.priceRange.selectedRange[1] = this.$route.query.pe;
    // Category

    // TODO: Set the values depending on the current route query string of category and brand (currently not working)
    if (this.$route.query.c !== undefined)
      this.selectedCategory = this.$route.query.c;
    // Brand
    if (this.$route.query.b !== undefined)
      this.selectedBrand = this.$route.query.b;
  },
};
</script>

<style>
.v-slider {
  width: 300px;
}
</style>
