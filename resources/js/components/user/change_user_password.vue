<template>
    <div class="jumbotron">
    <h2>Edit User</h2>
    <form v-if="user" @submit.prevent="changePassword">
        <div class="form-group">
            <label for="password">Current Password</label>
            <input type="password" class="form-control" name="password" id="password" v-model="user.password"/>
             <span v-show="errors.has('password')" class="is-danger">{{ errors.first('password') }}</span>
        </div>
        <div class="form-group" :class="{ error: errors.has('newPassword') }">
            <label for="password">New Password</label>
            <input type="password" class="form-control" name="newPassword" id="newPassword" v-model="newPassword"/>
             <span v-show="errors.has('newPassword')" class="is-danger">{{ errors.first('newPassword') }}</span>
        </div>
        <div class="field" :class="{ error: errors.has('confirmPassword') }">
            <label>Confirm Password</label>
            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" v-model="confirmPassword" v-validate="'required|confirmed:newPassword'">
            <span v-show="errors.has('confirmPassword')" class="is-danger">{{ errors.first('confirmPassword') }}</span>
        </div>
        
        <button type="submit" class="ui button primary" :disabled="!isFormValid">Change password</button>
    </form>
    </div>
</template>
<script>
export default {
    data: function(){
        return{
            user:undefined,
            password: '',
            newPassword: '',
            confirmPassword: '',
            errors:{}
        }
    },
    computed: {
        isFormValid () {
        return Object.keys(this.fields).every(key => this.fields[key].valid)
      },
    },
    methods: {
        changePassword: function(){
            axios.patch(`api/users/${this.user.id}`, this.user).then(result=>{
                user.password = this.newPassword = this.confirmPassword
            }).catch(error =>{
                 if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                user.password = this.newPassword = this.confirmPassword = ''
                
            })
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

</style>