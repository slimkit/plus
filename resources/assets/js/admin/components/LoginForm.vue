<template>
    <form class="form-signin" role="form" :action="action" @submit.prevent="onSubmit">
        <h2 class="form-signin-heading text-center">后台</h2>

        <input type="tel" class="form-control" placeholder="输入手机号码" required autofocus>
        <input type="password" class="form-control" placeholder="输入密码" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
    </form>
</template>

<script>
    export default {
        // mounted() {
        //     console.log('Component mounted.')
        // },
        
        props: {
            action: String,
        },

        methods: {
            onSubmit() {
                console.log(this.action);
            }
        }
    }
</script>
