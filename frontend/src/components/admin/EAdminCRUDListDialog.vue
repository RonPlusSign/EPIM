<!--
This component is a dialog with an input box, that does PATCH and POST requests to an endpoint

Props:
- "state" (Boolean, required): Controls the dialog state (open/close)

- "title" (String, default = ""): The title on the dialog

- "startingObject" (default = null): The object to edit
    Depending on this value, the request changes.
        - POST if startingObject is null
        - PATCH if startingObject is not null

- "endpoint" (String, required): endpoint where to do POST and PATCH requests (for example "brands.php")

Events:
- "updated-server-values": evoked after a successful request to the server

-->
<template>
  <v-dialog v-model="isOpen" max-width="400px">
    <!------------------------>
    <!---- "Add new" card ---->
    <!------------------------>
    <v-card>
      <v-form ref="form" onSubmit="return false;">
        <!-------------------->
        <!---- Card title ---->
        <!-------------------->
        <v-card-title class="primary darken-1 white--text mb-8 pr-0">{{
          title
        }}</v-card-title>
        <v-card-text class="pb-1">
          <!-------------------->
          <!---- Name input ---->
          <!-------------------->
          <v-text-field
            v-model="inputValue"
            clearable
            outlined
            dense
            label="Nome"
            :rules="[rules.required, rules.differentFromPreviousValue]"
            @keyup.enter.native="submit"
          />

          <!----------------------->
          <!---- Error message ---->
          <!----------------------->
          <v-alert
            v-model="error"
            dense
            prominent
            dismissible
            type="error"
            border="top"
            color="red darken-1"
          >
            Errore durante l'esecuzione dell'operazione.
          </v-alert>
        </v-card-text>
        <v-card-actions class="pb-6 px-8">
          <!----------------------------->
          <!---- "Close card" button ---->
          <!----------------------------->
          <v-btn @click="isOpen = false" dark small fab color="red">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-spacer />
          <!-------------------------->
          <!---- Send data button ---->
          <!-------------------------->
          <v-btn @click="submit" small fab :loading="loading" color="primary">
            <v-icon>mdi-check</v-icon>
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script>
import Axios from "axios";

export default {
  data() {
    return {
      isOpen: false, // Controls if the dialog is open (based on the "state" prop)
      inputValue: "", // Input field value
      loading: false, // Controls the loading effect
      error: false, // Controls the error alert status (open/close)
      rules: {
        // Input field rules
        required: (value) => !!value || "Inserisci questo parametro",
        differentFromPreviousValue: (value) =>
          !this.startingObject ||
          value !== this.startingObject.name ||
          "Il valore non può essere lo stesso del precedente!",
      },
    };
  },
  props: {
    state: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: "",
    },
    startingObject: {
      default: null,
    },

    endpoint: {
      type: String,
      required: true,
    },
  },

  watch: {
    state(value) {
      this.isOpen = value;
    },

    isOpen(value) {
      if (!value) this.inputValue = "";
      this.$emit("state-changed", value);
    },

    startingObject(value) {
      this.inputValue = value ? value.name : "";
    },
  },

  methods: {
    // Method to submit the form
    submit() {
      // Check if the input follows the rules
      if (this.$refs.form.validate()) {
        this.loading = true;
        if (this.startingObject === null) {
          // If there isn't a starting object, it means that we're adding a new item (POST Request)
          Axios.post(process.env.VUE_APP_API_URL + this.endpoint, {
            name: this.inputValue,
          })
            .then(() => {
              // Close the dialog
              this.loading = false;
              this.inputValue = "";
              this.isOpen = false;
              this.$emit("updated-server-values");
            })
            .catch((err) => {
              // Show the error
              this.loading = false;
              this.error = true;
              console.error(err);
            });
        } else {
          // Override the starting Object
          Axios.put(process.env.VUE_APP_API_URL + this.endpoint, {
            id: this.startingObject.id,
            name: this.inputValue,
          })
            .then(() => {
              // Close the dialog
              this.loading = false;
              this.inputValue = "";
              this.isOpen = false;
              this.$emit("updated-server-values");
            })
            .catch((err) => {
              // Show the error
              this.loading = false;
              this.error = true;
              console.error(err);
            });
        }
      }
    },
  },
};
</script>
