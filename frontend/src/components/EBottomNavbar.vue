<template>
  <v-bottom-navigation
    id="bottom-nav"
    app
    dark
    background-color="primary darken-1"
  >
    <v-btn
      class="bottom-nav-btn"
      v-for="item in navItems"
      :key="item.title"
      @click="$router.replace(item.route).catch((err) => {})"
    >
      <span>{{ item.title }}</span>
      <v-icon>{{ item.icon }}</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {
  name: "EBottomNavbar",
  data() {
    return {
      navItems: [
        { title: "Prodotti", icon: "mdi-package-variant", route: "/prodotti" },
        {
          title: "Categorie",
          icon: "mdi-format-list-bulleted-square",
          route: "/categorie",
        },
        { title: "Profilo", icon: "mdi-account", route: "/profilo" },
        { title: "Carrello", icon: "mdi-cart-outline", route: "/carrello" },
      ],
    };
  },

  created() {},
};
</script>

<style scoped>
.bottom-nav-btn.active {
  background-color: white;
}
</style>
