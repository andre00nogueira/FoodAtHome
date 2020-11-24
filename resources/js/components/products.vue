<template>
  <div>
    <navbar />
    
    <input
      class="form-control"
      id="myInput"
      type="text"
      placeholder="Search.."
    />
    <table class="table table-striped">
      <thead>
        <tr>
          <th />
          <th>Name</th>
          <th>Type</th>
          <th>Description</th>
          <th>Price</th>
          <!--<th>Actions</th>-->
        </tr>
      </thead>
      <tbody>
        <tr v-for="product of products" :key="product.id">
          <td>
            <img
              id="productPhoto"
              :src="'storage/products/' + product.photo_url"
            />
          </td>
          <td>{{ product.name }}</td>
          <td>{{ product.type }}</td>
          <td>{{ product.description.substring(0, 100) + "..." }}</td>
          <td>{{ product.price }}€</td>
          <!-- <td>
            <button class="btn btn-primary" v-on:click="editUser(user)">
              Edit
            </button>
            <button class="btn btn-danger" v-on:click="deleteUser(index)">
              Delete
            </button>
          </td>-->
        </tr>
      </tbody>
    </table>
    <pagination :data="products" @pagination-change-page="getProducts"/>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import pagination from 'laravel-vue-pagination';

export default {
  data () {
    return {
      products: {},
    };
  },
  mounted() {
      this.getProducts();
  },
  methods: {
      getProducts(page = 2){
         axios.get("api/products?page=" + page).then((response) => {
            this.products = response.data.data;
         }); 
      },
  },
  components: { navbar, pagination },
};
</script>

<style>
#productPhoto {
  width: 50px;
  height: 50px;
}
</style>