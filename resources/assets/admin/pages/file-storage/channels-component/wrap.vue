<template>
    <div class="container-fluid">
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

