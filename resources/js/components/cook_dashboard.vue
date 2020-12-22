<template>
 
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
                this.order = response.data.data;
            });
        }
    }
}
</script>

<style>

</style>