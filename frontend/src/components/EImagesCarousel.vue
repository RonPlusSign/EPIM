<template>
  <!----------------------------------->
  <!----- Product images carousel ----->
  <!----------------------------------->
  <div id="imageCarousel">
    <v-carousel continuous hide-delimiters height="420" class="elevation-2">
      <v-carousel-item
        class="grey lighten-4"
        v-for="(url, index) in images"
        :key="index"
        @click="openOverlay(url)"
        +
      >
        <!-- Image inside the carousel -->
        <v-img ratio="1" class="mx-auto" height="420" width="420" :src="url" />
      </v-carousel-item>

      <!-- Default image (if "images" is empty) -->
      <v-carousel-item class="grey lighten-4" v-if="this.images.length === 0">
        <v-img
          ratio="1"
          class="mx-auto"
          height="420"
          width="420"
          src="@/assets/Product Not Found.png"
        />
      </v-carousel-item>
    </v-carousel>

    <v-overlay :opacity="0.7" :value="overlay" :z-index="5">
      <v-row>
        <v-spacer />
        <v-btn @click="overlay = false" fab small class="mx-3 mb-1">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-row>
      <v-img class="overlayImage" contain :src="overlayImageUrl" />
    </v-overlay>
  </div>
</template>

<script>
export default {
  name: "EImagesCarousel",

  data() {
    return {
      overlay: false,
      overlayImageUrl: ""
    };
  },
  props: {
    images: {
      type: Array,
      required: true
    }
  },
  methods: {
    openOverlay(url) {
      this.overlayImageUrl = url;
      this.overlay = true;
    }
  }
};
</script>

<style scoped>
#imageCarousel {
  max-width: 450px;
  cursor: zoom-in;
}

.overlayImage {
  max-height: 80vh;
  max-width: 80vw;
}
</style>
