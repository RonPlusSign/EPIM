<template>
  <div>
    <!-- Persistent login dialog (if not logged) -->
    <ELoginDialog
      :isOpen="isLoginDialogActive"
      :persistent="true"
      @status-changed="(value) => (this.isLoginDialogActive = value)"
    />
  </div>
</template>

<script>
import ELoginDialog from "@/components/ELoginDialog.vue";

export default {
  name: "UserCart",
  components: { ELoginDialog },
  data() {
    return {
      isLoginDialogActive: false,
    };
  },
  computed: {
    logged() {
      return this.$store.getters.logged;
    },
    user() {
      return this.$store.getters.user;
    },
  },
  created() {
    if (!this.logged) this.isLoginDialogActive = true;
  },
};
</script>
