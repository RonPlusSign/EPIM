<template>
  <EProductsList :products="products" />
</template>

<script>
import Axios from "axios";
import EProductsList from "@/components/EProductsList";

export default {
  name: "Products",
  components: {
    EProductsList
  },
  data() {
    return {
      products: [],
    };
  },
  created() {
    Axios.get(process.env.VUE_APP_API_URL + `products.php`)
      .then((response) => {
        this.products = response.data.data;
      })
      .catch((error) =>{
        console.error(error);
      });
  }
};
</script>
