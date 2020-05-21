/*
This mixin contains the required login logic for the admin pages
*/

export default {
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
      this.$router.push("/");
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
            this.$router.push("/");
          });
          // Close login dialog if the user is logged but is not admin
        }
        this.$store.commit("closeLoginDialog");
      }
    },

    isAdmin(isAdminVal) {
      if (!this.logged) {
        this.$router.push("/");
        this.$store.commit("clearAll");
      } else if (this.logged && !isAdminVal) {
        this.$router.push("/");
      }
    },
  },

  methods: {
    openLogin() {
      const checkLogin = (context) => {
        context.dispatch("checkLoginAdmin").catch(() => {
          this.$router.push("/");
        });
      };

      this.$store.commit("openLoginDialog", true);
      this.$store.commit("setActionAfterLogin", checkLogin);
    },
  },
};
