<template>
    <nav
        class="navbar navbar-default"
        :style="{
            borderRadius: 0,
        }"
    >
        <div class="container-fluid">
            <div class="navbar-header">
                <router-link
                    tag="a"
                    class="navbar-brand"
                    to="/file-storage"
                    replace
                >
                    存储设置
                </router-link>
            </div>

            <ul class="nav navbar-nav">
                <router-link exact tag="li" exact-active-class="active" :to="{ name: 'file-storage:home' }">
                    <a>基础设置</a>
                </router-link>
                <router-link tag="li" active-class="active" :to="{ name: 'file-storage:filesystems' }">
                    <a>文件系统</a>
                </router-link>
                <router-link tag="li" active-class="active" :to="{ name: 'file-storage:channels' }">
                    <a>频道设置</a>
                </router-link>
            </ul>
        </div>
    </nav>
</template>

