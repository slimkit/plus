<template>
    <div class="container-fluid">
        <default-filesystem-panel />
        <local-filesystem-panel />
    </div>
</template>

<script>
import DefaultFilesystemPanel from './default';
import LocalFilesystemPanel from './local';
export default {
    components: {
        DefaultFilesystemPanel,
        LocalFilesystemPanel
    }
}
</script>

