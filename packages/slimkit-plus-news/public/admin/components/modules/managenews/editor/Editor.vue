<template>
    <div class="markdown-con">
        <textarea id="editor-md" style="display:none;"></textarea>
    </div>
</template>
<script>
import Editor from './zy_editor';
import 'highlight.js/styles/github.css';
export default {
    props: {
        value: {
            type: String,
            required: true
        }
    },
    data() { return({}) },
    watch: {
        value(val) {
            if(val === this.editor.value()) return;
            this.editor.value(val);
        },
    },
    methods: {
        /**
         * 初始化编辑器
         */
        initialize() {
            // 实例化编辑器
            this.editor = new Editor({
                element: this.$el.firstElementChild,
            });

            this.bindingEvents();
        },
        /**
         * 监听输入事件
         */
        bindingEvents() {
            this.editor.codemirror.on('change', () => {
                this.$emit('input', this.editor.value());
            });
        },
    },
    mounted() {
        this.initialize();
        console.log(this.editor);
    },
    destroyed() {
        this.editor = null;
    },
};
</script>
<style lang="scss">
.markdown-con {
    [type="file"] {
        display: none !important;
    }
    * {
        box-sizing: border-box;
    }

    *::-webkit-scrollbar {
        width: 2px;
        height: 2px;
    }

    *::-webkit-scrollbar-thumb {
        background-color: #33BBBA !important;
    }

    *::-webkit-scrollbar-track {
        background-color: transparent !important;
    }

    *:focus {
        outline: 0 !important;
    }
    .CodeMirror {
        height: 500px;
    }
    .CodeMirror-fullscreen,
    .editor-preview-side {
        top: 100px;
    }
    .editor-toolbar.fullscreen {
        top: 50px;
    }
    .CodeMirror .cm-spell-error:not(.cm-url):not(.cm-comment):not(.cm-tag):not(.cm-word) {
        background: none;
    }
}
</style>