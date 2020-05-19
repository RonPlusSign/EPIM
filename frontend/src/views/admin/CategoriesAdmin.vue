<template>
  <div v-if="logged">
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn @click="$router.push('/admin')" text color="secondary">
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">Torna alla pagina di admin</span>
    </v-btn>
    <br />
    <br />

    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2>Gestione delle categorie</h2>

    <v-spacer class="my-3" />

    <!------------------------------->
    <!---- CRUD categories table ---->
    <!------------------------------->
    <EAdminCRUDList
      :fetchingItems="loading"
      :items="categories"
      :endpoint="'categories.php'"
      :nameOfItems="'categorie'"
      @updated-server-values="getCategories"
    />

    <v-spacer clas="my-4" />
  </div>
</template>

<script>
import Axios from "axios";

import adminMixin from "@/mixins/adminMixin"; // To manage login
import EAdminCRUDList from "@/components/admin/EAdminCRUDList.vue";

export default {
  name: "CategoriesAmin",
  mixins: [adminMixin],

  components: { EAdminCRUDList },

  data() {
    return {
      categories: [],
      loading: false
    };
  },
  created() {
    this.getCategories();
  },
  methods: {
    // GET request to categories.php
    getCategories() {
      this.loading = true;
      Axios.get(process.env.VUE_APP_API_URL + `categories.php`)
        .then(response => {
          this.categories = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>
