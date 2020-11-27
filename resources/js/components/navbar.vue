<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <router-link class="navbar-brand" to="/">Food@Home</router-link>
    <router-link to="/menu">Menu</router-link>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
        <li class="nav-item">
          <router-link  to="/login">Login</router-link>
        </li>
        <li class="nav-item">
          <a  href="#" @click.prevent="logout">Logout</a>
        </li>
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