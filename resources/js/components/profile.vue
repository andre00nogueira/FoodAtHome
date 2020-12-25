<template>
    <div class="jumbotron">
    <h2>User Details</h2>
        <p style="text-align:center">
        
          <!--  <img class="img-profile rounded-circle" style="width: 100px; height: 100px;" :src="'storage/fotos/' + user.photo_url">
        -->
            <div style="text-align:center">
                <span class="lead font-weight-bold">Name: {{user.name}}</span>
            </div>
            <div style="text-align:center">
                <span class="lead">Email: {{user.email}}</span>
            </div>
            <div v-if="user.type == 'C'" style="text-align:center">
                <span class="lead">Address: {{user.address}}</span>
            </div>
            <div v-if="user.type == 'C'" style="text-align:center">
                <span class="lead">NIF: {{user.nif}}</span>
            </div>
            <div v-if="user.type == 'C'" style="text-align:center">
                <span class="lead">Phone: {{user.phone}}</span>
            </div>

            <router-link :to="`/customers/edit/${this.user.id}`">Edit User</router-link>
    </div>
</template>

<script>
export default {
    data(){
        return{
            customer:{}
        }
    },
computed:{
    user(){
            return this.$store.state.user
          }
  },
  created(){
            if(typeof this.user !== 'undefined' && this.user.type == 'C'){
                axios.get(`api/customers/${this.user.id}`).then((response)=>{
                    console.log(response)
                    customer = response.data.data
                }).catch((error)=>{
                    console.log(error)
                })
            }
            
          }
   
  }
</script>

<style>
</style>