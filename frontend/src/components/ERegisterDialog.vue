<!--
This component requires the "isOpen" prop to get its status.
There's the needing to handle a custom event:
- "status-changed" (open or closed) event

example:
<ERegisterDialog :isOpen="isRegisterDialogActive" @status-changed="(value) => { this.isRegisterDialogActive = value}" />
-->

<template>
  <!---------------------->
  <!------- Dialog ------->
  <!---------------------->
  <v-dialog
    v-model="isDialogActive"
    align="center"
    justify="center"
    width="600"
  >
    <v-card class="pb-5" :loading="loading">
      <!--------------------->
      <!------- Title ------->
      <!--------------------->

      <v-card-title class="primary darken-1 white--text mb-3 pl-3">
        <!-- Title -->
        Registrati
        <v-spacer />
        <!-- Close button -->
        <v-btn @click="isDialogActive = !isDialogActive" icon color="white">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-card-title>
      <!-------------------->
      <!------- Body ------->
      <!-------------------->
      <v-card-text>
        <!---------------------->
        <!------- Inputs ------->
        <!---------------------->
        <v-form ref="registerForm">
          <!------- Name ------->
          <v-text-field
            v-model="user.name"
            label="Nome"
            name="name"
            :rules="[rules.required]"
            prepend-icon="mdi-account"
            type="text"
            required
            @keyup.enter.native="handleRegistration"
          />
          <!------- Surname ------->
          <v-text-field
            v-model="user.surname"
            label="Cognome"
            name="surname"
            :rules="[rules.required]"
            prepend-icon="mdi-account-multiple"
            type="text"
            required
            @keyup.enter.native="handleRegistration"
          />
          <!------- Email ------->
          <v-text-field
            v-model="user.email"
            label="Email"
            name="email"
            :rules="[rules.required, rules.email]"
            prepend-icon="mdi-email"
            type="text"
            required
            @keyup.enter.native="handleRegistration"
          />

          <!------- Phone number ------->
          <v-text-field
            v-model="user.phoneNumber"
            label="Numero di telefono"
            name="phoneNumber"
            :rules="[rules.required, rules.phoneNumber]"
            prepend-icon="mdi-phone"
            type="text"
            required
            @keyup.enter.native="handleRegistration"
          />

          <!------- Password ------->
          <v-text-field
            v-model="user.password"
            id="password"
            label="Password"
            name="password"
            prepend-icon="mdi-lock"
            :rules="[rules.required, rules.noSpaces, rules.password]"
            :append-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="isPasswordVisible = !isPasswordVisible"
            :type="isPasswordVisible ? 'text' : 'password'"
            required
            @keyup.enter.native="handleRegistration"
          />

          <!------- Confirm password ------->
          <v-text-field
            v-model="user.confirmPassword"
            id="confirmPassword"
            label="Conferma password"
            name="confirmPassword"
            prepend-icon="mdi-lock-reset"
            :rules="[rules.required, rules.confirmPassword]"
            :append-icon="isPasswordVisible ? 'mdi-eye' : 'mdi-eye-off'"
            @click:append="isPasswordVisible = !isPasswordVisible"
            :type="isPasswordVisible ? 'text' : 'password'"
            required
            @keyup.enter.native="handleRegistration"
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
          >{{ errorMsg }}</v-alert
        >
      </v-card-text>
      <!----------------------->
      <!------- Buttons ------->
      <!----------------------->
      <v-card-actions class="px-5">
        <v-spacer />
        <v-btn @click="handleRegistration" :loading="loading" color="secondary">
          <span class="mt-1">Registrati</span>
          <v-icon class="ml-2">mdi-arrow-right</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Axios from "axios";

export default {
  name: "ERegisterDialog",
  props: {
    isOpen: { type: Boolean, required: true },
  },
  data() {
    return {
      user: {
        name: "",
        surname: "",
        email: "",
        phoneNumber: "",
        password: "",
        confirmPassword: "",
      },
      loading: false,
      isDialogActive: false,
      isPasswordVisible: false,
      error: false,
      errorMsg: "Errore durante la registrazione",
      rules: {
        required: (value) => !!value || "Inserisci questo parametro",
        email: (value) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "E-mail non valida";
        },
        password: (value) => {
          const pattern = /^.{8,}$/;
          return (
            pattern.test(value) ||
            "La password deve contenere almeno 8 caratteri"
          );
        },
        noSpaces: (value) => {
          const pattern = /\s/;
          return !pattern.test(value) || "La password non deve contenere spazi";
        },
        confirmPassword: (value) =>
          value === this.user.password || "La password non corrisponde",
        phoneNumber: (value) => {
          const pattern = /^[0-9]{7,12}$/;
          return (
            pattern.test(value.trim()) ||
            "Il numero inserito deve essere lungo dalle 7 a 12 cifre"
          );
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
  },

  methods: {
    handleRegistration() {
      if (this.$refs.registerForm.validate()) {
        this.loading = true;

        // Send registration request
        Axios.post(process.env.VUE_APP_API_URL + `user.php?signup`, {
          name: this.user.name,
          surname: this.user.surname,
          email: this.user.email,
          phoneNumber: this.user.phoneNumber.trim(),
          password: this.user.password,
        })
          .then((/*response*/) => {
            // Disable loading effect after the server response
            this.loading = false;
            this.isDialogActive = false;
            this.$store.dispatch("checkLogin");
            this.$store.dispatch("getUserData");
          })
          .catch((error) => {
            // Disable loading effect after the server response
            this.loading = false;
            this.error = true;
            if (error.body.error) this.errorMsg = error.body.error;
            console.error(error);
          });
      }
    },
  },
};
</script>
