<template>
    <div class="carousel">
        <slot></slot>
        
        <button @click="next" class="next">Next</button>
        <button @click="prev" class="prev">Previous</button>
    </div>
</template>
<script>
export default{
    data(){
        return{

        }
    },
    methods: {
        next(){
            this.$emit('next')
        },
        prev(){
            this.$emit('prev')
        }
    }
}
</script>
<style>
    .carousel{
        position:relative;
        width:800px;
        height:400px;
        overflow: hidden;
     /*   top: 50%;
        left: 10%; */
        margin-left: auto;
        margin-right: auto;
        margin-top: 50px;
        display: block;
 
    }
    button{
        position:absolute;
        height:40px;
        width:70px;
        top: calc(50% - 20px);
        background-color: rgba(0,0,0,0.8);
        border:none;
        color:#FFF;
   }
   button:focus, button:hover{
        outline:none;
        cursor:pointer;
   }
    .next{
        right:0;
    }
    .prev{
        left:0;
    }

</style>