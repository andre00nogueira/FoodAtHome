<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <router-link class="navbar-brand" to="/">Food@Home</router-link>
    <router-link to="/menu">Menu</router-link>
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
          <router-link to="/cart" class="btn btn-primary">Cart</router-link>
          <a  href="#" @click.prevent="logout" class="btn btn-secondary">Logout</a>
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
        this.$store.commit('clearUser')
        this.$store.commit('clearCart')

        this.$router.push('/')
      }).catch(error =>{
        console.log('Invalid Logout')
      })
    }
  }
};
</script>

<style>
    .navbar{
        margin-bottom: 5%;
    }
</style>