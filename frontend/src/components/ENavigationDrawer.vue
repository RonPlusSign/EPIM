<!--
This component requires one prop:

"drawerExpanded" (Boolean): to check if the drawer has to be expanded (it can be toggled from outside this component if necessary)
-->

<template>
  <!--------------------------------->
  <!------ Drawer (side menu) ------->
  <!--------------------------------->
  <v-navigation-drawer light v-model="isExpanded" app>
    <v-container width="100%" class="headline">
      {{
      logged ? `Ciao, ${user ? user.name : ""}!` : "Benvenuto!"
      }}
    </v-container>
    <v-divider />

    <!-- List of items -->
    <v-list dense nav class="py-0">
      <!-- Dynamic routes (from data()) -->
      <div v-for="item in drawerItems" :key="item.title">
        <v-list-item :to="item.route" class="py-1 mb-0" link>
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content class="py-0">
            <v-list-item-title class="subtitle-2">{{item.title}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider />
      </div>

      <!-- Logout list item -->
      <v-list-item v-if="logged" @click="logout" class="py-1 mb-0" link>
        <v-list-item-icon>
          <v-icon>mdi-logout</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title class="subtitle-2">Logout</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider v-if="logged" />
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "ENavigationDrawer",
  props: {
    drawerExpanded: {
      type: Boolean,
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
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
    user() {
      return this.$store.getters.user;
    }
  },

  created() {
    this.isExpanded = this.drawerExpanded;
  },

  watch: {
    drawerExpanded(value) {
      // Sync the prop with the value coming from outside
      this.isExpanded = value;
    },
    isExpanded(value) {
      this.$emit("toggle", value);
    }
  },

  methods: {
    logout() {
      this.$store.dispatch("logout");

      this.$router.push("/");
    }
  }
};
</script>
