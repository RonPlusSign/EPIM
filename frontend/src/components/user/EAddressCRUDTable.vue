
<!-- WARNING! this component is not working and is not used at the moment -->

<!--
This component is a data table that does CRUD requests of  user addresses.
C = create (POST request to the endpoint)
R = Read (shows the values passed as props)
U = Updates a value (PATCH)
D = Deletes a value (DELETE)

Props:
- "addresses" (Array, required): the array of elements to work on
    Format of the array must contain objects like:
      { id: Number, name: String }
    This type of objects is the one we have for Brands and Categories, so this component works with both

- "fetchingItems" (Boolean, required): true = currently fetching data from a server, show a loading effect

- "endpoint" (String, required): endpoint where to do the CRUD requests (for example "brands.php")
  (this component only does DELETE requests, see EAddressCRUDListDialog to see CREATE and PATCH requests)

- "nameOfItems" (String, required): name of the items (for example "marche" or "categorie")

Events:
- "updated-server-values": evoked after a successful request to the server
  This is necessary because the parent component can then fetch the updated list of items from the server

-->

<!--

<template>
  <v-container>
    <v-row cols="12" justify="center">
      <v-col xl="8" lg="8" md="8" sm="9" xs="12">
        <! ---------------------- -- >
        <! ------ Data table ---- -- >
        <! ---------------------- -- >
        <v-data-table
          :headers="headers"
          :items="filteredItems"
          sort-by="name"
          class="elevation-4"
          :loading="fetchingItems"
          loading-text="Caricamento in corso..."
          no-data-text="Nessun elemento disponibile!"
        >
          <! -------------------------------------------------------------- -- >
          <! ------ Header section (title, search box, Add new button) ---- -- >
          <! -------------------------------------------------------------- -- >
          <template v-slot:top>
            <! ---- Title -- -- >
            <h3 class="pt-4 pl-6">Lista di {{ nameOfItems }}</h3>
            <v-toolbar flat color="white">
              <v-row cols="12">
                <v-col xl="6" lg="6" md="6" sm="6" xs="6">
                  <! ---- Search box -- -- >
                  <v-text-field
                    v-model="filter"
                    append-icon="mdi-magnify"
                    label="Cerca"
                    single-line
                    hide-details
                    class="mt-1"
                  ></v-text-field>
                </v-col>
                <v-spacer />
                <v-col align="right" xl="6" lg="6" md="6" sm="6" xs="6">
                  <! ---- "Add new" button -- -- >
                  <v-btn @click="dialog = true" dark color="blue">
                    <span class="subtitle-2">Aggiungi</span>
                    <v-icon class="ml-2">mdi-plus</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </v-toolbar>
          </template>

          <! --------------------------- -- >
          <! ------ Actions buttons ---- -- >
          <! --------------------------- -- >
          <template v-slot:item.modify="{ item }">
            <! ---- "Edit" button -- -- >
            <v-btn fab icon @click="editItem(item)" class="editBtn">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
          </template>

          <template v-slot:item.delete="{ item }">
            <! ---- "Delete" button -- -- >
            <v-btn fab icon @click="deleteItem(item)" class="deleteBtn">
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
        </v-data-table>
      </v-col>
    </v-row>
    <v-spacer class="mb-10" />

    <! ----------------------------- -- >
    <! ---- Add new/Modify dialog -- -- >
    <! ----------------------------- -- >
    <EAdminCRUDListDialog
      :state="dialog"
      :title="dialogTitle"
      :endpoint="endpoint"
      @state-changed="(value) => (value ? (dialog = value) : close())"
      :startingObject="editedIndex !== -1 ? items[editedIndex] : null"
      @updated-server-values="$emit('updated-server-values')"
    />
  </v-container>
</template>

<script>
import Axios from "axios";
import EAdminCRUDListDialog from "@/components/admin/EAdminCRUDListDialog.vue";

export default {
  name: "EAdminCRUDList",
  components: { EAddressCRUDListDialog },
  data: () => ({
    dialog: false, // Controls the dialog status (open/close)
    filter: "", // Filter for search (by name)
    headers: [
      // Headers of each column of the table
      {
        /*
                "id": 13, // Address id
                "city": 234, // City id
                "street": "Via dei Polli",
                "houseNumber": 123,
                "postalCode": 54100,
                "phoneNumber": "123123123" // Phone number associated with that address
            */
        // Name column
        text: "Città",
        align: "start",
        sortable: true,
        value: "name",
        filterable: true
      },
      {
        // Street column
        text: "Via",
        sortable: true,
        value: "name",
        filterable: true
      },
      // TODO: Add all the other columns
      {
        // Modify column
        text: "Modifica",
        value: "modify",
        sortable: false,
        align: "center",
        width: 50,
        filterable: false
      },
      {
        // Delete column
        text: "Elimina",
        value: "delete",
        sortable: false,
        align: "center",
        width: 50,
        filterable: false
      }
    ],
    editedIndex: -1 // Index of the element that the user is modifying (Modified by the dialog)
  }),

  props: {
    items: {
      type: Array,
      required: true

      /*
      Example of list:
       [
            {
                "id": 13, // Address id
                "city": 234, // City id
                "street": "Via dei Polli",
                "houseNumber": 123,
                "postalCode": 54100,
                "phoneNumber": "123123123" // Phone number associated with that address
            },
            {
                "city": 123, // City id
                "street": "Via delle galline",
                "houseNumber": 321,
                "postalCode": 21300,
                "phoneNumber": "456456456" // Phone number associated with that address
            }
        ]
       */
    },
    fetchingItems: {
      type: Boolean,
      required: true
    },
    endpoint: {
      type: String,
      required: true
    },
    nameOfItems: {
      type: String,
      required: true
    }
  },

  computed: {
    dialogTitle() {
      return this.editedIndex === -1
        ? `Crea ${this.nameOfItems}`
        : `Modifica ${this.nameOfItems}`;
    },
    filteredItems() {
      // Filter items by the name
      return this.items.filter(item =>
        item.name.toUpperCase().includes(this.filter.toUpperCase())
      );
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  methods: {
    editItem(item) {
      // Open the dialog to modify an element
      this.editedIndex = this.items.indexOf(item);
      this.dialog = true;
    },

    deleteItem(item) {
      if (confirm(`Sei sicuro di voler eliminare ${item.name}?`)) {
        // DELETE request to the server
        Axios.delete(process.env.VUE_APP_API_URL + this.endpoint, {
          params: { id: item.id }
        })
          .then(() => {
            this.$emit("updated-server-values");
          })
          .catch(err => {
            console.error(err);
          });
      }
    },

    close() {
      // Close the dialog
      this.dialog = false;
      this.editedIndex = -1;
    }
  }
};
</script>

<style scoped>
.editBtn:hover {
  color: #2196f3;
  transition: 0.2s;
}

.deleteBtn:hover {
  color: #f44336;
  transition: 0.2s;
}
</style>

 -->