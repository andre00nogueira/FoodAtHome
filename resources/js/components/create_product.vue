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
                    <select
                        class="custom-select"
                        name="productType"
                        id="productType"
                        v-model="product.type"
                    >
                        <option value="">Choose Type...</option>
                        <option v-for="(type, index) in types" :key="index" :value="type.name">
                        {{ type.name }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" v-model="product.description" rows="5" cols="50"></textarea>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.description[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" class="form-control" name="price" id="price" v-model="product.price"/>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.price[0] }}</div>
                </div>
                <div class="form-group">
                    <label for="photo_url">Photo</label>
                    <input type="file" ref="file" class="form-control" name="photo_url" id="photo_url"  v-on:change="handlePhotoUpload"/>
                    <div v-if="errors && errors.photo_url" class="text-danger">{{ errors.photo_url[0] }}</div>
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
            errors:{},
            types: []
        }
    },
    mounted(){
        this.getTypes();
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
        },
        getTypes() {
            let url = `api/products/types`;
            axios.get(url).then((response) => {
                this.types = response.data.data;
            });
        },
        handlePhotoUpload(event){
            this.product.photo_url = event.target.files[0]
            console.log(this.product)
        }
    },
    components: { navbar }
}
</script>
<style>
</style>