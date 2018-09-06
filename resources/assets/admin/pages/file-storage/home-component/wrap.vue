<template>
    <div class="container-fluid">
        <file-size-panel />
        <file-mime-type-panel />
        <image-panel />
    </div>
</template>

<script>
import ImagePanel from './image';
import FileSizePanel from './file-size';
import FileMimeTypePanel from './file-mime-type';
export default {
    components: {
        ImagePanel,
        FileSizePanel,
        FileMimeTypePanel
    }
}
</script>

