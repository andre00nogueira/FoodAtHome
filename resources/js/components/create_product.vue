<template>
  <div>
    <navbar />
    <div class="jumbotron">
      <h2>Create Product</h2>
      <form @submit.prevent="createProduct">
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
          <div v-if="errors && errors.type" class="text-danger">
            {{ errors.type[0] }}
          </div>
        </div>

        <div class="form-group">
          <label for="description" style="width: 100%">Description</label>
          <textarea
            name="description"
            id="description"
            v-model="product.description"
            rows="5"
            cols="50"
            style="width: 100%"
          ></textarea>
          <div v-if="errors && errors.description" class="text-danger">
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
          <div v-if="errors && errors.price" class="text-danger">
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

        <button type="submit" class="btn btn-primary">Create</button>
        <router-link to="/menu" class="btn btn-secondary">Cancel</router-link>
      </form>
    </div>
  </div>
</template>
<script>
import navbar from "./navbar.vue";
export default {
  data() {
    return {
      product: {},
      errors: {},
      types: [],
    };
  },
  mounted() {
    this.getTypes();
  },
  methods: {
    createProduct: function () {
      const data = new FormData();
      data.append("name", this.product.name || '');
      data.append("type", this.product.type || '');
      data.append("description", this.product.description || '');
      data.append("price", this.product.price || '');
      data.append("photo_url", this.product.photo_url);
      axios
        .post("api/products", data)
        .then((result) => {
          this.$toasted
            .show(`Product ${this.product.name} created successfully!`, {
              type: "success",
            })
            .goAway(3500);
          this.$router.push(`/menu`);
        })
        .catch((error) => {
          this.errors = error.response.data.errors || {};
        });
    },
    getTypes() {
      let url = `api/products/types`;
      axios.get(url).then((response) => {
        this.types = response.data.data;
      });
    },
    handlePhotoUpload(event) {
      this.product.photo_url = event.target.files[0];
      console.log(this.product);
    },
  },
  components: { navbar },
};
</script>
<style>
</style>