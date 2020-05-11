<template>
  <div>Edit product {{ id }} here</div>
</template>

<script>
// TODO: Import adminMixin
export default {
  data() {
    return {
      id: this.$router.history.current.params.id,
    };
  },
  created() {
    // TODO: get the product to edit
    // ID = id: this.$router.history.current.params.id
  },
};
</script>
