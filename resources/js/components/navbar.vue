<template>
  <nav class="navbar navbar-dark bg-dark navbar-expand-sm">
    <router-link class="navbar-brand" to="/">Food@Home</router-link>
    <router-link
      v-if="user && user.type == 'C'"
      class="navbar-brand"
      :to="`/customer/${user.id}/dashboard`"
      >Dashboard</router-link
    >
    <router-link
      v-if="user && user.type == 'EC'"
      class="navbar-brand"
      :to="`/cook/${user.id}/dashboard`"
      >Dashboard</router-link
    >
    <router-link
      v-if="user && user.type == 'EM'"
      class="navbar-brand"
      :to="`/users`"
      >Users List</router-link
    >

    <router-link
      v-if="user && user.type == 'ED'"
      class="navbar-brand"
      :to="`/deliveryman/${user.id}/dashboard`"
      >Dashboard</router-link
    >

    <router-link
      v-if="user && user.type == 'EM'"
      class="navbar-brand"
      :to="`/stats`"
      >Stats</router-link
    >

    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
        <template v-if="!user">
          <li class="nav-item">
            <router-link to="/login" class="btn btn-primary">Login</router-link>
            <router-link to="/customers/create" class="btn btn-primary"
              >Register</router-link
            >
          </li>
        </template>
        <template v-else>
          <div class="collapse navbar-collapse" id="navbar-list-4">
            <ul class="navbar-nav">
              <li class="nav-item dropdown" style="display: flex">
                <img
                  :src="`storage/fotos/${
                    user.photo_url || 'default_avatar.jpg'
                  }`"
                  class="rounded-circle"
                  style="width: 40px; height: 40px"
                />
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  role="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                </a>

                <div
                  class="dropdown-menu"
                  aria-labelledby="navbarDropdownMenuLink"
                >
                  <router-link
                    :to="`/customer/${user.id}/dashboard`"
                    class="dropdown-item"
                    >Dashboard</router-link
                  >
                  <router-link :to="`/users/${user.id}`" class="dropdown-item"
                    >Profile</router-link
                  >
                  <router-link
                    v-if="user.type == 'C'"
                    to="/cart"
                    class="dropdown-item"
                    >Cart</router-link
                  >
                  <a class="dropdown-item" @click.prevent="logout" href="#"
                    >Logout</a
                  >
                </div>
              </li>
            </ul>
          </div>
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
            .patch(`/api/users/${this.user.id}`, {
              loggedin: new Boolean(false),
              available: new Boolean(false),
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

.rounded_circle {
  vertical-align: middle;
  border-radius: 50%;
}

.dropdown-toggle {
  text-align: right;
}
</style>