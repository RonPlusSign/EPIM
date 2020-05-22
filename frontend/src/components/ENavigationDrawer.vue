<!--
This component requires one prop:

"drawerExpanded" (Boolean): to check if the drawer has to be expanded (it can be toggled from outside this component if necessary)
-->

<template>
  <!--------------------------------->
  <!------ Drawer (side menu) ------->
  <!--------------------------------->
  <v-navigation-drawer light v-model="isExpanded" app disable-resize-watcher>
    <v-container width="100%" class="headline">
      <span v-if="logged">
        Ciao,
        <span class="secondary--text">{{user.name}}</span>!
      </span>
      <span v-else>Benvenuto!</span>
    </v-container>
    <v-divider />

    <!-- List of items -->
    <v-list dense nav class="py-0">
      <!-- Home route -->
      <v-list-item @click="isExpanded=false" :to="'/'" class="py-1 mb-0" link>
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>

        <v-list-item-content class="py-0">
          <v-list-item-title class="subtitle-2">Home</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider />

      <!-- "Your account" route -->
      <v-list-item @click="isExpanded=false" :to="'/profilo'" class="py-1 mb-0" link v-if="logged">
        <v-list-item-icon>
          <v-icon>mdi-account</v-icon>
        </v-list-item-icon>

        <v-list-item-content class="py-0">
          <v-list-item-title class="subtitle-2">Il tuo profilo</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider v-if="logged" />

      <!-- Login list item -->
      <v-list-item v-if="!logged" @click="login(); isExpanded = false;" class="py-1 mb-0" link>
        <v-list-item-icon>
          <v-icon>mdi-login</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title class="subtitle-2">Accedi</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider v-if="!logged" />

      <!-- Products route -->
      <v-list-item @click="isExpanded=false" :to="'/prodotti'" class="py-1 mb-0" link>
        <v-list-item-icon>
          <v-icon>mdi-package-variant</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title class="subtitle-2">Prodotti</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider />

      <!-- Categories route -->
      <v-list-item @click="isExpanded=false" :to="'/categorie'" class="py-1 mb-0" link>
        <v-list-item-icon>
          <v-icon>mdi-format-list-bulleted-square</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title class="subtitle-2">Categorie</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider />

      <!-- Brands route -->
      <v-list-item @click="isExpanded=false" :to="'/marche'" class="py-1 mb-0" link>
        <v-list-item-icon>
          <v-icon>mdi-tag</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title class="subtitle-2">Marche</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider />

      <!-- Logout list item -->
      <v-list-item v-if="logged" @click="logout(); isExpanded = false;" class="py-1 mb-0" link>
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
      isExpanded: false
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
    },
    login() {
      this.$store.commit("openLoginDialog");
    }
  }
};
</script>
