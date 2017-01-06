<template>

    <form class="form-signin" role="form" @submit.prevent="onSubmit">
        <bootstrap-alert></bootstrap-alert>
        <h2 class="form-signin-heading text-center">后台</h2>

        <input type="tel" class="form-control" placeholder="输入手机号码" required autofocus v-model="phone">
        <input type="password" class="form-control" placeholder="输入密码" required v-model="password">

        <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
    </form>
</template>

<script>
import { admin_login } from '../../utils/APIs';
import Alert from '../../components/Alert.vue';
import { alert, alertClose } from '../../utils/alert';

export default {

    data: () => ({
        phone: '',
        password: '',
    }),

    methods: {
        onSubmit() {
            alert({
                open: true,
                node: '124',
            });
        }
    },

    components: {
        'bootstrap-alert': Alert,
    }
}
</script>
