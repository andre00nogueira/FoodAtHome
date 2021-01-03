<template>
  <div>
    <navbar />
    <div class="jumbotron">
      <h2>Edit User</h2>
      <form v-if="user" @submit.prevent="changePassword">
        <div class="form-group">
          <label for="password">Current Password</label>
          <input
            type="password"
            class="form-control"
            name="currentPassword"
            id="currentPassword"
            v-model="currentPassword"
          />
          <div v-if="errors && errors.currentPassword" class="text-danger">
            {{ errors.currentPassword[0] }}
          </div>
        </div>
        <div class="form-group">
          <label for="password">New Password</label>
          <input
            type="password"
            class="form-control"
            name="password"
            id="password"
            v-model="password"
          />
          <div v-if="errors && errors.password" class="text-danger">
            {{ errors.password[0] }}
          </div>
        </div>
        <div class="field">
          <label>Confirm Password</label>
          <input
            type="password"
            class="form-control"
            name="password_confirmation"
            id="password_confirmation"
            v-model="password_confirmation"
          />
          <div
            v-if="errors && errors.password_confirmation"
            class="text-danger"
          >
            {{ errors.password_confirmation[0] }}
          </div>
        </div>

        <div class="form-group" style="margin-top: 20px">
          <button class="btn btn-primary" type="submit">Save</button>
          <router-link class="btn btn-danger" :to="`/users/${user.id}`"
            >Cancel</router-link
          >
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import navbar from "./navbar.vue";
export default {
  components: { navbar },
  data: function () {
    return {
      user: undefined,
      currentPassword: "",
      password: "",
      password_confirmation: "",
      errors: {},
    };
  },

  methods: {
    changePassword: function () {
      axios
        .post(`api/users/${this.user.id}/password`, {
          currentPassword: this.currentPassword,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .then((result) => {
          this.$toasted
            .show(`User's password changed successfuly`, {
              type: "success",
            })
            .goAway(3500);
            this.$router.push(`/users/${this.user.id}`)
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    },
  },
  async created() {
    const userID = this.$route.params.id;
    this.user = (await axios.get(`/api/users/${userID}`)).data.data;
    console.log(this.user);
  },
  beforeRouteUpdate(to, from, next){
    if(to.path == `/users/${to.params.id}/password` && to.params.id != this.$store.state.user.id){
      return next(`/users/${this.$store.state.user.id}/password`)
    }
    next()
  },
};
</script>
<style>
</style>