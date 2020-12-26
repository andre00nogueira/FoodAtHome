<template>
    <div v-if="!isFetching" class="jumbotron">
    <navbar />
    <h2>User Details</h2>
        <p style="text-align:center">
        
            <img class="img-profile rounded-circle" style="width: 100px; height: 100px;" :src="`storage/fotos/${user.photo_url}`">
            <div style="text-align:center">
                <span class="lead font-weight-bold">Name: {{user.name}}</span>
            </div>
            <div style="text-align:center">
                <span class="lead">Email: {{user.email}}</span>
            </div>
            <div v-if="customer">
                <div  style="text-align:center">
                    <span class="lead">Address: {{customer.address}}</span>
                </div>
                <div style="text-align:center">
                    <span class="lead">NIF: {{customer.nif}}</span>
                </div>
                <div style="text-align:center">
                    <span class="lead">Phone: {{customer.phone}}</span>
                </div>
            </div>

            <router-link :to="`/users/${this.user.id}/edit`">Edit User</router-link>
            <router-link :to="`/users/${this.user.id}/password`">Change Password</router-link>
    </div>
</template>

<script>
import navbar from "./navbar.vue";
export default {
    data(){
        return{
            customer:{},
            user:{},
            isFetching: true
        }
    },
  created(){
      const userID = this.$route.params.id;
        axios.get(`/api/users/${userID}`).then((response) => {
      this.user = response.data.data;
      console.log(this.user)
            if(typeof this.user !== 'undefined' && this.user.type == 'C'){
                axios.get(`api/customers/${this.user.id}`).then((response)=>{
                    this.customer = response.data.data
                    this.isFetching = false;
                }).catch((error)=>{
                    console.log(error)
                })
            }else{
                this.isFetching = false;
            }
        })
            
          },
   components: { navbar }
  }
  
</script>

<style>
</style>