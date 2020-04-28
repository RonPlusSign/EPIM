<template>
  <!--------------------------------->
  <!------ Drawer (side menu) ------->
  <!--------------------------------->
  <v-navigation-drawer light v-model="isExpanded" app>
    <v-container width="100%" class="headline">{{ logged ? `Ciao, ${username}!` : "Benvenuto!" }}</v-container>
    <v-divider></v-divider>

    <!-- List of items -->
    <v-list dense nav class="py-0">
      <!-- Dynamic routes (from data()) -->
      <div v-for="item in drawerItems" :key="item.title">
        <v-list-item @click="$router.replace(item.route).catch((err) => {})" class="py-1" link>
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title class="subtitle-1">{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
      </div>

      <!-- Logout list item -->
      <v-list-item @click="logout()" class="py-1" link>
        <v-list-item-icon>
          <v-icon>mdi-logout</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title class="subtitle-1">Logout</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider></v-divider>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import Axios from "axios";

export default {
  name: "ENavigationDrawer",
  props: {
    drawerExpanded: {
      type: Boolean,
      required: true
    },
    logged: {
      type: Boolean,
      required: true
    },
    username: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      isExpanded: false,
      drawerItems: [
        { title: "Home", icon: "mdi-home", route: "/" },
        { title: "Il tuo profilo", icon: "mdi-account", route: "/profilo" },
        { title: "Prodotti", icon: "mdi-package-variant", route: "/prodotti" },
        {
          title: "Categorie",
          icon: "mdi-format-list-bulleted-square",
          route: "/categorie"
        },
        { title: "Marche", icon: "mdi-tag", route: "/marche" }
      ]
    };
  },

  created() {
    this.isExpanded = this.drawerExpanded;
  },

  watch: {
    drawerExpanded(value) {
      this.isExpanded = value;
    },
    isExpanded(value) {
      this.$emit("toggle", value);
    }
  },

  methods: {
    logout() {
      Axios.get(process.env.VUE_APP_API_URL + `login.php?logout`).catch(err =>
        console.log(err)
      );

      this.$router.replace("/");
    }
  }
};
</script>