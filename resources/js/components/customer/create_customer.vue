<template>
    <div>
        <navbar />
        <div class="jumbotron">
        
        <h2>Create Customer</h2>
            <form @submit.prevent="createCustomer">
                <div class="form-group" >
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="customer.name"/>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email" id="email" v-model="customer.email"/>
                    <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>

                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" v-model="customer.password"/>
                    <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>

                </div>

                <div class="form-group">
                    <label for="password_confirmation">Password Confirmation</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" v-model="customer.password_confirmation"/>
                    <div v-if="errors && errors.password_confirmation" class="text-danger">{{ errors.password_confimation[0] }}</div>

                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address" v-model="customer.address"/>
                    <div v-if="errors && errors.address" class="text-danger">{{ errors.address[0] }}</div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" v-model="customer.phone"/>
                    <div v-if="errors && errors.phone" class="text-danger">{{ errors.phone[0] }}</div>
                </div>


                <div class="form-group">
                    <label for="nif">NIF</label>
                    <input type="text" class="form-control" name="nif" id="nif" v-model="customer.nif"/>
                    <div v-if="errors && errors.nif" class="text-danger">{{ errors.nif[0] }}</div>
                </div>

                <div class="form-group">
                    <label for="photo_url">Photo</label>
                    <input type="file" ref="file" class="form-control" name="photo_url" id="photo_url" v-on:change="handlePhotoUpload"/>
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
import navbar from "../navbar.vue";
export default{
    data(){
        return{
            customer:{},
            successMessage:'',
            errors:{}
        }
    },
    methods:{
        createCustomer: function (){
            axios.post('api/customers',this.customer).then(result=>{
                this.successMessage='Customer created'
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
        handlePhotoUpload(event){
            this.customer.photo_url = event.target.files[0]
            console.log(this.customer)
        }
    },
    components: { navbar }
}
</script>
<style>
</style>