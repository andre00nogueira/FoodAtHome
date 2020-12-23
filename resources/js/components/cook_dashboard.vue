<template>
    <div>
        <navbar/>

        <h2>Cook Dashboard</h2>
        <template v-if="!isFetching" class="content">
            <h3>Current Order - #{{ order.id }}</h3>
            <h6>Date - {{ order.date }}</h6>
            <h6>Status - {{ order.status }}</h6>
            <h6>Price - {{ order.total_price }}€</h6>
            <h6 v-if="order.notes">Notes - {{ order.notes }}</h6>
            <h6 v-else>Notes - No notes</h6>
        </template>
    </div>
    
</template>

<script>
import navbar from "./navbar.vue";

export default {
    data() {
        return {
            order: undefined,
            isFetching: true
        }
    },
    methods: {
        getCurrentOrder(id){
            axios.get(`api/orders/${id}`).then((response) => {
                this.order = response.data.data;
                console.log(response.data.data)
                this.isFetching = false
            }).catch((error) => {
                console.log(error)
            });
        }
    },
    mounted() {
        this.getCurrentOrder(1)
    },
    components: { navbar },
}
</script>

<style>
    .content{
        margin-top: 3%;
    }
</style>