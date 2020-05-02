<!--
This component requires the "isOpen" prop to get its status.
The component emits a custom event:
- "status-changed" (open or closed) event

- you can also pass the "persistent" property (default = False).
If it is set to true, the dialog won't close until the user does the login.

example:
<ELoginDialog :isOpen="isLoginDialogActive" @status-changed="(value) => { this.isLoginDialogActive = value}" />
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
        <v-btn
          v-if="!persistent"
          @click="isDialogActive = !isDialogActive"
          icon
          color="white"
          ><v-icon>mdi-close</v-icon></v-btn
        >
        <v-btn
          v-else
          @click="
            $router.replace('/');
            isDialogActive = false;
          "
          icon
          color="white"
          ><v-icon>mdi-home</v-icon></v-btn
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
      :persistent="persistent"
      @status-changed="
        (value) => {
          this.isRegisterDialogActive = value;
          if (persistent) this.isDialogActive = true;
        }
      "
    />
  </v-dialog>
</template>

<script>
import ERegisterDialog from "./ERegisterDialog.vue";

export default {
  name: "ELoginDialog",
  components: {
    ERegisterDialog,
  },
  props: {
    isOpen: { type: Boolean, required: true },
    persistent: { type: Boolean, default: false },
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

        this.$store
          .dispatch("login", this.user)
          .then(() => {
            // Disable loading effect after the server response
            this.loading = false;
            this.isDialogActive = false;
          })
          .catch(() => {
            // Disable loading effect after the server response
            this.loading = false;
            this.error = true;
          });
      }
    },
  },
};
</script>
