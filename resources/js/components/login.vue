<template>
    <div class="jumbotron">
        <h2>Login</h2>
        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input type="email" class="form-control" v-model="credentials.email" name="email" id="inputEmail" placeholder="Email address"/>
        </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input type="password" class="form-control" v-model="credentials.password" name="password" id="inputPassword" placeholder="Password"/>
        </div>
        <div class="form-group">
            <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
        </div>
    </div>
</template>
<script>
export default {
    data: function() {
        return{
            credentials: {
                email: '',
                password: ''
            }
        }
    },
    methods: {
        login () {
            this.$store.commit('clearUser')
            axios.get('/sanctum/csrf-cookie').then(response =>{
                axios.post('/api/login', this.credentials).then(response =>{
                    // This sets the current user
                    // To the logged in user
                    this.$store.commit('setUser', response.data)
                    axios.patch(`/api/users/${response.data.id}`, {
                        loggedin: new Boolean(true)
                    }).then((response) => {
                        console.log(response.data)
                    }).catch((error) => {
                        console.log(error)
                    })
                    // Sends user to home page
                    this.$router.push('/') 
                }).catch(error =>{
                    console.log('Invalid Authentication')
                })
            })
        }
    }
}
</script>