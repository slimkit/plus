<template>
    <transition name="custom-classes-transition" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutRight">
        <div class="ModelPop" :class="type">
            <slot></slot>
        </div>
    </transition>
</template>
<script>
export default {
    name: 'ui-alert',
    props: {
        type: String
    }
};
</script>
<style lang="scss">
.animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}


@keyframes bounceInRight {
    from,
    60%,
    75%,
    90%,
    to {
        animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
    }

    from {
        opacity: 0;
        transform: translate3d(3000px, 0, 0);
    }

    60% {
        opacity: 1;
        transform: translate3d(-25px, 0, 0);
    }

    75% {
        transform: translate3d(10px, 0, 0);
    }

    90% {
        transform: translate3d(-5px, 0, 0);
    }

    to {
        transform: none;
    }
}

.bounceInRight {
    animation-name: bounceInRight;
}

@keyframes bounceOutRight {
    20% {
        opacity: 1;
        transform: translate3d(-20px, 0, 0);
    }

    to {
        opacity: 0;
        transform: translate3d(2000px, 0, 0);
    }
}

.bounceOutRight {
    animation-name: bounceOutRight;
}

.ModelPop {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1060;
    padding: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
    width: 300px;
    height: 150px;
    background-color: #fff;
    &.info {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #31708f;
    }
    &.success {
        background-color: #dff0d8;
        border-color: #d6e9c6;
        color: #3c763d;
    }
    &.error,&.danger {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }
    >span.close {
        position: absolute;
        top: 0;
        right: 4px;
        outline: 0;
    }
    >div {
        width: 100%;
        height: 100%;
    }
}
</style>