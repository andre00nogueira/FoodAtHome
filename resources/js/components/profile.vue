<template>
  <div>
    <navbar />
    <div v-if="user" class="jumbotron content">
      <h2>User Details</h2>
      <p style="text-align: center">
        <img
          class="img-profile rounded-circle"
          style="width: 100px; height: 100px"
          :src="`storage/fotos/${user.photo_url || 'default_avatar.jpg'}`"
        />
      </p>

      <div style="text-align: center">
        <span class="lead font-weight-bold">Name: {{ user.name }}</span>
      </div>
      <div style="text-align: center">
        <span class="lead">Email: {{ user.email }}</span>
      </div>
      <div v-if="customer">
        <div style="text-align: center">
          <span class="lead">Address: {{ customer.address }}</span>
        </div>
        <div style="text-align: center">
          <span class="lead">NIF: {{ customer.nif }}</span>
        </div>
        <div style="text-align: center">
          <span class="lead">Phone: {{ customer.phone }}</span>
        </div>
      </div>

      <div style="margin-top: 20px">
        <router-link class="btn btn-primary" :to="`/users/${this.user.id}/edit`"
          >Edit User</router-link
        >
        <router-link
          class="btn btn-secondary"
          :to="`/users/${this.user.id}/password`"
          >Change Password</router-link
        >
      </div>
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
export default {
  data() {
    return {
      customer: undefined,
      user: {},
    };
  },
  created() {
    const userID = this.$route.params.id;
    axios.get(`/api/users/${userID}`).then((response) => {
      this.user = response.data.data;
      console.log(this.user);
      if (typeof this.user !== "undefined" && this.user.type == "C") {
        axios
          .get(`api/customers/${this.user.id}`)
          .then((response) => {
            this.customer = response.data.data;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    });
  },
  computed() {
    return this.$store.state.user.id;
  },
  components: { navbar },
};
</script>

<style>
</style>