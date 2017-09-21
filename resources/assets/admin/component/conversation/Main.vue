<style lang="css" module>
    .nav {
        padding: 12px 12px 0;
    }
</style>

<template>
    <div>
        <ul class="nav nav-tabs" :class="$style.nav">
            <router-link :to="{ name: 'conversation:list' }" tag="li" active-class="active" exact>
                <a href="javascript:;">会话列表</a>
            </router-link>
        </ul>
        <router-view />
    </div>
</template>
