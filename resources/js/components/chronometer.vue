<template>
  <h5>Time Elapsed - {{ timeFormatted.toLocaleTimeString() }}</h5>
</template>

<script>
export default {
    props: ['initialTime'],
    data() {
        return {
            diff: 0,
            timeFormatted: undefined
        }
    },
    created() {
        this.diff =  Math.abs(new Date() - new Date(this.initialTime))
        // New date starting from 00:00:00 + difference
        this.timeFormatted = new Date()
        this.timeFormatted.setHours(0,0,0,0+this.diff)

        // Every sec
        setInterval(() => {
            this.timeFormatted = new Date(this.timeFormatted.getTime() + 1000)
        }, 1000);
    },   
}
</script>

<style>

</style>