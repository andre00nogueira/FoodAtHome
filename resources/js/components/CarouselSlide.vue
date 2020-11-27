<template>
    <transition :name="direction" mode="in-out">
        <div v-show="visibleSlide===index" class="carousel-slide">
            <slot></slot>
        </div>
    </transition>
</template>
<script>
export default{
    props:['visibleSlide','index','direction'],
    data(){
        return{

        }
    }
}
</script>
<style>

    .carousel-slide {
        position:absolute;
        top:0;
        left:0;
        right:0;
        bottom:0;
    }

    .left-enter-active{
        animation: leftInAnimation 0.4s ease-in-out;
    }
    .left-leave-active{
        animation: leftOutAnimation 0.4s ease-in-out;
    }
    @keyframes leftInAnimation{
        from {transform: translateX(100%);}
        to {transform: translateX(0);}
    }
    @keyframes leftOutAnimation{
        from {transform: translateX(0);}
        to {transform: translateX(-100%)};
    }

     .right-enter-active{
        animation: rightInAnimation 0.4s ease-in-out;
    }
    .right-leave-active{
        animation: rightOutAnimation 0.4s ease-in-out;
    }
    @keyframes rightInAnimation{
        from {transform: translateX(-100%);}
        to {transform: translateX(0);}
    }
    @keyframes rightOutAnimation{
        from {transform: translateX(0);}
        to {transform: translateX(100%)};
    }
</style>