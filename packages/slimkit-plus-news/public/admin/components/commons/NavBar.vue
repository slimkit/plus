<template>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" aria-expanded="false" data-target="#news-navbar-collapse">
                    <span class="sr-only">切换导航</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand">资讯</span>
            </div>
            <div class="collapse navbar-collapse" id="news-navbar-collapse">
                <ul class="nav navbar-nav">
                    <router-link to="/setting" tag="li" active-class="active">
                        <a href="#">投稿配置</a>
                    </router-link>
                    <router-link to="/newslist" tag="li" active-class="active" exact>
                        <a href="#">资讯列表</a>
                    </router-link>
                    <router-link to="/cates" tag="li" active-class="active">
                        <a href="#">资讯分类列表</a>
                    </router-link>
                    <router-link to="/pinneds" tag="li" active-class="active">
                        <a href="#">置顶审核</a>
                    </router-link>
                    <router-link to="/deletelist" tag="li" active-class="active">
                        <a href="#">删除申请审核</a>
                    </router-link>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
export default { name: 'ui-nav-bar' };
</script>