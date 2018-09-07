<template>
    <div class="container-fluid">
        <default-filesystem-panel />
        <local-filesystem-panel />
        <aliyun-oss-filesystem-panel />
    </div>
</template>

<script>
import DefaultFilesystemPanel from './default';
import LocalFilesystemPanel from './local';
import AliyunOssFilesystemPanel from './aliyun-oss';
export default {
    components: {
        DefaultFilesystemPanel,
        LocalFilesystemPanel,
        AliyunOssFilesystemPanel
    }
}
</script>

