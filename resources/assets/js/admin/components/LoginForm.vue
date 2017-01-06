<template>
    <form class="form-signin" role="form" :action="url" @submit.prevent="onSubmit">
        <h2 class="form-signin-heading text-center">后台</h2>

        <input type="tel" class="form-control" placeholder="输入手机号码" required autofocus v-model="phone">
        <input type="password" class="form-control" placeholder="输入密码" required v-model="password">

        <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
    </form>
</template>

<script>
import { admin_login } from '../../utils/APIs';

export default {
    
    props: {
        url: String,
    },

    data: () => ({
        phone: '',
        password: '',
    }),

    methods: {
        onSubmit() {
            this.$http.post(admin_login, {
                phone: this.phone,
                password: this.password
            }).then((response) => {
                console.log(response);
            });
        }
    },
}
</script>
