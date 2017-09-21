<style lang="css" module>
    .nav {
        padding: 12px 12px 0;
    }
</style>

<template>
    <div>
        <ul class="nav nav-tabs" :class="$style.nav">
            <router-link :to="{name: 'certification:users'}" tag="li" active-class="active" exact>
                <a href="javascript:;">认证用户</a>
            </router-link>
            <router-link :to="{name: 'certification:categories'}" tag="li" active-class="active" exact>
                <a href="javascript:;">认证类型</a>
            </router-link>
        </ul>
        <router-view />
    </div>

</template>
