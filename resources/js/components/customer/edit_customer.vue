<template>
    <div class="jumbotron">
    <h2>Edit Customer</h2>
        <form v-if="customer" @submit.prevent="editCustomer">
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
                    <button class="btn btn-primary" type="submit">Save</button>
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
            customer:undefined,
            successMessage:'',
            errors:{}
        }
    },
    methods:{
        editCustomer: function (){
            axios.put(`api/customers/${this.customer.id}`,this.customer).then(result=>{
                this.successMessage='Customer edited'
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
        const customerID = this.$route.params.id
        this.customer = (await axios.get(`/api/customers/${customerID}`)).data.data
        console.log(this.customer)
    }
}
</script>
<style>
    a{
        font: 14.4px Nunito, sans-serif;
        padding: 6px 12px;
    }
</style>