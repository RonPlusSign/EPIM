<template>
  <v-container v-if="logged">
    <h2>Profilo utente</h2>
    <br />
    <v-divider />
    <v-row align="center" justify="center" cols="12">
      <!---------------->
      <!----- Name ----->
      <!---------------->
      <v-col xl="3" lg="4" md="5" sm="12">
        <v-row cols="12" align="center" no-gutters>
          <v-col align="center" justify="center" cols="8">
            <div v-if="!editToggler.name">
              <v-row>
                <v-col cols="4" align="center">
                  <!-- Name title -->
                  <span>Nome</span>
                </v-col>
                <v-col cols="8" align="center">
                  <!-- Display the name -->
                  <span class="subtitle-1">{{ editedUser.name }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- Name input (if editToggler.name is true) -->
            <v-text-field
              hide-details="auto"
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
          <v-col align="center" justify="center" cols="4">
            <v-row v-if="!editToggler.name">
              <v-col>
                <v-btn icon color="grey" @click="editToggler.name = true">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </v-col>
            </v-row>
            <div v-if="editToggler.name">
              <v-btn
                icon
                color="primary"
                @click="saveName"
                :loading="loading.name"
              >
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="
                  editToggler.name = false;
                  editedUser.name = user.name;
                "
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <v-col v-if="errors.name" cols="12" align="center" justify="center">
            <!-- Error message on name modify -->
            <v-alert
              v-model="errors.name"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >
              Errore durante la modifica del nome.
            </v-alert>
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
      <v-col xl="3" lg="4" md="5" sm="12">
        <v-row cols="12" align="center" no-gutters>
          <v-col align="center" justify="center" cols="8">
            <div v-if="!editToggler.surname">
              <v-row>
                <v-col cols="4" align="center">
                  <!-- surname title -->
                  <span>Cognome</span>
                </v-col>
                <v-col cols="8" align="center">
                  <!-- Display the surname -->
                  <span class="subtitle-1">{{ editedUser.surname }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- surname input (if editToggler.surname is true) -->
            <v-text-field
              hide-details="auto"
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
          <!-- Edit / confirm / cancel buttons -->
          <v-col align="center" justify="center" cols="4">
            <v-row v-if="!editToggler.surname">
              <v-col>
                <v-btn icon color="grey" @click="editToggler.surname = true">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </v-col>
            </v-row>
            <div v-if="editToggler.surname">
              <v-btn
                icon
                color="primary"
                @click="saveSurname"
                :loading="loading.surname"
              >
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="
                  editToggler.surname = false;
                  editedUser.surname = user.surname;
                "
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <!-- Error message on surname modify -->
          <v-col v-if="errors.surname" align="center" justify="center">
            <v-alert
              v-model="errors.surname"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >
              Errore durante la modifica del cognome.
            </v-alert>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" class="py-0">
        <v-divider />
      </v-col>

      <!------------------------>
      <!----- Phone number ----->
      <!------------------------>
      <v-col cols="12">
        <v-row cols="12" align="center" no-gutters>
          <v-col align="center" justify="center" cols="8">
            <div v-if="!editToggler.phoneNumber">
              <v-row>
                <v-col cols="4" align="center">
                  <!-- phoneNumber title -->
                  <span>Telefono</span>
                  <v-icon class="ml-2 hidden-sm-and-down">mdi-phone</v-icon>
                </v-col>
                <v-col cols="8" align="center">
                  <!-- Display the phoneNumber -->
                  <span class="subtitle-1">{{ editedUser.phoneNumber }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- phoneNumber input (if editToggler.phoneNumber is true) -->
            <v-text-field
              hide-details="auto"
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
          <!-- Edit / confirm / cancel buttons -->
          <v-col align="center" justify="center" cols="4">
            <v-row v-if="!editToggler.phoneNumber">
              <v-col>
                <v-btn
                  icon
                  color="grey"
                  @click="editToggler.phoneNumber = true"
                >
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </v-col>
            </v-row>
            <div v-if="editToggler.phoneNumber">
              <v-btn
                icon
                color="primary"
                @click="savePhoneNumber"
                :loading="loading.phoneNumber"
              >
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="
                  editToggler.phoneNumber = false;
                  editedUser.phoneNumber = user.phoneNumber;
                "
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <!-- Error message on phoneNumber modify -->
          <v-col v-if="errors.phoneNumber" align="center" justify="center">
            <v-alert
              v-model="errors.phoneNumber"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >
              Errore durante la modifica del numero di telefono.
            </v-alert>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" class="py-0">
        <v-divider />
      </v-col>

      <!----------------->
      <!----- Email ----->
      <!----------------->
      <v-col cols="12">
        <v-row cols="12" align="center" no-gutters>
          <v-col align="center" justify="center" cols="8">
            <div v-if="!editToggler.email">
              <v-row>
                <v-col cols="4" align="center">
                  <!-- email title -->
                  <span>
                    Email
                    <v-icon class="ml-2 hidden-sm-and-down">mdi-email</v-icon>
                  </span>
                </v-col>
                <v-col cols="8" align="center">
                  <!-- Display the email -->
                  <span class="subtitle-1">{{ editedUser.email }}</span>
                </v-col>
              </v-row>
            </div>
            <!-- email input (if editToggler.email is true) -->
            <v-text-field
              hide-details="auto"
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
          <!-- Edit / confirm / cancel buttons -->
          <v-col align="center" justify="center" cols="4">
            <v-row v-if="!editToggler.email">
              <v-col>
                <v-btn icon color="grey" @click="editToggler.email = true">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </v-col>
            </v-row>
            <div v-if="editToggler.email">
              <v-btn
                icon
                color="primary"
                @click="saveEmail"
                :loading="loading.email"
              >
                <v-icon>mdi-check-bold</v-icon>
              </v-btn>
              <v-btn
                icon
                color="red"
                @click="
                  editToggler.email = false;
                  editedUser.email = user.email;
                "
              >
                <v-icon>mdi-close-thick</v-icon>
              </v-btn>
            </div>
          </v-col>
          <!-- Error message on email modify -->
          <v-col v-if="errors.email" align="center" justify="center">
            <v-alert
              v-model="errors.email"
              dense
              prominent
              dismissible
              type="error"
              border="top"
              color="red darken-1"
            >
              Errore durante la modifica dell'indirizzo email.
            </v-alert>
          </v-col>
        </v-row>
      </v-col>

      <v-col cols="12" class="py-0">
        <v-divider />
      </v-col>

      <v-col cols="12" align="center" class="my-2">
        <!--------------------------->
        <!---- Go to orders page ---->
        <!--------------------------->
        <v-btn :to="'/profilo/ordini'" color="accent lighten-1">
          <span>Visualizza i tuoi ordini</span>
          <v-icon class="ml-2">mdi-truck</v-icon>
        </v-btn>
      </v-col>

      <v-col cols="12" class="py-0">
        <v-divider />
      </v-col>

      <v-col cols="12">
        <!---------------------------->
        <!---- CRUD address table ---->
        <!---------------------------->
        <EAddressCRUDTable
          :fetchingItems="fetchingAddresses"
          :items="addresses"
          :nameOfItems="'indirizzi'"
          @updated-server-values="fetchAddresses"
        />
      </v-col>
    </v-row>

    <v-divider />
  </v-container>
</template>

<script>
import Axios from "axios";
import userMixin from "@/mixins/userMixin";

import EAddressCRUDTable from "@/components/user/EAddressCRUDTable.vue";

export default {
  name: "UserProfile",
  mixins: [userMixin],
  components: { EAddressCRUDTable },

  data() {
    return {
      fetchingAddresses: false,
      editedUser: {},
      addresses: [],
      editToggler: {
        name: false,
        surname: false,
        phoneNumber: false,
        email: false,
      },
      loading: {
        name: false,
        surname: false,
        phoneNumber: false,
        email: false,
      },
      errors: {
        name: false,
        surname: false,
        phoneNumber: false,
        email: false,
      },
      // Input rules
      rules: {
        required: (value) => !!value || "Inserisci questo parametro",
        email: (value) => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || "E-mail non valida";
        },
        phoneNumber: (value) => {
          const pattern = /^[0-9]{9,10}$/;
          return (
            pattern.test(value.trim()) ||
            "Il numero inserito deve contenere solo 9 o 10 numeri"
          );
        },
      },
    };
  },

  created() {
    if (this.logged) {
      this.editedUser = this.user ? Object.assign({}, this.user) : {};

      this.fetchAddresses();
    }
  },

  watch: {
    user() {
      this.editedUser = Object.assign({}, this.user);

      this.fetchAddresses();
    },
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
          .catch((error) => {
            if (error.response.status === 403)
              this.$store
                .dispatch("checkLogin")
                .catch(this.$store.commit("openLoginDialog", true));
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
            this.errors.name = false;
          })
          .catch((err) => {
            this.loading.name = false;
            this.errors.name = true;
            console.error(err);
          });
      }
    },

    saveSurname() {
      if (this.$refs.surnameInput.validate()) {
        this.loading.surname = true;
        this.saveParam({ surname: this.editedUser.surname })
          .then(() => {
            this.loading.surname = false;
            this.editToggler.surname = false;
            this.errors.surname = false;
          })
          .catch((err) => {
            this.loading.surname = false;
            this.errors.surname = true;
            console.error(err);
          });
      }
    },

    savePhoneNumber() {
      if (this.$refs.phoneNumberInput.validate()) {
        this.loading.phoneNumber = true;
        this.saveParam({ phoneNumber: this.editedUser.phoneNumber })
          .then(() => {
            this.loading.phoneNumber = false;
            this.editToggler.phoneNumber = false;
            this.errors.phoneNumber = false;
          })
          .catch((err) => {
            this.loading.phoneNumber = false;
            this.errors.phoneNumber = true;
            console.error(err);
          });
      }
    },

    saveEmail() {
      if (this.$refs.emailInput.validate()) {
        this.loading.email = true;
        this.saveParam({ email: this.editedUser.email })
          .then(() => {
            this.loading.email = false;
            this.editToggler.email = false;
            this.errors.email = false;
          })
          .catch((err) => {
            this.loading.email = false;
            this.errors.email = true;
            console.error(err);
          });
      }
    },

    fetchAddresses() {
      this.fetchingAddresses = true;
      Axios.get(process.env.VUE_APP_API_URL + `user.php?address`)
        .then((response) => {
          this.addresses = response.data;
          this.fetchingAddresses = false;
        })
        .catch((err) => {
          if (err.response.status === 403)
            this.$store
              .dispatch("checkLogin")
              .catch(this.$store.commit("openLoginDialog", true));
          else console.error(err);
          this.fetchingAddresses = false;
        });
    },
  },
};
</script>
