<template>
  <div>
    <navbar />

    <div class="jumbotron content">
      <h1>FOOD@HOME</h1>
      <div class="menu">
        <router-link to="/menu" id="buttonMenu" class="button"
          >Check out our Menu</router-link
        >
      </div>
      <carousel @next="next" @prev="prev">
        <carousel-slide
          v-for="(slide, index) in slides"
          :key="slide"
          :index="index"
          :visibleSlide="visibleSlide"
          :direction="direction"
        >
          <img :src="slide" />
        </carousel-slide>
      </carousel>
      <p v-if="!this.$store.state.user">Considering ordering? Please, <router-link to="/login">login</router-link> or <router-link to="customers/create">create an account</router-link></p>
    </div>
  </div>
</template>
<script>
import navbar from "./components/navbar.vue";
import Carousel from "./components/Carousel";
import CarouselSlide from "./components/CarouselSlide";

export default {
  data() {
    return {
      slides: [
        "storage/slideshow/image1.jpg",
        "storage/slideshow/image2.jpg",
        "storage/slideshow/image3.jpg",
        "storage/slideshow/image4.jpg",
        "storage/slideshow/image5.jpg",
        "storage/slideshow/image6.jpg",
        "storage/slideshow/image7.jpg",
        "storage/slideshow/image8.jpg",
      ],
      visibleSlide: 0,
      direction: "left",
    };
  },

  computed: {
    slidesLength() {
      return this.slides.length;
    },
  },
  methods: {
    next() {
      if (this.visibleSlide >= this.slidesLength - 1) {
        this.visibleSlide = 0;
      } else {
        this.visibleSlide++;
      }
      this.direction = "left";
    },
    prev() {
      if (this.visibleSlide <= 0) {
        this.visibleSlide = this.slidesLength - 1;
      } else {
        this.visibleSlide--;
      }
      this.direction = "right";
    },
    myself() {
      this.$store.dispatch("loadUserLogged").then(() => {
        if (this.$store.state.user) {
          console.log("User currently logged:");
        } else {
          console.log("No user is currently logged");
        }
      });
    },
  },
  components: {
    navbar,
    Carousel,
    CarouselSlide,
  },
};
</script>
<style>
img {
  background-repeat: no-repeat;
  width: 100%;
  height: 100%;
  background-position: center center;
  text-align: center;
  display: block;
  margin-left: auto;
  margin-right: auto;
  padding: 0;
}

.button {
  border-radius: 5px;
  border: none;
  color: white;
  padding: 0;
  text-align: center;
  text-decoration: none;
  line-height: 50px;
  display: inline-block;
  font-size: 18px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
  width: 100px;
  height: 50px;
}

#buttonMenu {
  width: 100%;
  background-color: #343a40;
  color: white;
  border: 2px solid #555555;
  position: relative;
}

#buttonMenu:hover {
  background-color: white;
  color: #343a40;
}
</style>    