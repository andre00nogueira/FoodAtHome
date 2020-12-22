<template>
    <div class="jumbotron">
    <h2>Edit User</h2>
        <form v-if="user" @submit.prevent="editUser">
            <div class="form-group" >
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="user.name"/>
                    <div v-if="errors && errors.name" class="text-danger">{{ errors.name[0] }}</div> 
            </div>
        <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" v-model="user.email"/>
                <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>

            </div>    
            <div class="form-group">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <!--<button class="btn btn-secondary">Cancel</button> -->
                    <a class="btn btn-danger" href="#/index">Cancel</a>
            </div>
        </form>
         <div class=" alert" 
            :class="{'alert-success':successMessage}" v-if="successMessage">
            <button type="button" class="close-btn" @click="closeMessage()">&times;</button>
            <strong>{{successMessage}}</strong>
        </div>
    </div>
</template>  

<script>
export default{
    data: function(){
        return{
            user:undefined,
            successMessage:'',
            errors:{}
        }
    },
    methods:{
        editUser: function (){
            axios.put(`api/users/${this.user.id}`, this.user).then(result=>{
                this.successMessage='User Edited'
                this.failMessage=''
            }).catch(error=>{
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                this.successMessage=''
            })
        },
        
        closeMessage: function (){
            this.successMessage = ''
        }
    },
    async created(){
        const userID = this.$route.params.id
        this.user = (await axios.get(`/api/users/${userID}`)).data.data
        console.log(this.user)
    }
}
</script>

<style>
    a{
        font: 14.4px Nunito, sans-serif;
        padding: 6px 12px;
    }
</style>