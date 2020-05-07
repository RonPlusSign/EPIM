<template>
  <div>
    <!-------------------------------------->
    <!---- Go back to Admin page button ---->
    <!-------------------------------------->
    <v-btn @click="$router.push('/admin')" text color="secondary">
      <v-icon class="mr-2">mdi-arrow-left</v-icon>
      <span class="mt-1">torna alla pagina di admin</span>
    </v-btn>
    <br /><br />

    <!--------------->
    <!---- Title ---->
    <!--------------->
    <h2>Gestione delle categorie</h2>
  </div>
</template>

<script>
import adminMixin from "@/mixins/adminMixin";
export default {
  name: "CategoriesAmin",
  mixins: [adminMixin],
  data() {
    return {
      //
    };
  },
};
</script>
