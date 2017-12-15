<template>
    <div>
        <ul class="nav nav-tabs component-controller-nav">
            <router-link to="/setting" tag="li" active-class="active">
                <a href="#">投稿配置</a>
            </router-link>
            <router-link to="/newslist" tag="li" active-class="active">
                <a href="#">资讯列表</a>
            </router-link>
            <router-link to="/categories" tag="li" active-class="active">
                <a href="#">资讯分类列表</a>
            </router-link>
            <router-link to="/bin" tag="li" active-class="active">
                <a href="#">资讯回收站</a>
            </router-link>
            <router-link to="/pinneds" tag="li" active-class="active">
                <a href="#">置顶审核</a>
            </router-link>
            <!-- <router-link to="/" tag="li" active-class="active">
            <a href="#">待开发</a>
            </router-link> -->
        </ul>
        <router-view />
    </div>
</template>
<script>
export default {
    name: 'News',
}
</script>
<style lang="scss">
@import "./style/app";

// 旋转动画
@keyframes TurnAround {
    from {
        transform: rotate(1deg);
    }
    to {
        transform: rotate(360deg);
    }
}

// 设置滚动条样式
::-webkit-scrollbar {
    width: 2px;
    height: 2px;
}

::-webkit-scrollbar-thumb {
    background-color: #33BBBA !important;
}

::-webkit-scrollbar-track {
    background-color: transparent !important;
}

#app {
    padding: 0;
    margin: 0;
}

.slide-fade-enter-active {
    transition: all .3s ease;
}

.slide-fade-leave-active {
    transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter, .slide-fade-leave-to .slide-fade-leave-active {
    transform: translateX(10px);
    opacity: 0;
}
</style>