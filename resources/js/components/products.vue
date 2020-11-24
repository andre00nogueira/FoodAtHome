<template>
  <div>
    <navbar />


    <h2>Menu</h2>

    <div id="filterArea">
      <input
        class="form-control"
        type="text"
        v-model="searchQuery"
        placeholder="Search for a product..."
      />

      <select class="custom-select" id="productTypeFilter">
        <option selected>Type</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
      </select>

    </div>
    <table id="tableProducts" class="table table-striped">
      <thead>
        <tr>
          <th />
          <th>Name</th>
          <th>Type</th>
          <th>Description</th>
          <th>Price</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product of filterProducts" :key="product.id">
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
        </tr>
      </tbody>
    </table>
    <pagination
      :data="productsData"
      @pagination-change-page="getProducts"
    ></pagination>
  </div>
</template>

<script>
import navbar from "./navbar.vue";

export default {
  data() {
    return {
      products: [],
      productsData: {},
      searchQuery: "",
    };
  },
  mounted() {
    this.getProducts(1);
  },
  computed: {
    filterProducts() {
      if (!this.searchQuery) {
        return this.products;
      } else {
        return this.products.filter((product) => {
          return product.name
            .toLowerCase()
            .includes(this.searchQuery.toLowerCase());
        });
      }
    },
  },
  methods: {
    getProducts(page = 1) {
      let url = `api/products`;
      if(page != 0){
        url += `?page=${page}`
      }
      axios.get(url).then((response) => {
        this.products = response.data.data;
        this.productsData = response.data;
      });
    },
  },
  components: { navbar },
};
</script>

<style>
#productPhoto {
  width: 50px;
  height: 50px;
}

#tableProducts {
  margin-top: 2%;
  margin-bottom: 3%;
}

#filterArea {
  margin-top: 3%;
  display: flex;
}

#productTypeFilter{
  width: 20%;
}
</style>