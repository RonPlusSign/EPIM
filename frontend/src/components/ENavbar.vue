<template>
  <div>
    <v-app-bar app color="primary" class="align-content-center" dark>
      <!-------------------------------->
      <!---- Drawer button toggler ----->
      <!-------------------------------->
      <v-btn class="mr-1 hidden-xs-only" icon @click="drawerIsExpanded = !drawerIsExpanded">
        <v-icon large>mdi-menu</v-icon>
      </v-btn>

      <!--------------->
      <!---- Logo ----->
      <!--------------->
      <v-btn @click="$router.push('/').catch((err) => {})" text height="44px" class="px-0">
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
            ></v-select>

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
            ></v-select>

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
              <template
                v-slot:thumb-label="{ value }"
              >{{ value === priceRange.max ? "MAX" : value }}</template>
            </v-range-slider>

            <!-- Products sorting method -->
            <v-select
              v-model="selectedSortingTypeId"
              :items="typesOfSorting"
              item-text="name"
              item-value="id"
              no-data-text="Nessun ordinamento possibile"
              prepend-icon="mdi-sort"
              label="Ordina per..."
            ></v-select>

            <!-- Filters OK button -->
            <v-btn class="float-right mt-4 mb-3" dark color="blue" @click="searchProducts">
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
          <v-btn @click="$router.replace('/profilo').catch((err) => {})" target="_blank" text>
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
    <v-navigation-drawer light v-model="drawerIsExpanded" class="hidden-xs-only" app>
      <v-container width="100%" class="headline">
        {{
        logged ? "Ciao," + user.name + "!" : "Benvenuto!"
        }}
      </v-container>
      <v-divider></v-divider>

      <!-- List of items -->
      <v-list dense nav class="py-0">
        <div v-for="item in drawerItems" :key="item.title">
          <v-list-item @click="$router.replace(item.route).catch((err) => {})" class="py-1" link>
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title class="subtitle-1">
                {{
                item.title
                }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
        </div>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
import Axios from "axios";

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
          route: "/categorie"
        },
        { title: "Il tuo profilo", icon: "mdi-account", route: "/profilo" },
        { title: "Logout", icon: "mdi-logout", route: "/logout" }
      ],
      // User values
      logged: false,
      user: {
        // TODO: make a request to login.php to see if the user is logged (and retrieve its data)
        name: "",
        id: null
      },

      // ----- Filters attributes -----
      filtersChanged: false,
      // Title
      productSearchQuery: "",
      // Categories
      categories: [],
      selectedCategory: null,
      // Brands
      brands: [],
      selectedBrand: null,
      // Price range
      priceRange: {
        min: 0,
        max: 500,
        interval: 25,
        selectedRange: [0, 500]
      },
      // Order types
      typesOfSorting: [
        {
          id: 1,
          name: "Migliore corrispondenza",
          type: null,
          sortMethod: null
        },
        { id: 2, name: "Titolo (crescente)", type: "title", sortMethod: "asc" },
        {
          id: 3,
          name: "Titolo (decrescente)",
          type: "title",
          sortMethod: "desc"
        },
        { id: 4, name: "Prezzo (crescente)", type: "price", sortMethod: "asc" },
        {
          id: 5,
          name: "Prezzo (decrescente)",
          type: "price",
          sortMethod: "desc"
        }
      ],
      selectedSortingTypeId: 1
    };
  },
  computed: {
    selectedOrder() {
      return this.typesOfSorting.filter(
        element => element.id === this.selectedSortingTypeId
      )[0];
    }
  },
  methods: {
    searchProducts() {
      /**
       * Block navigation if route is the same (same search)
       * Also redirects to /prodotti when using searchbar
       */
      if (this.filtersChanged) {
        var parameters = {};

        // Title
        if (this.productSearchQuery.trim() !== "")
          parameters.q = this.productSearchQuery.trim();

        // Category
        if (this.selectedCategory !== null) {
          parameters.c = this.selectedCategory;
        }

        // Brand
        if (this.selectedBrand !== null) {
          parameters.b = this.selectedBrand;
        }

        // Price range
        if (this.priceRange.selectedRange[0] !== this.priceRange.min) {
          parameters.ps = this.priceRange.selectedRange[0];
        }

        if (this.priceRange.selectedRange[1] !== this.priceRange.max) {
          parameters.pe = this.priceRange.selectedRange[1];
        }

        // Type of sorting
        if (this.selectedOrder.type) {
          parameters.sort = this.selectedOrder.type;
        }

        switch (this.selectedOrder.sortMethod) {
          case "asc":
            parameters.asc = null;
            break;

          case "desc":
            parameters.desc = null;
            break;

          default:
            break;
        }

        this.$router.push({
          name: "products",
          query: parameters
        });
        console.log(this.$router.history.current.fullPath);
      }
      this.filtersChanged = false;
    }
  },
  created() {
    // query to get all categories (to fill filters)
    Axios.get(process.env.VUE_APP_API_URL + `categories.php`)
      .then(response => {
        this.categories = response.data;
      })
      .catch(error => {
        console.error(error);
      });

    // query to get all brands (to fill filters)
    Axios.get(process.env.VUE_APP_API_URL + `brands.php`)
      .then(response => {
        this.brands = response.data;
      })
      .catch(error => {
        console.error(error);
      });

    // fill the selected filters values depending on the current query string
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

  watch: {
    productSearchQuery() {
      this.filtersChanged = true;
    },
    selectedCategory() {
      this.filtersChanged = true;
    },
    selectedBrand() {
      this.filtersChanged = true;
    },

    selectedSortingTypeId() {
      this.filtersChanged = true;
    },

    priceRange: {
      deep: true,
      handler() {
        this.filtersChanged = true;
      }
    }
  }
};
</script>

