<template>
  <div>
    <navbar />

    <h2>Menu</h2>
    <router-link id="createButton" class="btn btn-primary" to="/products/create">Create Product</router-link>
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
            <div style="display: flex" v-if="user && user.type == 'C'">
              <input
                v-model="product.quantity"
                type="number"
                class="form-control"
                style="width: auto"
                placeholder="Quantity"
                min="1"
                max="20"
              />
              <button
                class="btn btn-primary"
                style="margin-left: 2%"
                v-on:click="addToCart(product)"
              >
                🛒
              </button>
              
              <router-link class="btn btn-warning" style="margin-left: 2%" :to="`/products/${product.id}/edit`">🔨</router-link>

              <button class="btn btn-danger" style="margin-left: 2%" @click.prevent="deleteProduct(product)">
                🗑️
              </button>
              
            
            <!--
              <form
                method="POST"
                action="{{route('products.destroy',$product)}}"
                onsubmit="return confirm('Are you sure you want to delete this record?');"
              >
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-xs btn-danger btn-p">
                  Delete
                </button>
              </form>
              -->
            </div>
          </td>
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
        return this.products;
      } else {
        if (this.selectedTypeValue == "") {
          if (this.products.length == 0) {
            this.getProducts(0);
          }
          return this.products.filter((product) => {
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
    user(){
      return this.$store.state.user
    }
  },
  methods: {
    getProducts(page = 1) {
      let url = `api/products`;
      if (page != 0) {
        url += `?page=${page}`;
      }
      axios.get(url).then((response) => {
        this.products = response.data.data;
        this.productsData = response.data;
      });
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

   
    deleteProduct(product){
      axios.delete(`api/products/${product.id}`).then((result)=>{
        let productDeletedIndex=this.products.findIndex((p)=>p.id==product.id)
        this.products.splice(productDeletedIndex,1)
        this.$toasted.show(`Product ${product.name} deleted!`, {type: "success",}).goAway(3500);
      }).catch((error)=>{
        console.log(error)
      })
    },
    addToCart(product) {
      if (product.quantity > 20 || product.quantity < 1) {
        this.$toasted
          .show(`Quantity should be between 1 and 20!`, {
            type: "error",
          })
          .goAway(3500);
        return;
      }
      this.$store.commit("addItemToCart", product);
      this.$toasted
        .show(`Product ${product.name} added to cart!`, {
          type: "success",
        })
        .goAway(3500);
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

#productTypeFilter {
  width: 20%;
}
#createButton{
  position: relative;
  left: calc(100% - 123px);
}
</style>