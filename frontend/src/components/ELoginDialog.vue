<!--
Login dialog controlled by the store.
It opens when the property this.$store.getters.isLoginDialogActive is set to True
This also links to the Register dialog.

The dialog can also be persistent (not closable), if this.$store.getters.isPersistentLoginDialog is set to True
  If it is persistent, the user can either login or go to the homepage ("/" route) 

To open the dialog, do:
  this.$store.commit("openLoginDialog");

To open the dialog as persistant, do:
  this.$store.commit("openLoginDialog", true);

To close the dialog, do:
  this.$store.commit("closeLoginDialog");
-->

<template>
  <!---------------------->
  <!------- Dialog ------->
  <!---------------------->
  <v-dialog
    v-model="isDialogActive"
    :persistent="persistent"
    align="center"
    justify="center"
    width="450"
  >
    <v-card class="pb-5" :loading="loading">
      <!---------------------------->
      <!------- Dialog title ------->
      <!---------------------------->
      <v-card-title class="primary darken-1 white--text mb-3">
        <!-- Title -->
        Effettua il login
        <v-spacer />
        <!-- Close dialog button -->
        <v-btn v-if="!persistent" @click="$store.commit('closeLoginDialog')" icon color="white">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-btn v-else @click="closePersistentDialog" icon color="white">
          <v-icon>mdi-home</v-icon>
        </v-btn>
      </v-card-title>
      <!-------------------->
      <!------- Body ------->
      <!-------------------->
      <v-card-text>
        <!---------------------->
        <!------- Inputs ------->
        <!---------------------->
        <v-form ref="loginForm">
          <!-- Email -->
          <v-text-field
            v-model="user.email"
            label="Email"
            name="email"
            :rules="[rules.required, rules.email]"
            prepend-icon="mdi-account"
            type="text"
            required
          />

          <!-- Password -->
          <v-text-field
            v-model="user.password"
            id="password"
            label="Password"
            name="password"
            prepend-icon="mdi-lock"
            :rules="[rules.required]"
            :append-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="isPasswordVisible = !isPasswordVisible"
            :type="isPasswordVisible ? 'text' : 'password'"
            required
          />
        </v-form>

        <!----------------------------->
        <!------- Error message ------->
        <!----------------------------->
        <v-alert
          v-model="error"
          dense
          prominent
          dismissible
          type="error"
          border="top"
          color="red darken-1"
        >Errore durante il login. Hai inserito i dati correttamente?</v-alert>
      </v-card-text>
      <!----------------------->
      <!------- Buttons ------->
      <!----------------------->
      <v-card-actions class="px-4">
        <!-- Register button -->
        <v-btn
          @click="isRegisterDialogActive = !isRegisterDialogActive"
          color="primary white--text"
        >Registrati</v-btn>
        <v-spacer />
        <!-- Do login button -->
        <v-btn @click="handleLogin" :loading="loading" color="secondary">
          <span class="mt-1">Login</span>
          <v-icon class="ml-2">mdi-arrow-right</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>

    <!------------------------------->
    <!------- Register dialog ------->
    <!------------------------------->
    <ERegisterDialog
      :isOpen="isRegisterDialogActive"
      :persistent="persistent"
      @status-changed="(value) => handleRegistrationClosing(value)"
    />
  </v-dialog>
</template>

<script>
import ERegisterDialog from "./ERegisterDialog.vue";

export default {
  name: "ELoginDialog",
  components: { ERegisterDialog },
  data() {
    return {
      // Input values
      user: {
        email: "",
        password: ""
      },

      // Login status
      error: false,
      loading: false,
      isPasswordVisible: false,

      // Register dialog status
      isRegisterDialogActive: false,

      // Input rules
      rules: {
        required: value => !!value || "Inserisci questo parametro",
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "E-mail non valida";
        }
      }
    };
  },

  computed: {
    // Get the dialog status from the store
    isDialogActive: {
      get() {
        return this.$store.getters.isLoginDialogActive;
      },
      set(value) {
        if (value) this.$store.commit("openLoginDialog", this.persistent);
        else this.$store.commit("closeLoginDialog");
      }
    },

    // Get if the dialog is persistent from the store
    persistent() {
      return this.$store.getters.isPersistentLoginDialog;
    }
  },

  watch: {
    isRegisterDialogActive(isActive) {
      // If the register dialog is open, close this dialog
      if (isActive) {
        this.$store.commit("closeLoginDialog");
      }
    },
    isDialogActive(isActive) {
      if (isActive) {
        this.resetForm();
      }
    }
  },

  methods: {
    handleLogin() {
      // Check if the user inputs follow the rules
      if (this.$refs.loginForm.validate()) {
        this.loading = true;

        // Make the login request to the server using the store
        this.$store
          .dispatch("login", this.user)
          .then(() => {
            // Disable loading effect after the server response
            this.loading = false;
            this.$store.commit("closeLoginDialog");
            this.resetForm();
          })
          .catch(() => {
            // Disable loading effect after the server response
            this.loading = false;
            this.error = true;
          });
      }
    },
    closePersistentDialog() {
      this.$router.push("/");
      this.$store.commit("closeLoginDialog");
      this.resetForm();
    },
    handleRegistrationClosing(value) {
      this.isRegisterDialogActive = value;
      if (this.persistent)
        this.$store.commit("openLoginDialog", this.persistent);
    },
    resetForm() {
      this.user.email = "";
      this.user.password = "";

      // Reset the form valdiation if it has been created
      if (this.$refs.loginForm) this.$refs.loginForm.reset();
    }
  }
};
</script>
