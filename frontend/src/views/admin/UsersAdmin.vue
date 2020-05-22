<template>
  <div v-if="isAdmin">
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn :to="'/admin'" exact text>
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">Torna alla pagina di admin</span>
    </v-btn>

    <br />
    <br />
    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2>Gestione degli utenti</h2>

    <v-spacer class="my-3" />

    <!-------------------->
    <!---- Users list ---->
    <!-------------------->
    <v-data-table
      :headers="headers"
      :items="users"
      sort-by="name"
      :search="search"
      class="elevation-4"
      :loading="fetchingUsers"
      loading-text="Caricamento in corso..."
      no-data-text="Nessun utente trovato!"
      no-results-text="Nessun utente corrisponde ai criteri di ricerca"
    >
      <!---------------------------------------------------------------->
      <!------ Header section (title, search box, Add new button) ------>
      <!---------------------------------------------------------------->
      <template v-slot:top>
        <!---- Title ---->
        <h3 class="pt-4 pl-6">Lista di utenti</h3>
        <v-toolbar flat color="white">
          <!---- Search box ---->
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Cerca"
            single-line
            hide-details
            class="mt-1"
          ></v-text-field>
        </v-toolbar>
      </template>

      <!----------------------------->
      <!------ Actions buttons ------>
      <!----------------------------->
      <template v-slot:item.isAdmin="{ item }">
        <v-row>
          <!---- isAdmin toggler ---->
          <v-switch
            class="mx-auto"
            v-model="item.isAdmin"
            :loading="loading[item.id]"
            dense
            @change="value => updateAdminState(item, value)"
          />
        </v-row>
      </template>
    </v-data-table>

    <v-spacer clas="my-4" />
  </div>
</template>

<script>
import Axios from "axios";

import adminMixin from "@/mixins/adminMixin"; // To manage login

export default {
  name: "UsersAdmin",
  mixins: [adminMixin],
  data() {
    return {
      users: [],
      fetchingUsers: false,
      loading: [],
      search: "",
      headers: [
        // Headers of each column of the table
        {
          // Name column
          text: "Nome",
          align: "start",
          sortable: true,
          value: "name",
          filterable: true
        },
        {
          // Surname column
          text: "Cognome",
          align: "center",
          sortable: true,
          value: "surname",
          filterable: true
        },
        {
          // Email column
          text: "Email",
          align: "center",
          sortable: true,
          value: "email",
          filterable: true
        },
        {
          // Phone number column
          text: "Numero di telefono",
          align: "center",
          sortable: true,
          value: "phoneNumber",
          filterable: true
        },
        {
          // Modify column
          text: "Amministratore",
          value: "isAdmin",
          sortable: false,
          align: "center",
          width: 120,
          filterable: false
        }
      ]
    };
  },

  created() {
    this.getUsers();
  },

  methods: {
    // GET request to user.php?all
    getUsers() {
      this.loading = true;
      Axios.get(process.env.VUE_APP_API_URL + `user.php?all`)
        .then(response => {
          this.users = response.data;
          this.loading = [];
          this.users.forEach(user => (this.loading[user.id] = false));
        })
        .catch(error => {
          console.error(error);
        });
    },
    updateAdminState(user, isAdmin) {
      if (
        // Ask the user to confirm the action
        confirm(
          isAdmin
            ? `Sei sicuro di voler rendere amministratore l'utente ${user.email} ?`
            : `Sei sicuro di rimuovere i permessi di amministrazione all'utente ${user.email} ?`
        )
      ) {
        // Make the request to change the status of the user
        Axios.post(process.env.VUE_APP_API_URL + `user.php?admin`, {
          id: user.id,
          isAdmin: isAdmin
        }).catch(error => {
          alert("Errore durante la modifica: ", error);
          user.isAdmin = !isAdmin;
        });
      }
      // If the user cancels the operation
      else user.isAdmin = !isAdmin;
    }
  }
};
</script>
