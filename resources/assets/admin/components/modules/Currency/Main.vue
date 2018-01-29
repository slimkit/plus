<template>
    <div class="clearfix">
        <div class="container-fluid" style="padding-top: 70px; position: relative;">

            <nav class="navbar navbar-default navbar-fixed-top" style="position: absolute;">
                <div class="navbar-header">

                <span class="navbar-brand">
                    <i class="glyphicon glyphicon-asterisk"></i>
                    积分设置
                </span>

                </div>
            </nav>
            <module-alert></module-alert>
            <router-view />
        </div>
    </div>
</template>
<script>
    import Alert from '../Alert';
    export default{
        components: { [Alert.name]: Alert },
    }
</script>