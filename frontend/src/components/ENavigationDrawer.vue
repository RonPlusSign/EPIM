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
        <span class="secondary--text">{{ user ? user.name : "" }}</span
        >!
      </span>
      <span v-else>Benvenuto!</span>
    </v-container>
    <v-divider />

    <v-list dense nav class="py-0">
      <!----------------------->
      <!---- List of items ---->
      <!----------------------->
      <div v-for="item in drawerItems" :key="item.title">
        <v-list-item
          v-if="item.visible"
          @click="item.action ? item.action() : null"
          :to="item.route ? item.route : ''"
          class="py-1 mb-0"
          link
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content class="py-0">
            <v-list-item-title class="subtitle-2">{{
              item.title
            }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider v-if="item.visible" />
      </div>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "ENavigationDrawer",
  props: {
    drawerExpanded: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      isExpanded: false,
    };
  },
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
    user() {
      return this.$store.getters.user;
    },

    drawerItems() {
      return [
        {
          title: "Home",
          action: () => (this.isExpanded = false),
          route: "/",
          icon: "mdi-home",
          visible: true,
        },
        {
          title: "Il tuo profilo",
          action: () => (this.isExpanded = false),
          route: "/profilo",
          icon: "mdi-account",
          visible: this.logged,
        },
        {
          title: "Accedi",
          action: () => {
            this.login();
            this.isExpanded = false;
          },
          route: null,
          icon: "mdi-login",
          visible: !this.logged,
        },
        {
          title: "Prodotti",
          action: () => (this.isExpanded = false),
          route: "/prodotti",
          icon: "mdi-package-variant",
          visible: true,
        },
        {
          title: "Categorie",
          action: () => (this.isExpanded = false),
          route: "/categorie",
          icon: "mdi-format-list-bulleted-square",
          visible: true,
        },
        {
          title: "Marche",
          action: () => (this.isExpanded = false),
          route: "/marche",
          icon: "mdi-tag",
          visible: true,
        },

        {
          title: "Logout",
          action: () => {
            this.logout();
            this.isExpanded = false;
          },
          route: null,
          icon: "mdi-logout",
          visible: this.logged,
        },
      ];
    },
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
    },
  },

  methods: {
    logout() {
      this.$store.dispatch("logout");

      this.$router.push("/");
    },
    login() {
      this.$store.commit("openLoginDialog");
    },
  },
};
</script>
