<template>
  <div class="carousel">
    <slot></slot>

    <button @click="next" class="button next">Next</button>
    <button @click="prev" class="button prev">Previous</button>
  </div>
</template>
<script>
export default {
  data() {
    return {};
  },
  methods: {
    next() {
      this.$emit("next");
    },
    prev() {
      this.$emit("prev");
    },
  },
};
</script>
<style>
.carousel {
  position: relative;
  width: 1050px;
  height: 600px;
  overflow: hidden;
  margin-left: auto;
  margin-right: auto;
  margin-top: 50px;
  display: block;
}

.button {
  position: absolute;
  height: 40px;
  width: 70px;
  top: calc(50% - 20px);
  background-color:rgba(52, 58, 64, 0.6);
  border: none;
  color: #fff;
}

.button:focus,
.button:hover {
  outline: none;
  cursor: pointer;
}
.next {
  right: 0;
}
.prev {
  left: 0;
}
</style>