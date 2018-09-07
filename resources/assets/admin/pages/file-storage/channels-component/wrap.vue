<template>
    <div class="container-fluid">

        <div class="alert alert-danger">
            所有的频道文件系统不建议频繁修改，如果你需要修改，请将文件全部迁移到对应的文件系统后再进行修改！
            如果没有进行文件迁移就进行了文件系统切换，则切换完成后文件系统会抛出文件找不到的错误（<code>仅非正式环境下，正式环境模式则返回 NULL</code>）。
        </div>

        <public-channel-panel :filesystems="filesystems" />
    </div>
</template>

<script>
import PublicChannelPanel from './public';
export default {
    components: {
        PublicChannelPanel,
    },
    data: () => ({
        filesystems: [
            { value: 'local', name: '本地存储' },
            { value: 'AliyunOSS', name: '阿里云 OSS' },
        ]
    })
}
</script>

