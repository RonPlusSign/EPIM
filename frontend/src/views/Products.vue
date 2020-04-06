<template>
  <products-list :products="products"></products-list>
</template>

<script>
import Axios from "axios";
import EProductsList from "../components/EProductsList";

export default {
  name: "Products",

  props: ["title"],

  data() {
    return {
      products: [],
      title: "?q=''"
    };
  },

  components: {
    "products-list": EProductsList
  },

  created: function() {
    Axios.get(process.env.VUE_APP_API_URL + `products.php` + title)
      .then(function(response) {
        // console.log(response);
        products = JSON.parse(response);
      })
      .catch(function(error) {
        // handle error
        console.log(error);
      });
  }
};
</script>
