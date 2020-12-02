import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: null,
    },
    mutations: {
        clearUser (state) {
            state.user = null
        },
        setUser(state, user) {
            state.user = user
        }
    },
    actions: {
        loadUserLogged (context) {
            axios.get('/api/users/me').then(response =>{ 
                //console.log(response.data)
                context.commit('setUser', response.data)
            }).catch(error =>{
                //console.log(error)
                context.commit('clearUser')
            })
        }
    }
})