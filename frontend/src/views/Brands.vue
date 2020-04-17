<template>
  <div class="Brands">
    <ECardsGrid :elements="addType(brands)" />
  </div>
</template>

<script>
import Axios from "axios";
import ECardsGrid from "@/components/ECardsGrid";

export default {
  name: "Brands",
  components: { ECardsGrid },
  data() {
    return {
      brands: []
    };
  },
  methods: {
    fetchBrands() {
      Axios.get(process.env.VUE_APP_API_URL + `brands.php`)
        .then(response => {
          this.brands = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },

    addType(brandsList) {
      brandsList.forEach(element => (element.type = "brand"));
      return brandsList;
    }
  },
  created() {
    this.fetchBrands();
  }
};
</script>