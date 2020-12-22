<template>
    <div class="jumbotron">
    <h2>User Details</h2>
        <p style="text-align:center">
        <!--
        <img class="img-profile rounded-circle" style="width: 100px; height: 100px;" src="{{asset('storage/fotos/'.Auth::user()->foto)}}">
        -->
            <div style="text-align:center">
                <span class="lead font-weight-bold">Name: {{user.name}}</span>
            </div>
            <div style="text-align:center">
                <span class="lead">Email: {{user.email}}</span>
            </div>
            <div style="text-align:center">
                <span class="lead">Address: {{user.address}}</span>
            </div>
    </div>
</template>

<script>
export default {
  data: function () {
    return {
      user: undefined
    }
  },
  async created(){
        const userID = this.$route.params.id
        this.user = (await axios.get(`/api/users/${userID}`)).data.data
        console.log(this.user)
    }
}
</script>

<style>
</style>