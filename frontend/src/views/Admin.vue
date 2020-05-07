<template>
  <div>
    <h2>Admin page</h2>
  </div>
</template>

<script>
export default {
  name: "Admin",
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
    isAdmin() {
      return this.$store.getters.isAdmin;
    },
  },
  created() {
    // Persistent login dialog (if not logged)
    if (!this.logged) this.openLogin();

    this.$store.dispatch("checkLoginAdmin").catch(() => {
      this.$router.replace("/");
    });
  },

  watch: {
    logged(value) {
      // Persistent login dialog (if not logged)
      if (!value) {
        this.openLogin();
      } else {
        if (!this.isAdmin) {
          this.$store.dispatch("checkLoginAdmin").catch(() => {
            this.$router.replace("/");
          });
          // Close login dialog if the user is logged but is not admin
        }
        this.$store.commit("closeLoginDialog");
      }
    },

    isAdmin(isAdminVal) {
      if (!this.logged) {
        this.$store.commit("clearAll");
        this.$router.replace("/");
      } else if (this.logged && !isAdminVal) {
        this.$router.replace("/");
      }
    },
  },

  methods: {
    openLogin() {
      const checkLogin = (context) => {
        context.dispatch("checkLoginAdmin").catch(() => {
          this.$router.replace("/");
        });
      };

      this.$store.commit("openLoginDialog", true);
      this.$store.commit("setActionAfterLogin", checkLogin);
    },
  },
};
</script>
