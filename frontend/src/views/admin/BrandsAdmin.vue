<template>
  <div v-if="isAdmin">
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn :to="'/admin'" exact text>
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">Torna alla pagina di admin</span>
    </v-btn>

    <br />
    <br />
    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2>Gestione delle marche</h2>

    <v-spacer class="my-3" />

    <!--------------------------->
    <!---- CRUD brands table ---->
    <!--------------------------->
    <EAdminCRUDList
      :fetchingItems="loading"
      :items="brands"
      :endpoint="'brands.php'"
      :nameOfItems="'marche'"
      @updated-server-values="getBrands"
    />

    <v-spacer clas="my-4" />
  </div>
</template>

<script>
import Axios from "axios";

import adminMixin from "@/mixins/adminMixin"; // To manage login
import EAdminCRUDList from "@/components/admin/EAdminCRUDList.vue";

export default {
  name: "BrandsAdmin",
  mixins: [adminMixin],
  components: { EAdminCRUDList },
  data() {
    return {
      brands: [],
      loading: false
    };
  },
  created() {
    this.getBrands();
  },
  methods: {
    // GET request to brands.php
    getBrands() {
      this.loading = true;
      Axios.get(process.env.VUE_APP_API_URL + `brands.php`)
        .then(response => {
          this.brands = response.data;
          this.loading = false;
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>
