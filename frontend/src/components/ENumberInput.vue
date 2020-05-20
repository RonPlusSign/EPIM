<!-- 
Component of a custom number input with - & + arrows

Params:
- "value" (Number, required): value to work on
- "min" (Number): lower bound
- "max" (Number): upper bound
- "caption" (String): Caption of the input number
- "decimals" (Number, default 0): Number of decimals accepted by the input

Events:
- "change": Returns the number value
-->

<template>
  <div>
    <!----------------->
    <!---- Caption ---->
    <!----------------->
    <span class="caption">{{ caption }}</span>

    <!------------------------->
    <!---- Number selector ---->
    <!------------------------->
    <div class="numberInputSelector">
      <!-- "minus" button -->
      <v-icon
        class="minus"
        @click="decrease"
        :disabled="this.number === this.min"
        color="gray darken-4"
      >mdi-minus</v-icon>

      <!-- Input box with the number -->
      <input class="value text-center" v-model="number" :step="decimals" type="number" />

      <!-- "plus" button -->
      <v-icon
        class="plus"
        @click="increase"
        :disabled="this.number === this.max"
        color="gray darken-4"
      >mdi-plus</v-icon>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      number: 0
    };
  },
  props: {
    value: {
      type: Number,
      required: true
    },
    max: {
      type: Number,
      default: Number.MAX_VALUE
    },
    min: {
      type: Number,
      default: Number.MIN_VALUE
    },
    decimals: {
      type: Number,
      default: 0
    },
    caption: String
  },

  created() {
    this.number = this.value ? this.value : 0;
  },

  methods: {
    increase() {
      if (this.number !== this.max) this.number++;
      this.number = +this.number.toFixed(this.decimals);
    },

    decrease() {
      if (this.number !== this.min) this.number--;
      this.number = +this.number.toFixed(this.decimals);
    }
  },
  watch: {
    number(val) {
      // Control the new value, to check if it has been changed by the user directly
      if (Number.parseFloat(val) > this.max) {
        val = this.max;
        this.number = this.max;
      } else if (Number.parseFloat(val) < this.min) {
        val = this.min;
        this.number = this.min;
      }
      this.$emit("change", Number.parseFloat(val));
    },

    value(val) {
      this.number = val;
    }
  }
};
</script>

<style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type="number"] {
  -moz-appearance: textfield;
}

.numberInputSelector {
  border: 1px solid grey;
  border-radius: 3px;
  padding: 2px;
  max-width: fit-content;
  min-width: 106px;
}

.value {
  max-width: 30px;
  margin-left: 10px;
  margin-right: 10px;
}
.minus {
  border-right: 1px solid #777;
}

.plus {
  border-left: 1px solid #777;
}
</style>
