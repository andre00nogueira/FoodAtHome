<template>
  <div>
    <navbar />
    <div class="jumbotron" v-if="user">
      <h2>Edit User</h2>
      <form @submit.prevent="editUser">
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
          <p style="text-align: center">
            <img
              v-if="typeof user.photo_url == 'string'"
              class="img-profile rounded-circle"
              style="width: 100px; height: 100px"
              :src="`storage/fotos/${user.photo_url || 'default_avatar.jpg'}`"
            />
            <img
              v-else
              class="img-profile rounded-circle"
              style="width: 100px; height: 100px"
              :src="`${uploadedPhoto}`"
            />
            <button
              type="button"
              class="btn btn-danger"
              style="align-self: right"
              @click="removeProfilePhoto(user.id)"
            >
              Remove Photo
            </button>
          </p>
        </div>
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

        <editcustomer
          v-if="user.type == 'C'"
          :customer="customer"
          :errors="errors"
        />

        <button class="btn btn-primary" type="submit">Save</button>
        <a class="btn btn-danger" href="#/index">Cancel</a>
      </form>
    </div>
  </div>
</template>  

<script>
import editcustomer from "./edit_customer.vue";
import navbar from "./navbar";

export default {
  data() {
    return {
      user: undefined,
      customer: undefined,
      uploadedPhoto: undefined,
      errors: {},
    };
  },
  methods: {
    editUser() {
      const data = new FormData();
      if (typeof this.user.photo_url != "string") {
        data.append("photo_url", this.user.photo_url);
      }
      data.append("id", this.user.id);
      data.append("name", this.user.name);
      data.append("email", this.user.email);

      // ENVIAMOS UM POST PORÉM O MÉTODO É PUT.
      // ISTO ACONTECE DEVIDO A UM BUG, DESCRITO AQUI:
      // https://stackoverflow.com/questions/61770340/vue-laravel-formdata-append-displaying-null-value-on-edit
      data.append("_method", "PUT");
      console.log(data);
      axios
        .post(`api/users/${this.user.id}`, data)
        .then((result) => {
          if (this.user.type == "C") {
            axios
              .put(`api/customers/${this.user.id}`, this.customer)
              .then((response) => {
                this.$toasted
                  .show(`Customer ${this.user.name} edited successfully`, {
                    type: "success",
                  })
                  .goAway(3500);
                this.$router.push(`/`);
              })
              .catch((error) => {
                if (error.response.status === 422) {
                  this.errors = error.response.data.errors || {};
                }
              });
          } else {
            this.$toasted
              .show(`User ${this.user.name} edited successfully`, {
                type: "success",
              })
              .goAway(3500);
            this.$router.push(`/`);
          }
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    },
    removeProfilePhoto(userId) {
      axios
        .patch(`api/users/${userId}`, {
          photo_url: "default_avatar.jpg",
        })
        .then((response) => {
          this.user = response.data.data;
          this.$toasted
            .show(`Removed profile picture successfully`, {
              type: "success",
            })
            .goAway(3500);
        });
    },
    handlePhotoUpload(event) {
      this.user.photo_url = event.target.files[0];
      this.uploadedPhoto = URL.createObjectURL(event.target.files[0]);
      console.log(this.user);
    },
  },
  async created() {
    const userID = this.$route.params.id;
    axios.get(`/api/users/${userID}`).then((response) => {
      this.user = response.data.data;

      if (typeof this.user !== "undefined" && this.user.type == "C") {
        console.log("entrou");
        axios
          .get(`api/customers/${this.user.id}`)
          .then((response) => {
            this.customer = response.data.data;
            console.log(this.customer);
          })
          .catch((error) => {
            console.log(error);
          });
      }
    });
  },
  components: { navbar, editcustomer },
};
</script>

<style>
a {
  font: 14.4px Nunito, sans-serif;
  padding: 6px 12px;
}
</style>