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
    <v-menu transition="slide-y-transition" offset-y :close-on-content-click="false">
      <!-- filters menu toggler -->
      <template v-slot:activator="{ on }">
        <v-btn icon v-on="on">
          <v-icon>
            {{
            filtersEmpty ? "mdi-filter-outline" : "mdi-filter"
            }}
          </v-icon>
        </v-btn>
      </template>

      <!------------------------------->
      <!----------- filters ----------->
      <!------------------------------->
      <v-container class="white">
        <h3 class="mb-1">Filtri di ricerca</h3>
        <v-divider></v-divider>

        <!-- Best sellers -->
        <v-row class="pa-3">
          <v-col align="center">
            <v-btn
              @click="bestSellers"
              color="primary lighten-2 black--text"
              small
            >Prodotti più venduti</v-btn>
          </v-col>
          <v-col align="center">
            <!-- Worst sellers -->
            <v-btn @click="worstSellers" color="orange lighten-2" small>Prodotti meno venduti</v-btn>
          </v-col>
        </v-row>

        <!-- Category -->
        <v-select
          clearable
          v-model="activeFilters.selectedCategoryId"
          :items="categories"
          item-text="name"
          item-value="id"
          no-data-text="Nessuna categoria presente"
          prepend-icon="mdi-format-list-bulleted-square"
          label="Categoria"
        />

        <!-- Brand -->
        <v-select
          clearable
          v-model="activeFilters.selectedBrandId"
          :items="brands"
          item-text="name"
          item-value="id"
          no-data-text="Nessuna marca presente"
          prepend-icon="mdi-tag"
          label="Marca"
        />

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
          <template v-slot:thumb-label="{ value }">{{ value === priceRange.max ? "MAX" : value }}</template>
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
        />

        <v-row cols="12" class="mx-1 mb-3 mt-2">
          <v-col xl="5" lg="5" md="5" sm="12" cols="12" align="center">
            <!-- Filters Clear button -->
            <v-btn color="red lighten-1" dark @click="clearFilters">
              Cancella filtri
              <v-icon class="ml-2" dark>mdi-delete</v-icon>
            </v-btn>
          </v-col>
          <v-col class="hidden-sm-and-down" xl="3" lg="3" md="3" cols="12"></v-col>
          <v-col xl="4" lg="4" md="4" cols="12" align="center">
            <!-- Filters OK button -->
            <v-btn dark color="blue" @click="searchProducts">
              Cerca
              <v-icon class="ml-2" dark>mdi-magnify</v-icon>
            </v-btn>
          </v-col>
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
        interval: 10
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
        // {
        //   id: 6,
        //   name: "Più venduti",
        //   type: "sales",
        //   sortMethod: "desc",
        // },
        // {
        //   id: 7,
        //   name: "Meno venduti",
        //   type: "sales",
        //   sortMethod: "asc",
        // },
      ],

      activeFilters: {
        selectedCategoryId: null,
        selectedBrandId: null,
        priceRange: [0, 500],
        selectedSortingMethodId: 1
      }
    };
  },
  computed: {
    selectedSortingMethod() {
      // Represents the selected sorting method of the array typesOfSorting
      return this.typesOfSorting.filter(
        element => element.id === this.activeFilters.selectedSortingMethodId
      )[0];
    },

    filtersEmpty() {
      return (
        this.activeFilters.selectedCategoryId === null &&
        this.activeFilters.selectedBrandId === null &&
        this.activeFilters.priceRange[0] === 0 &&
        this.activeFilters.priceRange[1] === 500 &&
        this.activeFilters.selectedSortingMethodId === 1
      );
    }
  },
  created() {
    // Fill the selected filters values depending on the current query string

    // query to get all categories (and fill filters)
    Axios.get(process.env.VUE_APP_API_URL + `categories.php`)
      .then(response => {
        this.categories = response.data;

        // Set the values of category and brand depending on the current route query string
        if (this.$route.query.c !== undefined) {

          this.activeFilters.selectedCategoryId = +this.categories.find(
            category => category.name === this.$route.query.c
          ).id;
        }
      })
      .catch(error => {
        console.error(error);
      });

    // query to get all brands (and fill filters)
    Axios.get(process.env.VUE_APP_API_URL + `brands.php`)
      .then(response => {
        this.brands = response.data;
        // Brand
        if (this.$route.query.b !== undefined) {

          this.activeFilters.selectedBrandId = +this.brands.find(
            brand => brand.name === this.$route.query.b
          ).id;
        }
      })
      .catch(error => {
        console.error(error);
      });

    // Restore the price range
    if (this.$route.query.ps !== undefined)
      this.activeFilters.priceRange[0] = this.$route.query.ps;
    if (this.$route.query.pe !== undefined)
      this.activeFilters.priceRange[1] = this.$route.query.pe;

    this.savedFiltersFromURI = true;
  },

  watch: {
    activeFilters: {
      deep: true,
      handler() {
        // this handler is also called if the filters are retrieved by the parameters in the URI, so we emit the value only if the change is after that process
        if (this.savedFiltersFromURI)
          this.$emit("filters-changed", this.formatFilters());
      }
    }
  },
  methods: {
    formatFilters() {
      // Method that returns an object with the attributes that follow the request format
      // Also check if the filters are defined and not null
      var formattedFilters = {};
      // Category
      if (this.activeFilters.selectedCategoryId !== null) {
        formattedFilters.c = this.categories.find(
          category => category.id === this.activeFilters.selectedCategoryId
        ).name;
      }

      // Brand
      if (this.activeFilters.selectedBrandId !== null) {
        formattedFilters.b = this.brands.find(
          brand => brand.id === this.activeFilters.selectedBrandId
        ).name;
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
        selectedCategoryId: null,
        selectedBrandId: null,
        priceRange: [0, 500],
        selectedSortingMethodId: 1
      };
    },

    bestSellers() {
      this.clearFilters();
      this.$emit("filters-changed", {
        sales: "",
        desc: ""
      });
      this.$emit("search");
    },
    worstSellers() {
      this.clearFilters();
      this.$emit("filters-changed", {
        sales: "",
        asc: ""
      });
      this.$emit("search");
    }
  }
};
</script>
