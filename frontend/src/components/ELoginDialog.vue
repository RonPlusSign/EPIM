<!--
This component requires the "isOpen" prop to get its status.
There's the needing to handle the custom "status-changed" event.

example:
<ELoginDialog :isOpen="isLoginDialogActive" @status-changed="(value) => { this.isLoginDialogActive = value}" />
-->

<template>
  <!---------------------->
  <!------- Dialog ------->
  <!---------------------->
  <v-dialog align="center" justify="center" v-model="isDialogActive" width="400">
    <v-card class="pb-5" :loading="loading">
      <!--------------------->
      <!------- Title ------->
      <!--------------------->
      <v-card-title class="primary darken-1 white--text mb-3">Effettua il login</v-card-title>
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
            name="login"
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
            type="password"
            required
          />
        </v-form>
      </v-card-text>
      <!----------------------->
      <!------- Buttons ------->
      <!----------------------->
      <v-card-actions class="px-4">
        <v-btn color="blue white--text">Registrati</v-btn>
        <v-spacer />
        <v-btn @click="handleLogin()" :loading="loading" color="primary">
          <span class="mt-1">Login</span>
          <v-icon class="ml-2">mdi-arrow-right</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Axios from "axios";

export default {
  name: "ELoginDialog",
  props: {
    isOpen: { type: Boolean, required: true }
  },
  data() {
    return {
      user: {
        email: "",
        password: ""
      },
      loading: false,
      isDialogActive: false,
      rules: {
        required: value => !!value || "Inserisci questo parametro",
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "E-mail non valida";
        }
      }
    };
  },
  watch: {
    isOpen() {
      this.isDialogActive = this.isOpen;
    },
    isDialogActive() {
      this.$emit("status-changed", this.isDialogActive);
    }
  },

  methods: {
    handleLogin() {
      if (this.$refs.loginForm.validate()) {
        this.loading = true;

        Axios.post(process.env.VUE_APP_API_URL + `login.php`, {
          email: this.user.email,
          password: this.user.password
        }).then(response => {
          console.log(response.status);
        });
        // TODO: make a POST request to login.php and wait for its response

        // Disable loading effect after the server response
        // this.loading = false;
      }
    }
  }
};
</script>