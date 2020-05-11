<template>
  <v-container>
    <!-------------------->
    <!---- Page title ---->
    <!-------------------->
    <h2>
      {{
        productObject ? "Modifica di un prodotto" : "Aggiunta di un prodotto"
      }}
    </h2>
    <v-divider class="my-3" />

    <!----------------------->
    <!---- Product title ---->
    <!----------------------->
    <v-form ref="editProductForm">
      <h3 class="mb-3">Titolo</h3>
      <v-text-field
        v-model="product.title"
        :rules="[rules.required]"
        clearable
        dense
        filled
      />

      <v-divider class="my-3" />

      <v-row cols="12">
        <v-col xl="4" lg="4" md="6" sm="12" xs="12" align="center">
          <!-------------------->
          <!---- Sell price ---->
          <!-------------------->
          <h3 class="mb-3">Prezzo di vendita (€)</h3>
          <ENumberInput
            :value="product.sellPrice"
            :min="0"
            :decimals="2"
            @changed="(value) => (product.sellPrice = value)"
          />
        </v-col>
        <v-col xl="4" lg="4" md="6" sm="12" xs="12" align="center">
          <!------------------------>
          <!---- Purchase Price ---->
          <!------------------------>
          <h3 class="mb-3">Prezzo di acquisto (€)</h3>
          <ENumberInput
            :value="product.purchasePrice"
            :min="0"
            :decimals="2"
            @changed="(value) => (product.purchasePrice = value)"
          />
        </v-col>
        <v-col xl="4" lg="4" md="12" sm="12" xs="12" align="center">
          <!--------------------------->
          <!---- Recommended price ---->
          <!--------------------------->
          <h3 class="mb-3">Prezzo consigliato (€)</h3>
          <ENumberInput
            :value="product.recommendedPrice"
            :min="0"
            :decimals="2"
            @changed="(value) => (product.recommendedPrice = value)"
          />
        </v-col>
      </v-row>

      <v-divider class="my-3" />

      <!--------------------->
      <!---- Description ---->
      <!--------------------->
      <h3 class="mb-3">Descrizione</h3>
      <v-textarea
        v-model="product.description"
        :rules="[rules.required]"
        clearable
        filled
        auto-grow
      />
      <!---------------------------->
      <!---- Available quantity ---->
      <!---------------------------->
      <h3 class="mb-3">Quantità disponibile</h3>
      <ENumberInput
        :value="product.quantity"
        :min="0"
        @changed="(value) => (product.quantity = value)"
      />

      <v-divider class="my-3" />
      <!---------------->
      <!---- Images ---->
      <!---------------->
      <h3>Immagini</h3>
      <!------------------- // TODO: Manage images -------------------->
      <i> La gestione delle immagini non è supportata al momento. </i>

      <v-divider class="my-3" />
      <!------------------>
      <!---- Category ---->
      <!------------------>
      <h3 class="mb-3">Categoria</h3>

      <v-select
        v-model="product.categoryId"
        :items="categories"
        item-text="name"
        item-value="id"
        no-data-text="Nessuna categoria presente"
        prepend-icon="mdi-format-list-bulleted-square"
        clearable
        filled
        label="Categoria"
        :rules="[rules.required]"
      ></v-select>

      <v-divider class="my-3" />
      <!--------------->
      <!---- Brand ---->
      <!--------------->
      <h3 class="mb-3">Marca</h3>

      <v-select
        v-model="product.brandId"
        :items="brands"
        item-text="name"
        item-value="id"
        no-data-text="Nessuna marca presente"
        prepend-icon="mdi-tag"
        clearable
        filled
        label="Marca"
        :rules="[rules.required]"
      ></v-select>
    </v-form>

    <!----------------------->
    <!---- "Save" button ---->
    <!----------------------->
    <v-btn
      @click="sendProduct"
      dark
      fixed
      rounded
      bottom
      right
      large
      class="saveBtn mr-6 mb-6 elevation-6"
      color="blue"
      :loading="loading"
    >
      Salva
      <v-icon class="ml-2">mdi-check</v-icon>
    </v-btn>
  </v-container>
</template>

<script>
import Axios from "axios";

import ENumberInput from "@/components/ENumberInput.vue";

export default {
  components: { ENumberInput },
  data() {
    return {
      product: {
        title: "",
        description: "",
        quantity: 0,
        brandId: -1,
        categoryId: -1,
        purchasePrice: -1,
        sellPrice: -1,
        recommendedPrice: -1,
      },
      categories: [],
      brands: [],

      rules: { required: (value) => !!value || "Inserisci questo parametro" },
      loading: false,
    };
  },

  props: {
    productObject: {
      // If this object is passed, we're editing the product data
      // If it is null, we're creating a new product
      default: null,
    },
  },

  created() {
    if (this.productObject) this.product = this.productObject;

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
  },

  mounted() {
    this.$refs.editProductForm.reset();
  },

  watch: {
    productObject(newObject) {
      this.product = newObject;
    },
  },

  methods: {
    sendProduct() {
      if (this.$refs.editProductForm.validate()) {
        this.loading = true;

        if (!this.productObject) {
          // Create a new product: POST request
          Axios.post(process.env.VUE_APP_API_URL + `products.php`, this.product)
            .then(() => {
              this.loading = false;
              this.$router.push("/admin/prodotti");
            })
            .catch((err) => {
              console.error(err);
              this.loading = false;
            });
        } else {
          // Edit the product: PATCH request

          // Check what attributes changed
          let patch = {};

          // console.log(this.product);
          // console.log(this.productObject);

          if (this.product.title !== this.productObject.title)
            patch.title = this.product.title;

          if (this.product.description !== this.productObject.description)
            patch.description = this.product.description;

          if (this.product.quantity !== this.productObject.quantity)
            patch.quantity = this.product.quantity;

          if (this.product.brandId !== this.productObject.brandId)
            patch.brandId = this.product.brandId;

          if (this.product.categoryId !== this.productObject.categoryId)
            patch.categoryId = this.product.categoryId;

          if (this.product.purchasePrice !== this.productObject.purchasePrice)
            patch.purchasePrice = this.product.purchasePrice;

          if (this.product.sellPrice !== this.productObject.sellPrice)
            patch.sellPrice = this.product.sellPrice;

          if (
            this.product.recommendedPrice !==
            this.productObject.recommendedPrice
          )
            patch.recommendedPrice = this.product.recommendedPrice;

          // console.log(patch);
          Axios.patch(process.env.VUE_APP_API_URL + `products.php`, patch)
            .then(() => {
              this.loading = false;
              this.$router.push("/admin/prodotti");
            })
            .catch((err) => {
              console.error(err);
              this.loading = false;
            });
        }
      }
    },
  },
};
</script>

<style scoped>
.saveBtn {
  z-index: 1;
}
</style>
