<template>
    <div id="app">
        <div class="container">
            <ui-nav-bar></ui-nav-bar>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
export default {
    name: 'news',
}
</script>
<style lang="scss">
#app {
    padding-top: 70px;
}
</style>