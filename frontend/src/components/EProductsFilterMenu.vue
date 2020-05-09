<!--
Menu that manages the products search filters selection.

It emits 2 events:
"filters-changed": when the value of a filter changes
"search": when the search button is clicked

Example of usage:

<EProductsFilterMenu
  @filters-changed="newFilters => {}"
  @search="searchProducts()"
/>
-->
<template>
  <!---------------------------------------->
  <!---- Filter button & filter inputs ----->
  <!---------------------------------------->
  <div class="text-center">
    <v-menu
      transition="slide-y-transition"
      offset-y
      :close-on-content-click="false"
    >
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
          v-model="activeFilters.selectedCategory"
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
          v-model="activeFilters.selectedBrand"
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
          v-model="activeFilters.priceRange"
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

        <!-- Products sorting method -->
        <v-select
          v-model="activeFilters.selectedSortingMethodId"
          :items="typesOfSorting"
          item-text="name"
          item-value="id"
          no-data-text="Nessun ordinamento possibile"
          prepend-icon="mdi-sort"
          label="Ordina per..."
        ></v-select>

        <v-row class="mx-1 mb-3 mt-2">
          <!-- Filters Clear button -->
          <v-btn color="red lighten-1" dark @click="clearFilters">
            Cancella filtri
            <v-icon class="ml-2" dark>mdi-delete</v-icon>
          </v-btn>
          <v-spacer />
          <!-- Filters OK button -->
          <v-btn dark color="blue" @click="searchProducts">
            Cerca
            <v-icon class="ml-2" dark>mdi-magnify</v-icon>
          </v-btn>
        </v-row>
      </v-container>
    </v-menu>
  </div>
</template>

<script>
import Axios from "axios";

export default {
  data() {
    return {
      // ----- Filters attributes -----
      filtersChanged: false, // To make a new search only after some filters changed
      savedFiltersFromURI: false, // needed to start watching for changes of filters only after fetching them from the URI
      // Title
      // Categories
      categories: [],
      // Brands
      brands: [],
      // Price range
      priceRange: {
        min: 0,
        max: 500,
        interval: 25,
      },
      // Order types
      typesOfSorting: [
        {
          id: 1,
          name: "Migliore corrispondenza",
          type: null,
          sortMethod: null,
        },
        { id: 2, name: "Titolo (crescente)", type: "title", sortMethod: "asc" },
        {
          id: 3,
          name: "Titolo (decrescente)",
          type: "title",
          sortMethod: "desc",
        },
        { id: 4, name: "Prezzo (crescente)", type: "price", sortMethod: "asc" },
        {
          id: 5,
          name: "Prezzo (decrescente)",
          type: "price",
          sortMethod: "desc",
        },
        {
          id: 6,
          name: "Più venduti",
          type: "sales",
          sortMethod: "desc",
        },
        {
          id: 7,
          name: "Meno venduti",
          type: "sales",
          sortMethod: "asc",
        },
      ],

      activeFilters: {
        selectedCategory: null,
        selectedBrand: null,
        priceRange: [0, 500],
        selectedSortingMethodId: 1,
      },
    };
  },
  computed: {
    selectedSortingMethod() {
      // Represents the selected sorting method of the array typesOfSorting
      return this.typesOfSorting.filter(
        (element) => element.id === this.activeFilters.selectedSortingMethodId
      )[0];
    },
  },
  created() {
    // query to get all categories (to fill filters)
    Axios.get(process.env.VUE_APP_API_URL + `categories.php`)
      .then((response) => (this.categories = response.data))
      .catch((error) => {
        console.error(error);
      });

    // query to get all brands (to fill filters)
    Axios.get(process.env.VUE_APP_API_URL + `brands.php`)
      .then((response) => (this.brands = response.data))
      .catch((error) => {
        console.error(error);
      });

    // fill the selected filters values depending on the current query string
    if (this.$route.query.ps !== undefined)
      this.activeFilters.priceRange[0] = this.$route.query.ps;
    if (this.$route.query.pe !== undefined)
      this.activeFilters.priceRange[1] = this.$route.query.pe;
    // Category

    // TODO: Set the values of category and brand depending on the current route query string (currently not working)
    if (this.$route.query.c !== undefined)
      this.activeFilters.selectedCategory = this.$route.query.c;

    // Brand
    if (this.$route.query.b !== undefined)
      this.activeFilters.selectedBrand = this.$route.query.b;

    this.savedFiltersFromURI = true;
  },

  watch: {
    activeFilters: {
      deep: true,
      handler() {
        // this handler is also called if the filters are retrieved by the parameters in the URI, so we emit the value only if the change is after that process
        if (this.savedFiltersFromURI)
          this.$emit("filters-changed", this.formatFilters());
      },
    },
  },
  methods: {
    formatFilters() {
      // Method that returns an object with the attributes that follow the request format
      // Also check if the filters are defined and not null
      var formattedFilters = {};
      // Category
      if (this.activeFilters.selectedCategory !== null) {
        formattedFilters.c = this.activeFilters.selectedCategory;
      }

      // Brand
      if (this.activeFilters.selectedBrand !== null) {
        formattedFilters.b = this.activeFilters.selectedBrand;
      }

      // Price range
      if (this.activeFilters.priceRange[0] !== this.priceRange.min) {
        formattedFilters.ps = this.activeFilters.priceRange[0];
      }

      if (this.activeFilters.priceRange[1] !== this.priceRange.max) {
        formattedFilters.pe = this.activeFilters.priceRange[1];
      }

      // Type of sorting
      if (this.selectedSortingMethod && this.selectedSortingMethod.type) {
        formattedFilters.sort = this.selectedSortingMethod.type;

        switch (this.selectedSortingMethod.sortMethod) {
          case "asc":
            formattedFilters.asc = null;
            break;

          case "desc":
            formattedFilters.desc = null;
            break;

          default:
            break;
        }
      }

      return formattedFilters;
    },
    searchProducts() {
      this.$emit("search");
    },
    clearFilters() {
      this.activeFilters = {
        selectedCategory: null,
        selectedBrand: null,
        priceRange: [0, 500],
        selectedSortingMethodId: 1,
      };
    },
  },
};
</script>
