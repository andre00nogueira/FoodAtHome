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

      <select
        @change="getProductsByType($event)"
        class="custom-select"
        id="productTypeFilter"
      >
        <option value="">Choose Type...</option>
        <option v-for="(type, index) in types" :key="index" :value="type.name">
          {{ type.name }}
        </option>
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
          <th />
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
          <td>
            <div style="display:flex">
              <input
                v-model="product.quantity"
                type="number"
                class="form-control"
                style="width: 65%"
                placeholder="Quantity"
                min="1"
                max="10"
              />
              <button class="btn btn-primary" style="margin-left: 2%" v-on:click="addToCart(product)">
                🛒
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <pagination
      :data="
        Object.keys(productsData).length === 0 ? allProductsData : productsData
      "
      @pagination-change-page="getProducts"
    ></pagination>
  </div>
</template>

<script>
import navbar from "./navbar.vue";

export default {
  data() {
    return {
      allProducts: [],
      allProductsData: {},
      products: [],
      productsData: {},
      types: [],
      searchQuery: "",
      selectedTypeValue: "",
    };
  },
  mounted() {
    this.getProducts();
    this.getTypes();
  },
  computed: {
    filterProducts() {
      if (!this.searchQuery) {
        this.allProductsData = {};
        return this.products;
      } else {
        if (this.selectedTypeValue == "") {
          if (this.allProducts.length == 0) {
            this.getProducts(0);
          }
          return this.allProducts.filter((product) => {
            return product.name
              .toLowerCase()
              .includes(this.searchQuery.toLowerCase());
          });
        } else {
          return this.products.filter((product) => {
            return product.name
              .toLowerCase()
              .includes(this.searchQuery.toLowerCase());
          });
        }
      }
    },
  },
  methods: {
    getProducts(page = 1) {
      let url = `api/products`;
      if (page != 0) {
        url += `?page=${page}`;

        axios.get(url).then((response) => {
          this.products = response.data.data;
          this.productsData = response.data;
        });
      } else {
        axios.get(url).then((response) => {
          this.allProducts = response.data.data;
          this.allProductsData = response.data;
        });
      }
    },

    //#region GET FOOD BY TYPE
    getProductsByType(event) {
      this.selectedTypeValue = event.target.value;
      if (this.selectedTypeValue == "") {
        this.getProducts();
      } else {
        let url = `api/products/types/${this.selectedTypeValue}`;
        axios.get(url).then((response) => {
          this.products = response.data.data;
          this.productsData = response.data;
        });
      }
    },
    //#endregion

    //#region GET FOOD TYPES
    getTypes() {
      let url = `api/products/types`;
      axios.get(url).then((response) => {
        this.types = response.data.data;
      });
    },
    //#endregion

    addToCart(product, quantity) {
      this.$store.commit('addItemToCart', product)
    }
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

#productTypeFilter {
  width: 20%;
}
</style>