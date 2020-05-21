import Vue from "vue";
import Vuex from "vuex";
import Axios from "axios";

Vue.use(Vuex);

const store = new Vuex.Store({
  strict: process.env.NODE_ENV !== "production", // Forces to use getters and mutations to access and change state data

  state: {
    // data of the Store
    logged: null,
    isAdmin: null,
    user: null,

    // Login dialog status
    isLoginDialogActive: false,

    // Login dialog persistent
    isPersistentLoginDialog: false,

    // Function to execute after the login
    afterLogin: () => {},
  },

  getters: {
    // "Computed properties" of the Store
    logged(state) {
      return state.logged;
    },

    user(state) {
      return state.user;
    },

    isLoginDialogActive(state) {
      return state.isLoginDialogActive;
    },

    isPersistentLoginDialog(state) {
      return state.isPersistentLoginDialog;
    },

    isAdmin(state) {
      return state.isAdmin;
    },

    afterLogin(state) {
      return state.afterLogin;
    },
  },

  mutations: {
    // "Methods" of the Store, to change the state
    // These shouldn't use async operations, but just change the state.
    // You call a mutation using this.$store.commit("mutation-name");

    // Clear all the data
    clearAll(state) {
      state.logged = false;
      state.isAdmin = false;
      state.user = null;
      state.afterLogin = () => {};
    },

    /**
     * Sets the value of the "logged" property
     * @param {Boolean} isLogged the new value for the "logged" property
     */
    setLogged(state, isLogged) {
      if (isLogged) {
        state.logged = isLogged;
        state.dispatch("getUserData");
      } else state.commit("clearAll");
    },

    /**
     * Sets the value of the "isAdmin" property
     * @param {Boolean} isLogged the new value for the "isAdmin" property
     */
    setIsAdmin(state, isAdmin) {
      state.isAdmin = isAdmin;
    },

    /**
     * Sets the user object value
     * @param {Object} userData data of the user
     */
    setUser(state, userData) {
      state.user = userData;
    },

    /**
     * Open the login dialog (state.isLoginDialogActive = True)
     * @param {Boolean} persistent if the dialog has to be persistent (default = False)
     */
    openLoginDialog(state, persistent = false) {
      state.isPersistentLoginDialog = persistent;
      state.isLoginDialogActive = true;
    },

    /**
     * Close the login dialog (state.isLoginDialogActive = False)
     */
    closeLoginDialog(state) {
      state.isLoginDialogActive = false;
      state.afterLogin = () => {};
    },

    /**
     * Set the function that has to be executed after the login
     * @param {function} action that has to be executed after the login
     */
    setActionAfterLogin(state, action) {
      state.afterLogin = action;
    },
  },

  actions: {
    // "Methods" to perform async operations
    // You call a mutation using this.$store.dispatch("action-name");

    /**
     * Login action
     * @param {Object} credentials An object representing the user credentials ( { email: "", password: "" } )
     * @async because it makes a request to the server
     * @returns a Promise that is resolved if the login is OK, and is rejected if there's an error
     */
    login(context, credentials) {
      return new Promise((resolve, reject) => {
        // POST request to see if the login is OK
        Axios.post(process.env.VUE_APP_API_URL + `login.php`, {
          email: credentials.email,
          password: credentials.password,
        })
          .then(() => {
            // Update the state
            context.dispatch("checkLogin");
            context.dispatch("getUserData");
            context.dispatch("runAfterLoginTask");
            context.commit("closeLoginDialog");
            // Request is successful
            resolve();
          })
          .catch((error) => {
            console.error(error);
            // Error during the login, reject the Promise
            reject();
          });
      });
    },

    /**
     * Logout request to the server
     */
    logout(context) {
      // GET request to login.php?logout
      Axios.get(
        process.env.VUE_APP_API_URL + `login.php?logout`
      ).catch(() => {});
      context.commit("clearAll");
    },

    /**
     * Makes a GET request to login.php to see if the user is logged
     */
    checkLogin(context) {
      Axios.get(process.env.VUE_APP_API_URL + `login.php`)
        .then((response) => {
          context.commit("setLogged", response.data.logged);
        })
        .catch(() => {});
    },

    /**
     * Makes a GET request to login.php?admin to see if the user is logged and is admin
     * @returns a Promise that is resolved if the user is admin, and rejected if it isn't
     */
    checkLoginAdmin(context) {
      return new Promise((resolve, reject) => {
        Axios.get(process.env.VUE_APP_API_URL + `login.php`)
          .then((response) => {
            context.commit("setIsAdmin", response.data.isAdmin);
            context.commit("setLogged", response.data.logged);

            if (response.data.isAdmin) resolve();
            else reject();
          })
          .catch(() => {});
      });
    },

    /**
     * GET the user data from the server and update the state
     * (this action is called after the login)
     */
    getUserData(context) {
      Axios.get(process.env.VUE_APP_API_URL + `user.php`)
        .then((result) => {
          context.commit("setUser", result.data);
        })
        .catch((err) => {
          console.error(err);
          context.dispatch("checkLogin");
        });
    },

    /**
     * Run the function stored in afterLogin
     */
    runAfterLoginTask(context) {
      context.getters.afterLogin(context);
    },
  },
});

export default store;
