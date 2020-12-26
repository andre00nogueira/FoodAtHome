<template>
    <div>
        <navbar />
        <div>
            <h4>Current Order</h4>
        </div>
        <div>
            <h4>My Orders</h4>
            <orderTable :orders="orders"/>
            <pagination v-if="orders.length>0"  :data="ordersData" @pagination-change-page="getResults"></pagination>
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
            ordersData: {}
        };
    },
    created(){
        axios.get(`api/deliverymen/${this.$route.params.id}/orders`).then((response)=>{
            this.ordersData=response.data
            this.orders=this.ordersData.data
        })
    },
    methods:{
        getResults(page = 1) {
          let url = `api/deliverymen/${this.$route.params.id}/orders`;
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