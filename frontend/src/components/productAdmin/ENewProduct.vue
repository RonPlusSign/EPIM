<template>
  <v-container class="ENewProduct">
    <!-------------------->
    <!---- Page title ---->
    <!-------------------->
    <v-form v-model="isDataValid">
      <v-card class="mx-auto EProductCard" height="100%" width="80%">
        <v-card-title>{{ product ? "Modifica" : "Nuovo prodotto" }}</v-card-title>
        <v-img
          class="white--text align-end"
          height="200px"
          :src="productLocal.images[0]"
          alt="Immagine Prodotto"
          contain
        ></v-img>

        <v-card-title style="height: 9em;">
          <v-text-field
            label="Titolo"
            v-model="productLocal.title"
            required
            :rules="titleRules"
            counter="256"
          ></v-text-field>
        </v-card-title>

        <v-card-text class="text--primary">
          <v-textarea solo label="Descrizione" v-model="productLocal.description"></v-textarea>

          <v-row class="mb-4">
            <v-col class="pt-0 mt-0">
              <v-autocomplete
                label="Marca"
                :items="brands"
                item-text="name"
                item-value="id"
                v-model="productLocal.brand_id"
                required
                :rules="rules"
              />
            </v-col>
            <v-col class="pt-0 mt-0">
              <v-autocomplete
                label="Categoria"
                :items="categories"
                item-text="name"
                item-value="id"
                v-model="productLocal.category_id"
                required
                :rules="rules"
              />
            </v-col>
          </v-row>

          <v-simple-table>
            <thead>
              <tr>
                <th class="text-center">Prezzo vendita [€]</th>
                <th class="text-center">Prezzo consigliato [€]</th>
                <th class="text-center">Prezzo acquisto [€]</th>
                <th class="text-center">Quantità disponibile</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center">
                  <v-text-field
                    required
                    :rules="rules"
                    type="number"
                    v-model="productLocal.sell_price"
                  />
                </td>
                <td class="text-center">
                  <v-text-field
                    required
                    :rules="rules"
                    type="number"
                    v-model="productLocal.recommended_price"
                  />
                </td>
                <td class="text-center">
                  <v-text-field
                    required
                    :rules="rules"
                    type="number"
                    v-model="productLocal.purchase_price"
                  />
                </td>
                <td class="text-center">
                  <v-text-field
                    required
                    :rules="rules"
                    type="number"
                    v-model="productLocal.quantity"
                  />
                </td>
              </tr>
            </tbody>
          </v-simple-table>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="green" text @click="imagesDialog = true">Modifica immagini</v-btn>
          <v-btn color="green" text @click="sendProduct()" :disabled="!isDataValid">Ok</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>

    <v-dialog v-model="imagesDialog" max-width="60%" transition="dialog-transition">
      <v-card>
        <v-card-title class="headline">Url Immagini</v-card-title>

        <v-card-text>
          <v-row v-for="(imageUrl,index) in productLocal.images" :key="index" no-gutters>
            <v-col class="pa-0">
              <v-text-field v-model="productLocal.images[index]" append-icon="mdi-delete" @click:append="productLocal.images.splice(index, 1)" />
            </v-col>
          </v-row>

          <v-row no-gutters>
            <v-col class="text-right pa-0">
              <v-btn text @click="productLocal.images.push('')">
                aggiungi
                <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="green darken-1" text @click="imagesDialog = false">Ok</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import Axios from "axios";

export default {
  name: "ENewProduct",
  data() {
    return {
      imagesDialog: false,
      productLocal: {},
      categories: [],
      brands: [],
      isDataValid: false,
      rules: [v => !!v || "Il campo è obbligatorio"],
      titleRules: [
        v => !!v || "Il campo è obbligatorio",
        v => v.length <= 256 || "il numero massimo di caratteri è 256"
      ]
    };
  },
  props: {
    product: {
      type: Object,
      default: null
    }
  },
  methods: {
    fetchBrandsCategories() {
      Axios.get(process.env.VUE_APP_API_URL + `categories.php`)
        .then(response => (this.categories = response.data))
        .catch(error => {
          console.error(error);
        });

      Axios.get(process.env.VUE_APP_API_URL + `brands.php`)
        .then(response => (this.brands = response.data))
        .catch(error => {
          console.error(error);
        });
    },
    patchProduct() {
      Axios.patch(
        process.env.VUE_APP_API_URL + `products.php`,
        this.productLocal,
        {
          params: {
            id: this.productLocal.id
          }
        }
      )
        .then(res =>
          res.status === 200 ? alert("Operazione completata") : alert("Errore")
        )
        .catch(() => alert("Errore (Esempio: nessun valore cambiato)"));
    },
    addProduct() {
      Axios.post(
        process.env.VUE_APP_API_URL + `products.php`,
        this.productLocal,
        {
          params: {
            id: this.productLocal.id
          }
        }
      )
        .then(res =>
          res.status === 200 ? alert("Operazione completata") : alert("Errore")
        )
        .catch(() => alert("Errore"));
    },
    // Check if current page is new product or modify product
    sendProduct() {
      if (this.product) this.patchProduct();
      else this.addProduct();
    }
  },
  watch: {
    product() {
      this.productLocal = this.product;
    }
  },
  created() {
    if (this.product) this.productLocal = this.product;
    this.fetchBrandsCategories();
  }
};
</script>

<style lang="scss" scoped>
</style>