<template>
  <div>
    <h2>User cart</h2>
  </div>
</template>

<script>
export default {
  name: "UserCart",
  data() {
    return {
      //
    };
  },
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
    user() {
      return this.$store.getters.user;
    },
  },
  created() {
    // Persistent login dialog (if not logged)
    if (!this.logged) this.$store.commit("openLoginDialog", true);
  },

  watch: {
    logged(value) {
      // Persistent login dialog (if not logged)
      if (!value) this.$store.commit("openLoginDialog", true);
      // Close login dialog if the user is logged
      // Must do this because the view when created doesn't see logged === true and opens the dialog
      else this.$store.commit("closeLoginDialog");
    },
  },
};
</script>
