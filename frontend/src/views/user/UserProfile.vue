<template>
  <v-container>
    <h2>Profilo utente</h2>
    <br />
    <v-divider />
    <v-row align="center" justify="center" cols="12">
      <!---------------->
      <!----- Name ----->
      <!---------------->
      <v-col xl="3" lg="4" md="5" sm="12" xs="12" class="py-0 my-2">
        <v-row cols="12">
          <v-col align="center" justify="center" class="py-0" cols="8">
            <div v-if="!editToggler.name">
              <v-row>
                <v-col cols="3" class="pt-4">
                  <!-- Name title -->
                  <span>Nome</span>
                </v-col>
                <v-col cols="9">
                  <!-- Display the name -->
                  <span class="title">{{ editedUser.name }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- Name input (if editToggler.name is true) -->
            <v-text-field
              filled
              ref="nameInput"
              v-model="editedUser.name"
              v-else
              name="name"
              :loading="loading.name"
              :rules="[rules.required]"
              class="pt-0"
            />
          </v-col>

          <!-- Edit / confirm / cancel buttons -->
          <v-col align="center" justify="center" cols="4" class="py-0">
            <v-btn
              v-if="!editToggler.name"
              icon
              color="grey"
              class="mt-3"
              @click="editToggler.name = true"
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <div v-if="editToggler.name" class="pt-2">
              <v-btn icon color="primary" @click="saveName" :loading="loading.name">
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="editToggler.name = false; editedUser.name = user.name;"
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <v-col align="center" justify="center">
            <!-- Error message on name modify -->
            <v-alert
              v-model="errors.name"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >Errore durante la modifica del nome.</v-alert>
          </v-col>
        </v-row>
      </v-col>
      <!-- Vertical divider (large screens) -->
      <v-col cols="1" class="hidden-sm-and-down py-0 my-2">
        <v-spacer />
        <v-divider class="py-8 mx-lg-9 mx-md-3" vertical />
        <v-spacer />
      </v-col>
      <!-- Horizontal divider (small screens) -->
      <v-col cols="12" class="d-md-none py-0">
        <v-divider />
      </v-col>

      <!------------------->
      <!----- Surname ----->
      <!------------------->
      <v-col xl="3" lg="4" md="5" sm="12" xs="12" class="py-0 my-2">
        <v-row cols="12">
          <v-col align="center" justify="center" class="py-0" cols="8">
            <div v-if="!editToggler.surname">
              <v-row>
                <v-col cols="3" class="pt-4">
                  <!-- surname title -->
                  <span>Cognome</span>
                </v-col>
                <v-col cols="9">
                  <!-- Display the surname -->
                  <span class="title">{{ editedUser.surname }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- surname input (if editToggler.surname is true) -->
            <v-text-field
              filled
              ref="surnameInput"
              v-model="editedUser.surname"
              v-else
              surname="surname"
              :loading="loading.surname"
              :rules="[rules.required]"
              class="pt-0"
            />
          </v-col>
          <v-col align="center" justify="center" cols="4" class="py-0">
            <!-- Edit / confirm / cancel buttons -->
            <v-btn
              v-if="!editToggler.surname"
              icon
              color="grey"
              class="mt-3"
              @click="editToggler.surname = true"
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <div v-if="editToggler.surname" class="pt-2">
              <v-btn icon color="primary" @click="saveSurname" :loading="loading.surname">
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="editToggler.surname = false; editedUser.surname = user.surname;"
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <!-- Error message on surname modify -->
          <v-col align="center" justify="center">
            <v-alert
              v-model="errors.surname"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >Errore durante la modifica del cognome.</v-alert>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" class="py-0">
        <v-divider />
      </v-col>
      <!------------------------>
      <!----- Phone number ----->
      <!------------------------>
      <v-col cols="12" class="py-0 my-2">
        <v-row cols="12">
          <v-col align="center" justify="center" class="py-0" cols="8">
            <div v-if="!editToggler.phoneNumber">
              <v-row>
                <v-col cols="3" class="pt-4">
                  <!-- phoneNumber title -->
                  <span>Numero di telefono</span>
                </v-col>
                <v-col cols="9">
                  <!-- Display the phoneNumber -->
                  <span class="title">{{ editedUser.phoneNumber }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- phoneNumber input (if editToggler.phoneNumber is true) -->
            <v-text-field
              filled
              ref="phoneNumberInput"
              v-model="editedUser.phoneNumber"
              v-else
              phoneNumber="phoneNumber"
              :loading="loading.phoneNumber"
              :rules="[rules.required, rules.phoneNumber]"
              class="pt-0"
            />
          </v-col>
          <v-col align="center" justify="center" cols="4" class="py-0">
            <!-- Edit / confirm / cancel buttons -->
            <v-btn
              v-if="!editToggler.phoneNumber"
              icon
              color="grey"
              class="mt-3"
              @click="editToggler.phoneNumber = true"
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <div v-if="editToggler.phoneNumber" class="pt-2">
              <v-btn icon color="primary" @click="savePhoneNumber" :loading="loading.phoneNumber">
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="editToggler.phoneNumber = false; editedUser.phoneNumber = user.phoneNumber;"
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <!-- Error message on phoneNumber modify -->
          <v-col align="center" justify="center">
            <v-alert
              v-model="errors.phoneNumber"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >Errore durante la modifica del numero di telefono.</v-alert>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" class="py-0">
        <v-divider />
      </v-col>
      <!----------------->
      <!----- Email ----->
      <!----------------->
      <v-col cols="12" class="py-0 my-2">
        <v-row cols="12">
          <v-col align="center" justify="center" class="py-0" cols="8">
            <div v-if="!editToggler.email">
              <v-row>
                <v-col cols="3" class="pt-4">
                  <!-- email title -->
                  <span>Numero di telefono</span>
                </v-col>
                <v-col cols="9">
                  <!-- Display the email -->
                  <span class="title">{{ editedUser.email }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- email input (if editToggler.email is true) -->
            <v-text-field
              filled
              ref="emailInput"
              v-model="editedUser.email"
              v-else
              email="email"
              :loading="loading.email"
              :rules="[rules.required, rules.email]"
              class="pt-0"
            />
          </v-col>
          <v-col align="center" justify="center" cols="4" class="py-0">
            <!-- Edit / confirm / cancel buttons -->
            <v-btn
              v-if="!editToggler.email"
              icon
              color="grey"
              class="mt-3"
              @click="editToggler.email = true"
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <div v-if="editToggler.email" class="pt-2">
              <v-btn icon color="primary" @click="saveEmail" :loading="loading.email">
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="editToggler.email = false; editedUser.email = user.email;"
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <!-- Error message on email modify -->
          <v-col align="center" justify="center">
            <v-alert
              v-model="errors.email"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >Errore durante la modifica dell'indirizzo email.</v-alert>
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <v-divider />
  </v-container>
</template>

<script>
import Axios from "axios";

export default {
  name: "UserProfile",
  data() {
    return {
      editedUser: {},
      editToggler: {
        name: false,
        surname: false,
        phoneNumber: false,
        email: false
      },
      loading: {
        name: false,
        surname: false,
        phoneNumber: false,
        email: false
      },
      errors: {
        name: false,
        surname: false,
        phoneNumber: false,
        email: false
      },
      // Input rules
      rules: {
        required: value => !!value || "Inserisci questo parametro",
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "E-mail non valida";
        },
        phoneNumber: value => {
          const pattern = /^[0-9]{9,10}$/;
          return (
            pattern.test(value.trim()) ||
            "Il numero inserito deve contenere solo 9 o 10 numeri"
          );
        }
      }
    };
  },
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
    user() {
      return this.$store.getters.user;
    }
  },
  created() {
    // Persistent login dialog (if not logged)
    if (!this.logged) this.$store.commit("openLoginDialog", true);

    this.editedUser = this.user ? Object.assign({}, this.user) : {};
  },

  watch: {
    logged(value) {
      // Persistent login dialog (if not logged)
      if (!value) this.$store.commit("openLoginDialog", true);
      // Close login dialog if the user is logged
      // Must do this because the view when created doesn't see logged === true and opens the dialog
      else this.$store.commit("closeLoginDialog");
    },

    user() {
      this.editedUser = Object.assign({}, this.user);
    }
  },

  methods: {
    saveParam(param) {
      return new Promise((resolve, reject) => {
        // POST request to see if the login is OK
        Axios.patch(process.env.VUE_APP_API_URL + `user.php`, param)
          .then((/*response*/) => {
            this.$store.dispatch("getUserData");

            // Request is successful
            resolve();
          })
          .catch(error => {
            // Error during the login, reject the Promise
            reject(error);
          });
      });
    },

    saveName() {
      if (this.$refs.nameInput.validate()) {
        this.loading.name = true;
        this.saveParam({ name: this.editedUser.name })
          .then(() => {
            this.loading.name = false;
            this.editToggler.name = false;
          })
          .catch(err => {
            this.errors.name = true;

            console.error(err);
          });
      }
    },

    saveSurame() {
      if (this.$refs.surnameInput.validate()) {
        this.loading.surname = true;
        this.saveParam({ name: this.editedUser.surname })
          .then(() => {
            this.loading.surname = false;
            this.editToggler.surname = false;
          })
          .catch(err => {
            this.errors.surname = true;
            console.error(err);
          });
      }
    },

    savePhoneNumber() {
      if (this.$refs.phoneNumberInput.validate()) {
        this.loading.phoneNumber = true;
        this.saveParam({ name: this.editedUser.phoneNumber })
          .then(() => {
            this.loading.phoneNumber = false;
            this.editToggler.phoneNumberInput = false;
          })
          .catch(err => {
            this.errors.phoneNumber = true;
            console.error(err);
          });
      }
    },

    saveEmail() {
      if (this.$refs.emailInput.validate()) {
        this.loading.email = true;
        this.saveParam({ name: this.editedUser.email })
          .then(() => {
            this.loading.email = false;
            this.editToggler.emailInput = false;
          })
          .catch(err => {
            this.errors.email = true;
            console.error(err);
          });
      }
    }
  }
};
</script>
