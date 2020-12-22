<template>
    <button @click.prevent="getCurrentOrder(106778)">CLICK</button>
</template>

<script>
export default {
    data() {
        return {
            order: undefined,
        }
    },
    methods: {
        getCurrentOrder(id){
            axios.get(`api/orders/${id}`).then((response) => {
                this.order = response.data;
                console.log(response.data)
            }).catch((error) => {
                console.log(error)
            });
        }
    }
}
</script>

<style>

</style>