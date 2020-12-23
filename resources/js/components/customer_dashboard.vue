<template>
    <div>
        <navbar />
        <h1>Dashboard</h1>
        <div>
            <h4>My Ongoing Orders</h4>
            <orderTable :orders="orders"/>
            <pagination v-if="orders.length>0"  :data="ordersData" @pagination-change-page="getResults"></pagination>
        </div>
        <div>
            <h4>My Closed Orders</h4>
            <orderTable :orders="ordersClosed"/>
            <pagination v-if="ordersClosed.length>0"  :data="ordersClosedData" @pagination-change-page="getResultsClosed"></pagination>
        </div>
    </div>
</template>

<script>
import navbar from "./navbar.vue";
import orderTable from "./order_table.vue";
export default {
    data(){
        return{
            orders:[],
            ordersData: {},
            ordersClosed: [],
            ordersClosedData:{}
        };
    },
    created(){
        console.log("teste")
        axios.get(`api/users/orders/${this.$route.params.id}`).then((response)=>{
            this.ordersData=response.data
            this.orders=this.ordersData.data
        })
        axios.get(`api/users/orders/${this.$route.params.id}/closed`).then((response)=>{
            this.ordersClosedData=response.data
            this.ordersClosed=this.ordersClosedData.data
        })
    },
    methods:{
        getResultsClosed(page = 1) {
          let url = `api/users/orders/${this.$route.params.id}/closed`;
          if(page!=0){
            url += `?page=${page}`;
            axios.get(url).then((response) => {
                this.ordersClosedData = response.data;
                this.ordersClosed=this.ordersClosedData.data
            });
          }
        },
        getResults(page = 1) {
          let url = `api/users/orders/${this.$route.params.id}`;
          if(page!=0){
            url += `?page=${page}`;
            axios.get(url).then((response) => {
                this.ordersData = response.data;
                this.orders=this.ordersData.data
            });
          }
        }
    },
    components: { navbar, orderTable }
}
</script>

<style>

</style>