<template>
    <div>
        <navbar />
        <div v-if="currentOrder">
            <h4>Current Delivery</h4>
            <h3>Current Order - #{{ order.id }}</h3>
            <br />
            <h3>Customer - {{ order.customer_name }}</h3>
            <h4>Status - {{ order.status == "H" ? "Holding" : "Preparing" }}</h4>
            <h5>Preparation Started - {{ order.opened_at }}</h5>
            <h6>Price - {{ order.total_price }}€</h6>
            <h6>Notes - {{ order.notes ? order.notes : "No notes" }}</h6>
            <div class="content">
                <h2>Items</h2>
                <itemsTable :items="order.orderItems" />
            </div>
        </div>
        <div v-else>
            <h4>My Orders</h4>
            <orderTable :orders="orders" :to-delivery="true" @assignOrder="deliverOrder"/>
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
            ordersData: {},
            client:undefined,
            currentOrder:undefined
        };
    },
    created(){
        axios.get(`api/deliverymen/${this.$route.params.id}/order`).then((response)=>{
            console.log(response.data)
            if(response.data){
                this.currentOrder=response.data.data
                axios.get(`api/customer/${this.currentOrder}`).then((response)=>{
                    this.client=response.data.data
                })
            }else{
                axios.get(`api/deliverymen/orders`).then((response)=>{
                    this.ordersData=response.data
                    this.orders=this.ordersData.data
                })
            }
        })
    },
    methods:{
        getResults(page = 1) {
          let url = `api/deliverymen/orders`;
          if(page!=0){
            url += `?page=${page}`;
            axios.get(url).then((response) => {
                this.ordersData = response.data;
                this.orders=this.ordersData.data
            });
          }
        },deliverOrder(orderID){
            var order = orders.find(order => {
                return order.id === orderID
            })
            let payload = {
            userId: order.customer_id,
            status: value,
            orderId: order.id
          }
          this.$socket.emit("order_status", payload);
        }
    },
    components: { navbar, orderTable }
}
</script>

<style>

</style>