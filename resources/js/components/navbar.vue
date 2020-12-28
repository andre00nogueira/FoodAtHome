<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <router-link class="navbar-brand" to="/">Food@Home</router-link>
    <router-link
      v-if="$store.state.user && $store.state.user.type == 'C'"
      class="navbar-brand"
      :to="`/customer/${$store.state.user.id}/dashboard`"
      >Dashboard</router-link
    >
    <router-link
      v-if="$store.state.user && $store.state.user.type == 'EC'"
      class="navbar-brand"
      :to="`/cook/${$store.state.user.id}/dashboard`"
      >Dashboard</router-link
    >
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
        <template v-if="!$store.state.user">
          <li class="nav-item">
            <router-link to="/login" class="btn btn-primary">Login</router-link>
            <router-link to="/customers/create" class="btn btn-primary"
              >Register</router-link
            >
          </li>
        </template>
        <template v-else>
          <!-- <li class="nav-item">
            <img id="userPhoto" class="img-profile rounded-circle" style="width: 100px; height: 100px;" :src="`storage/fotos/${user.photo_url}`" />
             <router-link :to="`/users/${user.id}`" class="btn btn-primary"
              >{{user.name}}</router-link
            >
            <router-link
              v-if="$store.state.user.type == 'C'"
              to="/cart"
              class="btn btn-primary"
              >Cart</router-link
            >
            <a href="#" @click.prevent="logout" class="btn btn-secondary"
              >Logout</a
            >
          </li>
          -->

          <li class="nav-item">
            <router-link
              v-if="$store.state.user.type == 'C'"
              to="/cart"
              class="btn btn-primary"
              >Cart</router-link
            >
            <!--<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> -->
              <div class="container-fluid">
                <ul class="navbar-nav">
                  <li class="nav-item dropdown">
                    <a
                      class="nav-link dropdown-toggle d-flex align-items-center"
                      href="#"
                      id="navbarDropdownMenuLink"
                      role="button"
                      data-mdb-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <img
                        :src="`storage/fotos/${user.photo_url}`"
                        class="rounded-circle"
                        height="22"
                        alt=""
                        loading="lazy"
                      />
                    </a>
                    <ul
                      class="dropdown-menu"
                      aria-labelledby="navbarDropdownMenuLink"
                    >
                      <li><router-link class="dropdown-item" :to="`/users/${user.id}`">Profile</router-link></li>
                      <li><a class="dropdown-item" href="#" @click.prevent="logout">Logout</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
          <!--  </nav> -->
          </li>

          <!-- <div class="right">
            <ul>
              <li>
                <a href="#">
                  <p>
                    {{user.name}} <br>
                  </p>
                  <img :src="`storage/fotos/${user.photo_url}`" >
                  <i class="fas fa-angle-down"></i>
                </a>
              </li>
            </ul>
          </div>
          -->
        </template>
      </ul>
    </div>
    <hr />
  </nav>
</template>

<script>
export default {
  methods: {
    logout() {
      axios
        .post("/api/logout")
        .then((response) => {
          console.log("User has logged out");
          // This updates the store
          // And sets current user to NULL
          this.$store.commit("clearCart");
          axios
            .patch(`/api/users/${this.$store.state.user.id}`, {
              logged_in: new Boolean(false),
            })
            .then((response) => {
              console.log(response.data);
            })
            .catch((error) => {
              console.log(error);
            });
          this.$store.commit("clearUser");
          this.$router.push("/");
        })
        .catch((error) => {
          console.log(`Invalid Logout ${error}`);
        });
    },
  },
  computed: {
    user() {
      return this.$store.state.user;
    },
  },
};
</script>

<style>
.navbar {
  margin-bottom: 5%;
}
</style>