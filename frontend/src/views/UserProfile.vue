<template>
  <div>
    <!-- Persistent login dialog (if not logged) -->
    <ELoginDialog
      :isOpen="isLoginDialogActive"
      :persistent="true"
      @logged="(value) => (this.logged = value)"
      @status-changed="(value) => (this.isLoginDialogActive = value)"
    />
  </div>
</template>

<script>
import Axios from "axios";
import ELoginDialog from "@/components/ELoginDialog.vue";

export default {
  name: "UserProfile",
  components: { ELoginDialog },
  data() {
    return {
      logged: false,
      isLoginDialogActive: false,
      user: null,
    };
  },
  created() {
    Axios.get(process.env.VUE_APP_API_URL + `login.php`)
      .then((response) => {
        if (!response.data.logged) this.isLoginDialogActive = true;
        else
          Axios.get(process.env.VUE_APP_API_URL + `user.php`)
            .then((response) => {
              this.user = response.data;
            })
            .catch(() => {});
      })
      .catch(() => {});
  },
};
</script>
