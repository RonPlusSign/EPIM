<!--
This component requires the "isOpen" prop to get its status.
There's the needing to handle 2 custom events:
- "status-changed" (open or closed) event
"logged" event (user login went ok)

example:
<ELoginDialog :isOpen="isLoginDialogActive" @logged="(value) => {this.logged = value}" @status-changed="(value) => { this.isLoginDialogActive = value}" />
-->

<template>
  <!---------------------->
  <!------- Dialog ------->
  <!---------------------->
  <v-dialog
    align="center"
    justify="center"
    v-model="isDialogActive"
    width="450"
  >
    <v-card class="pb-5" :loading="loading">
      <!--------------------->
      <!------- Title ------->
      <!--------------------->
      <v-card-title class="primary darken-1 white--text mb-3"
        >Effettua il login
        <v-spacer />
        <v-btn @click="isDialogActive = !isDialogActive" icon color="white"
          ><v-icon>mdi-close</v-icon></v-btn
        >
      </v-card-title>
      <!-------------------->
      <!------- Body ------->
      <!-------------------->
      <v-card-text>
        <!---------------------->
        <!------- Inputs ------->
        <!---------------------->
        <v-form ref="loginForm">
          <v-text-field
            v-model="user.email"
            label="Email"
            name="email"
            :rules="[rules.required, rules.email]"
            prepend-icon="mdi-account"
            type="text"
            required
          />

          <v-text-field
            v-model="user.password"
            id="password"
            label="Password"
            name="password"
            prepend-icon="mdi-lock"
            :rules="[rules.required]"
            :append-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="() => (isPasswordVisible = !isPasswordVisible)"
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
        >
          Questo account non esiste. Hai inserito i dati correttamente?
        </v-alert>
      </v-card-text>
      <!----------------------->
      <!------- Buttons ------->
      <!----------------------->
      <v-card-actions class="px-4">
        <v-btn
          @click="isRegisterDialogActive = !isRegisterDialogActive"
          color="blue white--text"
          >Registrati</v-btn
        >
        <v-spacer />
        <v-btn @click="handleLogin()" :loading="loading" color="primary">
          <span class="mt-1">Login</span>
          <v-icon class="ml-2">mdi-arrow-right</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>
    <ERegisterDialog
      :isOpen="isRegisterDialogActive"
      @registered="
        (value) => {
          this.emitLogged();
        }
      "
      @status-changed="
        (value) => {
          this.isRegisterDialogActive = value;
        }
      "
    />
  </v-dialog>
</template>

<script>
import ERegisterDialog from "./ERegisterDialog.vue";
import Axios from "axios";

export default {
  name: "ELoginDialog",
  props: {
    isOpen: { type: Boolean, required: true },
  },
  components: {
    ERegisterDialog,
  },
  data() {
    return {
      user: {
        email: "",
        password: "",
      },
      loading: false,
      isDialogActive: false,
      isPasswordVisible: false,
      isRegisterDialogActive: false,
      error: false,
      rules: {
        required: (value) => !!value || "Inserisci questo parametro",
        email: (value) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "E-mail non valida";
        },
      },
    };
  },
  watch: {
    isOpen() {
      this.isDialogActive = this.isOpen;
    },
    isDialogActive() {
      this.$emit("status-changed", this.isDialogActive);
    },
    isRegisterDialogActive(value) {
      if (value) {
        this.isDialogActive = false;
      }
    },
  },

  methods: {
    handleLogin() {
      if (this.$refs.loginForm.validate()) {
        this.loading = true;

        Axios.post(process.env.VUE_APP_API_URL + `login.php`, {
          email: this.user.email,
          password: this.user.password,
        })
          .then((/*response*/) => {
            // Disable loading effect after the server response
            this.loading = false;
            this.isDialogActive = false;

            this.emitLogged();
          })
          .catch((error) => {
            // Disable loading effect after the server response
            this.loading = false;
            this.error = true;
            console.error(error);
          });
      }
    },
    emitLogged() {
      this.$emit("logged", true);
    },
  },
};
</script>
