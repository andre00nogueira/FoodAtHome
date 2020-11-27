<template>
  <div>
    <navbar />
    <div class="jumbotron">
      <h1>FOOD@HOME</h1>
      <h5 style="margin-bottom: 5%">WELCOME TO OUR PROJECT</h5>
      <div class="menu">
        <h5>Our Menu</h5>
        <a href="/menu" id="buttonMenu" class="button">Menu</a>
     </div>
      <carousel @next="next" @prev="prev">
        <carousel-slide v-for="(slide,index) in slides" :key="slide" :index="index" :visibleSlide="visibleSlide" :direction="direction">
            <img :src="slide" />
        </carousel-slide>
      </carousel>

      <!--<button class="button buttonMenu"></button>-->
      
    </div>
  </div>
</template>
<script>
import navbar from "./components/navbar.vue";
//import Navbar from './components/navbar.vue'
//import EditUserComponent from './components/edit_user.vue'
import Carousel from "./components/Carousel";
import CarouselSlide from "./components/CarouselSlide";

export default {
  data(){
      return{
          slides: [
            'storage/slideshow/image1.jpg',
            'storage/slideshow/image2.jpg',
            'storage/slideshow/image3.jpg',
            'storage/slideshow/image4.jpg',
            'storage/slideshow/image5.jpg',
            'storage/slideshow/image6.jpg',
            'storage/slideshow/image7.jpg',
            'storage/slideshow/image8.jpg',
          ],
          visibleSlide : 0,
          direction: 'left',
      }
  },
 
  computed: {
      slidesLength(){
          return this.slides.length;
      }
  },
  methods: {
      next(){
          if(this.visibleSlide >= this.slidesLength - 1){
              this.visibleSlide = 0;
          }else{
              this.visibleSlide++;
          }
          this.direction="left"
      },
      prev(){
          if(this.visibleSlide <= 0){
              this.visibleSlide = this.slidesLength -1;
          }else{
              this.visibleSlide--;
          }
          this.direction="right"
      }
  },
  components: {
       navbar,
       Carousel,
       CarouselSlide,
    }
  
  /*data: function() {
            return {
                title: 'List Users',
                editingUser: false,
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                currentUser: {},
                users: [],
                departments: []
            }
        },
        methods: {
            editUser: function (user) {
                this.currentUser = Object.assign({}, user)
                this.editingUser = true
            },
            deleteUser: function (index) {
                axios.delete(`/api/users/${index}`)
                .then(result => {
                    this.users.splice(index, 1)
                })
            },
            saveUser: function (user) {
                axios.put(`/api/users/${user.id}`, user)
                .then(result =>{
                    const user = result.data.data
                    Object.assign(this.users.find(u => u.id == user.id), user)
                    Object.assign(this.currentUser, user)
                    this.successMessage = 'User Updated!'
                    this.failMessage = ''
                }).catch(error => {
                    this.failMessage = 'User Update ERROR!'
                    this.successMessage = ''
                })
            },
            cancelEdit: function () {
                this.currentUser = {}
                this.editingUser = false
            },
            closeMessage: function () {
                this.successMessage = ''
                this.failMessage = ''
            }
        },
        mounted () {
            axios.get('/api/users').then(response =>{
                this.users = response.data.data
            })
            axios.get('/api/departments').then(response =>{
                this.departments = response.data.data
            })
        },
        components: {
            'edit-user': EditUserComponent
        }*/
};
</script>
<style>
    img {
        background-repeat: no-repeat;
        width: 100%;
        height:100%;
        background-position: center center;
        text-align:center;
        display: block;
        margin-left: auto;
        margin-right: auto;
        padding:0;
    }
    .button {
        border: none;
        color: white;
        padding:0;
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
        background-color: black;
        color: white;
        border: 2px solid #555555;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }

    #buttonMenu:hover {
        background-color: white;
        color: black;
    }
    .jumbotron{
        
    }

    .menu{
        position: relative;
    }

    .menu > h5{
        position: relative;
        text-align: center;
    }

</style>    