<template>
  <div v-if="isAdmin">
    <!---------------------------->
    <!---- Title and subtitle ---->
    <!---------------------------->
    <h1>Pagina di amministrazione</h1>
    <h3>Benvenuto, {{ user.name }}</h3>

    <!-- Horizontal line (divider) -->
    <v-divider class="my-4" />

    <!---------------------->
    <!---- Buttons grid ---->
    <!---------------------->
    <v-row cols="12">
      <v-col
        v-for="(page, index) in pages"
        :key="index"
        class="d-flex justify-center"
        xl="3"
        lg="3"
        md="4"
        sm="6"
        cols="12"
      >
        <!-- Button -->
        <v-btn :to="page.route" color="secondary" dark>
          {{ page.title }}
          <v-icon class="ml-3">{{ page.icon }}</v-icon>
        </v-btn>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import adminMixin from "@/mixins/adminMixin";
export default {
  name: "Admin",
  mixins: [adminMixin],
  data() {
    return {
      pages: [
        {
          title: "Ordini",
          icon: "mdi-truck",
          route: "/admin/ordini"
        },
        {
          title: "Prodotti",
          icon: "mdi-package-variant",
          route: "/admin/prodotti"
        },
        {
          title: "Marche",
          icon: "mdi-tag",
          route: "/admin/marche"
        },
        {
          title: "Categorie",
          icon: "mdi-format-list-bulleted-square",
          route: "/admin/categorie"
        },

        {
          title: "Utenti",
          icon: "mdi-account",
          route: "/admin/utenti"
        }
      ]
    };
  }
};
</script>
