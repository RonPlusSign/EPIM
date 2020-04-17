<template>
  <div class="Categories">
    <ECardsGrid :elements="addType(categories)" />
  </div>
</template>

<script>
import Axios from "axios";
import ECardsGrid from "@/components/ECardsGrid";

export default {
  name: "Categories",
  components: { ECardsGrid },
  data() {
    return {
      categories: []
    };
  },
  methods: {
    fetchCategories() {
      Axios.get(process.env.VUE_APP_API_URL + `categories.php`)
        .then(response => {
          this.categories = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },

    addType(categoriesList) {
      categoriesList.forEach(element => (element.type = "category"));
      return categoriesList;
    }
  },
  created() {
    this.fetchCategories();
  }
};
</script>