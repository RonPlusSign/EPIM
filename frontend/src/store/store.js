import Vue from "vue";
import Vuex from "vuex";
import Axios from "axios";

Vue.use(Vuex);

const store = new Vuex.Store({
  strict: true, // Forces to use getters and mutations to access state data

  state: {
    // data of the Store
    logged: false,
    user: { name: "Pippo" }, // TODO: make a request to user.php to get user info
  },

  getters: {
    // "Computed properties" of the Store
    logged(state) {
      return state.logged;
    },
    user(state) {
      return state.user;
    },
  },

  mutations: {
    // "Methods" of the Store, to change the state
    // These shouldn't use async operations, but just change the state.
    // You call a mutation using this.$store.commit("mutation-name");

    // Clear all the data
    clearAll(state) {
      state.logged = false;
      // state.user = null;
    },

    isLogged(state, isLogged) {
      if (isLogged) {
        // TODO: Make a request to user.php to get user data
        // Use the getUserData action?
        state.logged = isLogged;
      } else state.commit("clearAll");
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
          .then((/*response*/) => {
            // Update the state
            context.commit("logged", true);
            context.dispatch("getUserData");
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
          context.commit("isLogged", response.data.logged);
        })
        .catch(() => {});
      context.commit("clearAll");
    },

    /**
     * GET the user data from the server and update the state
     * (this action is called after the login)
     */
    getUserData(context) {
      console.log("Getting user data...");
      // TODO: Make a request to user.php to get user data
      context;
    },
  },
});

export default store;
