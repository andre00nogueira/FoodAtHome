<template>
  <div>
    <navbar />
    <div class="jumbotron">
      <h2>Create User</h2>
      <form @submit.prevent="createUser">
        <div class="form-group">
          <label for="name">Name</label>
          <input
            type="text"
            class="form-control"
            name="name"
            id="name"
            v-model="user.name"
          />
          <div v-if="errors && errors.name" class="text-danger">
            {{ errors.name[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="email">E-mail</label>
          <input
            type="email"
            class="form-control"
            name="email"
            id="email"
            v-model="user.email"
          />
          <div v-if="errors && errors.email" class="text-danger">
            {{ errors.email[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="userType">User Type</label>
          <select class="custom-select" id="userType" v-model="user.type">
            <option value="">Choose Type...</option>
            <option
              v-for="(type, index) in types"
              :key="index"
              :value="type.name"
            >
              {{ type.name }}
            </option>
          </select>
          <div v-if="errors && errors.type" class="text-danger">
            {{ errors.type[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            class="form-control"
            name="password"
            id="password"
            v-model="user.password"
          />
          <div v-if="errors && errors.password" class="text-danger">
            {{ errors.password[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Password Confirmation</label>
          <input
            type="password"
            class="form-control"
            name="password_confirmation"
            id="password_confirmation"
            v-model="user.password_confirmation"
          />
          <div
            v-if="errors && errors.password_confirmation"
            class="text-danger"
          >
            {{ errors.password_confimation[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="photo_url">Photo</label>
          <input
            type="file"
            ref="file"
            class="form-control"
            name="photo_url"
            id="photo_url"
            v-on:change="handlePhotoUpload"
          />
          <div v-if="errors && errors.photo_url" class="text-danger">
            {{ errors.photo_url[0] }}
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
        <router-link to="/users" class="btn btn-secondary">Cancel</router-link>
      </form>
    </div>
  </div>
</template>
<script>
import navbar from "./navbar.vue";
export default {
  data() {
    return {
      user: {},
      errors: {},
      types: [],
    };
  },
  methods: {
    createUser() {
      const data = new FormData();
      if (this.user.photo_url) {
        data.append("photo_url", this.user.photo_url);
      }
      data.append("name", this.user.name);
      data.append("email", this.user.email);
      data.append("type", this.user.type);
      data.append("password", this.user.password);
      data.append("password_confirmation", this.user.password_confirmation);
      axios
        .post("api/users", data)
        .then((result) => {
          this.$toasted
            .show(`User ${this.user.name} created successfully!`, {
              type: "success",
            })
            .goAway(3500);
          this.$router.push(`/users`);

          this.failMessage = "";
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    },
    handlePhotoUpload(event) {
      this.user.photo_url = event.target.files[0];
      console.log(this.user);
    },
    getTypes() {
      let url = `api/users/types`;
      axios.get(url).then((response) => {
        this.types = response.data.data;
        this.types.splice(
          this.types.findIndex((t) => t.name == "C"),
          1
        );
      });
    },
  },
  mounted() {
    this.getTypes();
  },
  components: { navbar },
};
</script>
<style>
</style>