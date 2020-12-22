<template>
  <div>
    <navbar />
    
    <div class="jumbotron">
      <h1>FOOD@HOME</h1>
      <h5>WELCOME TO OUR PROJECT</h5>
       <a
        href="#"
        @click.prevent="myself"
      >Myself</a>
    </div>
  </div>
</template>
<script>
import navbar from "./components/navbar.vue";
//import Navbar from './components/navbar.vue'
//import EditUserComponent from './components/edit_user.vue'

export default {
  components: { navbar },
  methods: {
      myself() {
          this.$store.dispatch('loadUserLogged').then(() => {
              if (this.$store.state.user) {
                  console.log('User currently logged:')
                  console.log(this.$store.state.user)
              }else{
                  console.log('No user is currently logged')
              }
          })
      }
  }
  /*data: function() {
            return {
                title: 'List Users',
                editingUser: false,
                showSuccess: false,
                showFailure: false,
                successMessage: '',
                failMessage: '',
                currentUser: {},
                users: [],
                departments: []
            }
        },
        methods: {
            editUser: function (user) {
                this.currentUser = Object.assign({}, user)
                this.editingUser = true
            },
            deleteUser: function (index) {
                axios.delete(`/api/users/${index}`)
                .then(result => {
                    this.users.splice(index, 1)
                })
            },
            saveUser: function (user) {
                axios.put(`/api/users/${user.id}`, user)
                .then(result =>{
                    const user = result.data.data
                    Object.assign(this.users.find(u => u.id == user.id), user)
                    Object.assign(this.currentUser, user)
                    this.successMessage = 'User Updated!'
                    this.failMessage = ''
                }).catch(error => {
                    this.failMessage = 'User Update ERROR!'
                    this.successMessage = ''
                })
            },
            cancelEdit: function () {
                this.currentUser = {}
                this.editingUser = false
            },
            closeMessage: function () {
                this.successMessage = ''
                this.failMessage = ''
            }
        },
        mounted () {
            axios.get('/api/users').then(response =>{
                this.users = response.data.data
            })
            axios.get('/api/departments').then(response =>{
                this.departments = response.data.data
            })
        },
        components: {
            'edit-user': EditUserComponent
        }*/
};
</script>
<style></style>