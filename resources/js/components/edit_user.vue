<template>
  <div>
  <navbar />
  <div class="jumbotron">
    <h2>Edit User</h2>
    <form v-if="user" @submit.prevent="editUser">
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

      <div v-if="customer" class="form-group">
        <label for="address">Address</label>
        <input
          type="text"
          class="form-control"
          name="address"
          id="address"
          v-model="customer.address"
        />
        <div v-if="errors && errors.address" class="text-danger">
          {{ errors.address[0] }}
        </div>
      </div>

      <div v-if="customer" class="form-group">
        <label for="phone">Phone</label>
        <input
          type="text"
          class="form-control"
          name="phone"
          id="phone"
          v-model="customer.phone"
        />
        <div v-if="errors && errors.phone" class="text-danger">
          {{ errors.phone[0] }}
        </div>
      </div>

      <div v-if="customer" class="form-group">
        <label for="nif">NIF</label>
        <input
          type="text"
          class="form-control"
          name="nif"
          id="nif"
          v-model="customer.nif"
        />
        <div v-if="errors && errors.nif" class="text-danger">
          {{ errors.nif[0] }}
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

      <div class="form-group">
        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-danger" href="#/index">Cancel</a>
      </div>
    </form>
    <div
      class="alert"
      :class="{ 'alert-success': successMessage }"
      v-if="successMessage"
    >
      <button type="button" class="close-btn" @click="closeMessage()">
        &times;
      </button>
      <strong>{{ successMessage }}</strong>
    </div>
  </div>
  </div>
</template>  

<script>
import navbar from './navbar.vue';
export default {
  data: function () {
    return {
      user: undefined,
      customer: undefined,
      successMessage: "",
      errors: {},
    };
  },
  methods: {
    editUser: function () {
      axios
        .put(`api/users/${this.user.id}`, this.user)
        .then((result) => {
          this.successMessage = "User Edited";
          this.failMessage = "";
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
          this.successMessage = "";
        });

      if (this.user.type == "C") {
        axios
          .put(`api/customers/${this.user.id}`, this.customer)
          .then((result) => {
            this.successMessage = "Customer Edited";
            this.failMessage = "";
          })
          .catch((error) => {
            if (error.response.status === 422) {
              this.errors = error.response.data.errors || {};
            }
            this.successMessage = "";
          });
      }
    },

    closeMessage: function () {
      this.successMessage = "";
    },
    handlePhotoUpload(event){
            this.user.photo_url = event.target.files[0]
            console.log(this.user)
        }
  },
  async created() {
    const userID = this.$route.params.id;
    axios.get(`/api/users/${userID}`).then((response) => {
      this.user = response.data.data;
      console.log(this.user);
      
      if (typeof this.user !== "undefined" && this.user.type == "C") {
        console.log("entrou");
        axios
          .get(`api/customers/${this.user.id}`)
          .then((response) => {
            console.log(response);
            this.customer = response.data.data;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    });
    console.log(this.user);
  },
  components: { navbar }
};
</script>

<style>
a {
  font: 14.4px Nunito, sans-serif;
  padding: 6px 12px;
}
</style>