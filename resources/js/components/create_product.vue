<template>
    <div>
        <navbar />
        <div class="jumbotron"> 
        <h2>Create Product</h2>
            <form @submit.prevent="createProduct">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="product.name"/>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="productType">Product Type</label>
                    <select name="productType" id="productType" v-model="product.type">
                        <option value="hotDish">hot dish</option>
                        <option value="coldDish">cold dish</option>
                        <option value="dessert">dessert</option>
                        <option value="drink">drink</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" v-model="product.description" rows="5" cols="50"></textarea>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.description[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" name="price" id="price" v-model="product.price"/>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.price[0] }}</div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
                <router-link to="/menu" class="btn btn-secondary">Cancel</router-link>
            </form>
             <div class=" alert" 
                :class="{'alert-success':successMessage}" v-if="successMessage">
                <button type="button" class="close-btn" @click="closeMessage()">&times;</button>
                <strong>{{successMessage}}</strong>
            </div>
        </div>
    </div>
</template>
<script>
import navbar from './navbar.vue';
export default {
    data(){
        return{
            product:{},
            successMessage:'',
            errors:{}
        }
    },
    methods:{
        createProduct: function (){
            axios.post('api/products',this.product).then(result=>{
                this.successMessage='Product created'
                this.failMessage=''
            }).catch(error=>{
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {}
                }
                this.successMessage=''
            })
        },
        closeMessage: function (){
            this.successMessage = ''
        }
    },
    components: { navbar }
}
</script>
<style>
</style>