<template>
  <div>
      <navbar />
        <h1>Order Details</h1>
        <label for="id" class="lead font-weight-bold">Order ID: </label>
        <span id="id" class="lead font-weight">{{order.id}}</span>
        <br>
        <label for="status" class="lead font-weight-bold">Status: </label>
        <span id="status" class="lead font-weight">{{getStatus(order.status)}}</span>
        <br>
        <label for="notes" class="lead font-weight-bold">Notes: </label>
        <span id="notes" class="lead font-weight">{{order.notes ? order.notes : "-"}}</span>
        <br>
        <label for="date" class="lead font-weight-bold">Date: </label>
        <span id="date" class="lead font-weight">{{order.date}}</span>
        <br>
        <label for="prepared" class="lead font-weight-bold">Prepared by: </label>
        <span id="prepared" class="lead font-weight">{{order.prepared_by ? cooker :"Not prepared yet"}}</span>
        <br>
        <label for="prepared_time" class="lead font-weight-bold">Preparation time: </label>
        <span id="prepared_time" class="lead font-weight">{{order.preparation_time ? getTime(order.preparation_time) : "-"}}</span>
        <br>
        <label for="delivered" class="lead font-weight-bold">Delivered by: </label>
        <span id="delivered" class="lead font-weight">{{order.delivered_by ? deliver :"Not delivered yet"}}</span>
        <br>
        <label for="delivery_time" class="lead font-weight-bold">Delivery time: </label>
        <span id="delivery_time" class="lead font-weight">{{order.delivery_time ? getTime(order.delivery_time) : "-"}}</span>
        <br>
        <label for="total_time" class="lead font-weight-bold">Total time: </label>
        <span id="total_time" class="lead font-weight">{{order.total_time ? getTime(order.total_time) : "-"}}</span>
        <h1>Itens List</h1>
        <itemsTable :items="order.orderItems"/>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import itemsTable from "./items_table.vue";
export default {
    data(){
        return{
            order:{},
            orderItens:[],
            cooker:"",
            deliver:""
        };
    },
    created(){
        axios.get(`api/orders/${this.$route.params.id}`).then((response)=>{
            this.order=response.data.data
            console.log(this.order)
            if(this.order.prepared_by){
                axios.get(`api/users/${this.order.prepared_by}`).then((response)=>{
                    this.cooker=`${response.data.data.name} (${response.data.data.email})`
                })
            }
            if(this.order.delivered_by){
                axios.get(`api/users/${this.order.delivered_by}`).then((response)=>{
                    this.deliver=`${response.data.data.name} (${response.data.data.email})`
                })
            }
        })
    },
    methods:{
        getStatus(status){
            switch(status){
                case 'H': 
                    return "Holding";
                case 'P':
                    return "Preparing";
                case 'R':
                    return "Ready";
                case 'T':
                    return "In Transit";
                case 'D':
                    return "Delivered";
                default:
                    return "Cancelled";
            }
        },
        getTime(time){
            let hours   = Math.floor(time / 3600);
            let minutes = Math.floor((time - (hours * 3600)) / 60);
            let seconds = time - (hours * 3600) - (minutes * 60);
            if (hours   < 10) {hours   = "0"+hours;}
            if (minutes < 10) {minutes = "0"+minutes;}
            if (seconds < 10) {seconds = "0"+seconds;}
            return hours+':'+minutes+':'+seconds;
        }
    },
    components: { navbar,itemsTable}
}
</script>

<style>

</style>