<template>
  <div>
    <navbar />
    <div class="jumbotron" v-if="product">
      <h2>Edit Product</h2>
      <form @submit.prevent="editProduct">
        <div class="form-group">
          <label for="name">Name</label>
          <input
            type="text"
            class="form-control"
            name="name"
            id="name"
            v-model="product.name"
          />
          <div v-if="errors && errors.name" class="text-danger">
            {{ errors.name[0] }}
          </div>
        </div>
        <div class="form-group">
          <label for="productType">Product Type</label>
          <select
            class="custom-select"
            name="productType"
            id="productType"
            v-model="product.type"
          >
            <option value="">Choose Type...</option>
            <option
              v-for="(type, index) in types"
              :key="index"
              :value="type.name"
            >
              {{ type.name }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <label for="description" style="width: 100%;">Description</label>
          <textarea
            name="description"
            id="description"
            v-model="product.description"
            rows="5"
            cols="50"
            style="width: 100%;"
          ></textarea>
          <div v-if="errors && errors.name" class="text-danger">
            {{ errors.description[0] }}
          </div>
        </div>
        <div class="form-group">
          <label for="price">Price</label>
          <input
            type="number"
            step="0.01"
            class="form-control"
            name="price"
            id="price"
            v-model="product.price"
          />
          <div v-if="errors && errors.name" class="text-danger">
            {{ errors.price[0] }}
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
          <p style="text-align: center">
            <img
              v-if="typeof product.photo_url == 'string'"
              class="img-profile rounded-circle"
              style="width: 100px; height: 100px"
              :src="`storage/products/${product.photo_url}`"
            />
            <img
              v-else
              class="img-profile rounded-circle"
              style="width: 100px; height: 100px"
              :src="`${uploadedPhoto}`"
            />
          </p>
        </div>
        
        <button type="submit" class="btn btn-primary">Save</button>
        <router-link to="/menu" class="btn btn-secondary">Cancel</router-link>
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
import navbar from "./navbar.vue";
export default {
  data() {
    return {
      product: {},
      uploadedPhoto: undefined,
      successMessage: "",
      errors: {},
      types: [],
    };
  },
  mounted() {
    this.getTypes();
  },
  methods: {
    editProduct: function () {
      const data = new FormData();
      data.append("name", this.product.name);
      data.append("type", this.product.type);
      data.append("description", this.product.description);
      data.append("price", this.product.price);
      if (typeof this.product.photo_url != "string") {
        data.append("photo_url", this.product.photo_url);
      }
      data.append("_method", "PUT");
      console.log(data);
      
      axios
        .post(`api/products/${this.product.id}`, data)
        .then((result) => {
          this.$toasted
            .show(`Product ${this.product.name} edited successfully`, {
              type: "success",
            })
            .goAway(3500);
          this.$router.push(`/menu`);
          this.failMessage = "";
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
          this.successMessage = "";
        });
    },
    closeMessage: function () {
      this.successMessage = "";
    },
    getTypes() {
      let url = `api/products/types`;
      axios.get(url).then((response) => {
        this.types = response.data.data;
      });
    },
    handlePhotoUpload(event) {
      this.product.photo_url = event.target.files[0];
      this.uploadedPhoto = URL.createObjectURL(event.target.files[0]);
      console.log(this.product);
    },
  },
  async created() {
    const productID = this.$route.params.id;
    axios.get(`/api/products/${productID}`).then((response) => {
      this.product = response.data.data;
      console.log(this.product);
    });
  },
  components: { navbar },
};
</script>
<style>
</style>