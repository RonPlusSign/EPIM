<!--
This component is a dialog with an input box, that does PATCH and POST requests to an `user.php?address`

Props:
- "state" (Boolean, required): Controls the dialog state (open/close)

- "title" (String, default = ""): The title on the dialog

- "startingObject" (default = null): The object to edit
    Depending on this value, the request changes.
        - POST if startingObject is null
        - PATCH if startingObject is not null

Events:
- "updated-server-values": evoked after a successful request to the server

-->
<template>
  <v-dialog v-model="isOpen" max-width="400px">
    <!------------------------>
    <!---- "Add new" card ---->
    <!------------------------>
    <v-card>
      <v-form ref="form" onsubmit="return false;">
        <!-------------------->
        <!---- Card title ---->
        <!-------------------->
        <v-card-title class="primary darken-1 white--text">{{
          title
        }}</v-card-title>
        <v-card-text class="pb-1">
          <!----------------------->
          <!---- Region select ---->
          <!----------------------->
          <v-autocomplete
            v-model="selectedRegion"
            :items="regions"
            item-text="name"
            item-value="id"
            :rules="[rules.required, rules.positive]"
            no-data-text="Nessuna regione presente"
            prepend-icon="mdi-map"
            label="Regione"
            clearable
          />

          <!------------------------->
          <!---- Province select ---->
          <!------------------------->
          <v-autocomplete
            v-model="selectedProvince"
            :disabled="provinces.length === 0"
            :items="provinces"
            item-text="name"
            item-value="id"
            :rules="[rules.required]"
            no-data-text="Seleziona una regione"
            prepend-icon="mdi-map-marker-radius"
            label="Provincia"
            clearable
          />

          <!--------------------->
          <!---- City select ---->
          <!--------------------->
          <v-autocomplete
            v-model="address.city"
            :disabled="cities.length === 0"
            :items="cities"
            item-text="name"
            item-value="id"
            :rules="[rules.required]"
            no-data-text="Seleziona una provincia"
            prepend-icon="mdi-home-city"
            label="Città"
            clearable
          />

          <!--------------------------->
          <!---- Street name input ---->
          <!--------------------------->
          <v-text-field
            v-model="address.street"
            :rules="[rules.required]"
            label="Via"
            prepend-icon="mdi-road-variant"
            outlined
            dense
            clearable
            @keyup.enter.native="submit"
          />

          <v-row>
            <v-col>
              <!---------------------------->
              <!---- House number input ---->
              <!---------------------------->
              <v-text-field
                v-model="address.houseNumber"
                clearable
                outlined
                dense
                label="Civico"
                :rules="[rules.required, rules.positive]"
                prepend-icon="mdi-numeric"
                @keyup.enter.native="submit"
              />
            </v-col>
            <v-col>
              <!--------------------------->
              <!---- Postal code input ---->
              <!--------------------------->
              <v-text-field
                v-model="address.postalCode"
                clearable
                outlined
                dense
                label="CAP"
                :rules="[rules.required, rules.isNumber, rules.positive]"
                prepend-icon="mdi-map-marker-path"
                @keyup.enter.native="submit"
              />
            </v-col>
          </v-row>

          <!---------------------------->
          <!---- Phone number input ---->
          <!---------------------------->
          <v-text-field
            v-model="address.phoneNumber"
            clearable
            outlined
            dense
            label="Numero di telefono per la consegna"
            :rules="[rules.required, rules.phoneNumber]"
            prepend-icon="mdi-phone"
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
      address: null,
      regions: [],
      provinces: [],
      cities: [],
      selectedRegion: null,
      selectedProvince: null,
      fetchingData: false,
      loading: false, // Controls the loading effect
      error: false, // Controls the error alert status (open/close)
      rules: {
        // Input field rules
        required: (value) => !!value || "Inserisci questo parametro",
        positive: (value) => Number.parseInt(value) > 0 || "Valore non valido",
        isNumber: (value) => {
          const pattern = /^[0-9]+$/;
          return pattern.test(value) || "Il valore inserito non è un numero";
        },
        phoneNumber: (value) => {
          const pattern = /^[0-9]{7,12}$/;
          return (
            pattern.test(value) ||
            "Il numero inserito deve essere lungo dalle 7 a 12 cifre"
          );
        },
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
  },

  watch: {
    state(value) {
      this.isOpen = value;
      if (!value) this.$refs.form.reset();
    },

    isOpen(value) {
      if (!value) {
        this.clearAddress();
      }
      this.$emit("state-changed", value);
    },

    startingObject(value) {
      if (value) {
        // If a starting object is given, fill the form
        // The starting object only has the city id, so we need to fetch its province and region.

        this.address = Object.assign({}, value);

        let city = this.address.city;
        let province = null;
        let region = null;

        this.fetchingData = true; // Set fetching data to true, so the watchers won't start doing the requests

        // Get the province of the selected city
        Axios.get(process.env.VUE_APP_API_URL + `address.php`, {
          params: { cities: "", id: city },
        })
          .then((response) => {
            province = response.data.province;

            // Get the region of the selected province
            Axios.get(process.env.VUE_APP_API_URL + `address.php`, {
              params: { provinces: "", id: province },
            })
              .then((response) => {
                region = response.data.region;
                this.selectedRegion = region;
                this.selectedProvince = province;
                this.address.city = city;

                // Fetch the cities of that province
                Axios.get(process.env.VUE_APP_API_URL + `address.php`, {
                  params: {
                    cities: "",
                    province: province,
                  },
                })
                  .then((response) => (this.cities = response.data))
                  .catch((err) => console.error(err));

                // Fetch the provinces of that region
                Axios.get(process.env.VUE_APP_API_URL + `address.php`, {
                  params: {
                    provinces: "",
                    region: region,
                  },
                })
                  .then((response) => (this.provinces = response.data))
                  .catch((err) => console.error(err));

                Axios.get(process.env.VUE_APP_API_URL + `address.php`, {
                  params: {
                    cities: "",
                    province: province,
                  },
                })
                  .then((response) => (this.cities = response.data))
                  .catch((err) => console.error(err));

                setTimeout(() => {
                  // Set fetching data to false, so the watchers can start to work again
                  this.fetchingData = false;
                }, 1000);
              })
              .catch((err) => console.error(err));
          })
          .catch((err) => console.error(err));
      }
    },

    selectedRegion(regionId) {
      if (!this.fetchingData) {
        this.provinces = [];
        this.selectedProvince = null;
        if (regionId != null) {
          Axios.get(process.env.VUE_APP_API_URL + `address.php`, {
            params: {
              provinces: "",
              region: regionId,
            },
          })
            .then((response) => (this.provinces = response.data))
            .catch((err) => console.error(err));
        }
      }
    },

    selectedProvince(provinceId) {
      if (!this.fetchingData) {
        this.cities = [];
        this.address.city = null;
        if (provinceId != null) {
          Axios.get(process.env.VUE_APP_API_URL + `address.php`, {
            params: {
              cities: "",
              province: provinceId,
            },
          })
            .then((response) => (this.cities = response.data))
            .catch((err) => console.error(err));
        }
      }
    },
  },

  created() {
    this.startingObject
      ? (this.address = this.startingObject)
      : this.clearAddress();

    this.fetchRegions();
  },

  methods: {
    // Method to submit the form
    submit() {
      // Check if the input follows the rules
      if (this.$refs.form.validate()) {
        this.loading = true;
        if (this.startingObject === null) {
          // If there isn't a starting object, it means that we're adding a new item (POST Request)
          Axios.post(
            process.env.VUE_APP_API_URL + `user.php?address`,
            this.address
          )
            .then(() => {
              // Close the dialog
              this.loading = false;
              this.isOpen = false;
              this.clearAddress();
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
          Axios.patch(process.env.VUE_APP_API_URL + `user.php?address`, {
            id: this.startingObject.id,
            ...this.addressDifferences,
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

    clearAddress() {
      if (this.startingObject === null) {
        this.selectedRegion = null;
        this.selectedProvince = null;

        this.provinces = [];
        this.cities = [];

        this.address = {
          city: null, // City id
          street: null,
          houseNumber: null,
          postalCode: null,
          phoneNumber: null, // Phone number associated with that address
        };
      }
    },

    fetchRegions() {
      Axios.get(process.env.VUE_APP_API_URL + `address.php?regions`)
        .then((response) => (this.regions = response.data))
        .catch((err) => console.error(err));
    },
  },
  computed: {
    addressDifferences() {
      // Check the differences only if there's a starting object
      if (this.startingObject !== null) {
        // Check what attributes changed
        let patch = {};

        if (this.address.city !== this.startingObject.city)
          patch["city"] = this.address.city;

        if (this.address.street !== this.startingObject.street)
          patch["street"] = this.address.street;

        if (this.address.houseNumber !== this.startingObject.houseNumber)
          patch["houseNumber"] = this.address.houseNumber;

        if (this.address.postalCode !== this.startingObject.postalCode)
          patch["postalCode"] = this.address.postalCode;

        if (this.address.phoneNumber !== this.startingObject.phoneNumber)
          patch["phoneNumber"] = this.address.phoneNumber;

        return patch;
      } else return this.address;
    },
  },
};
</script>
