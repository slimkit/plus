<template>
    <div>
        <nav-component />
        <router-view />
    </div>
</template>

<script>
import NavComponent from './modules/nav-component';
export default {
    components: {NavComponent}
}
</script>

