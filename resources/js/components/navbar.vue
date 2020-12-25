<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <router-link class="navbar-brand" to="/">Food@Home</router-link>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
        <template v-if="!$store.state.user">
        <li class="nav-item">
          <router-link to="/login" class="btn btn-primary">Login</router-link>
          <router-link to="/customers/create" class="btn btn-primary">Register</router-link>
        </li>
        </template>
        <template v-else>
        <li class="nav-item">
          <img id="userPhoto" :src="`storage/app/public/fotos/${user.photo_url}`"/>
          <router-link v-if="$store.state.user.type == 'C'" to="/cart" class="btn btn-primary">Cart</router-link>
          <router-link :to="`/users/${this.user.id}`" class="btn btn-primary">Profile</router-link>
          <a href="#" @click.prevent="logout" class="btn btn-secondary">Logout</a>
        </li>
        </template>
      </ul>
    </div>
    <hr>
  </nav>
</template>

<script>
export default {
  methods: {
    logout () {
      axios.post('/api/logout').then(response =>{
        console.log('User has logged out')
        // This updates the store
        // And sets current user to NULL
        this.$store.commit('clearCart')
        this.$store.commit('clearUser')

        this.$router.push('/')
      }).catch(error =>{
        console.log(`Invalid Logout ${error}`)
      })
    }
  },
  computed:{
    user(){
            return this.$store.state.user
          }
  },
  created(){
            if(typeof this.user !== 'undefined' && this.user.type == 'C'){
                axios.get(`api/users/${this.user.id}`).then((response)=>{
                    console.log(response)
                    customer = response.data.data
                }).catch((error)=>{
                    console.log(error)
                })
            }
            
          }
}
</script>

<style>
    .navbar{
        margin-bottom: 5%;
    }
</style>