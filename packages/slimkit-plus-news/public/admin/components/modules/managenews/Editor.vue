<template>
    <div id="editor" :class="{ full: isFull}" ref="editor">
        <div class="panel panel-default">
            <div class="panel-heading editor--header">
                <!-- 加粗、斜体、删除线、上传图片 -->
                <div class="btn-group">
                    <button class="btn btn-default" @click="addStrong" title="粗体(Ctrl + Alt + B)">
                        <i class="vf-bold"></i>
                    </button>
                    <button class="btn btn-default" @click="addItalic" title="斜体(Ctrl + Alt + I)">
                        <i class="vf-italic"></i>
                    </button>
                    <button class="btn btn-default" @click="addStrikethrough" title="删除线(Ctrl + Alt + S)">
                        <i class="vf-strike"></i>
                    </button>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-default" @click="addUl" title="无序列表(Ctrl + Alt + U)">
                        <i class="vf-ul"></i>
                    </button>
                    <button type="button" class="btn btn-default" @click="addOl" title="有序列表(Ctrl + Alt + L)">
                        <i class="vf-ol"></i>
                    </button>
                    <button type="button" class="btn btn-default" @click="addTable" title="表格(Ctrl + Alt + T)">
                        <i class="vf-table"></i>
                    </button>
                </div>
                <div class="btn-group">
                    <button class="btn btn-default" @click="deforeUpload" title="图片(Ctrl + Alt + P)">
                        <input ref="img" @change="uploadImg" type="file">
                        <i class="vf-image"></i>
                    </button>
                    <button class="btn btn-default" @click="addLink" title="超链接(Ctrl + Alt + K)">
                        <i class="vf-link"></i>
                    </button>
                </div>
                <!-- 全屏、预览 -->
                <div class="btn-group">
                    <!-- <button class="btn btn-default" @click="preview"> -->
                    <!-- <i class="vf-undo"></i> -->
                    <!-- </button> -->
                    <!-- <button class="btn btn-default" @click="preview"> -->
                    <!-- <i class="vf-redo"></i> -->
                    <!-- </button> -->
                    <button class="btn btn-default" @click="preview">
                        <i v-if="isPreview" class="vf-eye-close"></i>
                        <i v-else class="vf-eye"></i>
                    </button>
                    <button class="btn btn-default" @click="fullScreen">
                        <i v-if="isFull" class="vf-small"></i>
                        <i v-else class="vf-full"></i>
                    </button>
                </div>
            </div>
            <div class="panel-body editor--body" :style="{ height: fixHeight }">
                <div class="editor--input" :class="{all: !isPreview}">
                    <textarea ref="txtInput" placeholder="输入内容..." v-model="content" 
                    @keydown.ctrl.alt.b.prevent.stop="addStrong" 
                    @keydown.ctrl.alt.i.prevent.stop="addItalic"
                    @keydown.ctrl.alt.s.prevent.stop="addStrikethrough" 
                    @keydown.ctrl.alt.u.prevent.stop="addUl" 
                    @keydown.ctrl.alt.l.prevent.stop="addOl" 
                    @keydown.ctrl.alt.t.prevent.stop="addTable" 
                    @keydown.ctrl.alt.p.prevent.stop="deforeUpload" 
                    @keydown.ctrl.alt.k.prevent.stop="addLink" 
                    ></textarea>
                </div>
                <div class="editor--pre" v-if="isPreview">
                    <div class="markdown-body" ref="pre"></div>
                </div>
            </div>
            <div class="panel-footer">
                MarkdownEditor
            </div>
        </div>
    </div>
</template>
<script>
import { api, admin } from '../../../axios';

// markdown
import markdownIt from 'markdown-it';
import plusImageSyntax from 'markdown-it-plus-image';
// 语法高亮
import hljs from 'highlight.js';
// 引入样式库
import 'github-markdown-css';
import 'highlight.js/styles/github.css';

// 文件上传
import fileUpload from '../../../file_upload_v2';

const md = markdownIt({
    html: true,
    highlight: function(code) {
        return hljs ? hljs.highlightAuto(code).value : code
    }
}).use(plusImageSyntax, `${api.defaults.baseURL}/files/`);

export default {
    name: "module-editor",
    props: {
        value: {
            type: String,
            repuired: true
        }
    },
    data() {
        return({
            content: "",
            isPreview: false,
            isFull: false,
            editor: {},
            clientHeight: 0,
            txtInput: null
        });
    },
    computed: {
        pre_content() {
            // return md.render(this.content);
        },
        fixHeight() {
            return this.isFull ? this.clientHeight : '';
        },
    },
    watch: {
        value(val) {
            this.txtInput.value = val;
        },
        content(val){
            this.$emit('input', val);
        },
        /**
         * 全屏监听
         * @param  {Boolean}  val
         */
        isFull(val) {
            if(val) {
                document.querySelector('body').classList.add("body--full")
            } else {
                document.querySelector('body').classList.remove("body--full")
            }
        }
    },
    methods: {
        /**
         * 获取文本框选中内容
         * return {
         *     start:   开始下标,
         *     end:     结束下标
         *     length： 长度
         *     text：   选中内容
         * } || null
         */
        getSelection() {
            const e = this.txtInput;
            return('selectionStart' in e) ? (() => {
                let length = e.selectionEnd - e.selectionStart;
                return {
                    start: e.selectionStart,
                    end: e.selectionEnd,
                    length,
                    text: e.value.substr(e.selectionStart, length)
                }
            })() : null;
        },

        /**
         * 设置文本框选中内容
         * @param {Number} start
         * @param {Number} end
         */
        setSelection(start, len) {
            const e = this.txtInput;
            return('selectionStart' in e) ? (function() {
                e.selectionStart = start;
                e.selectionEnd = start + len;
            })() : null;
        },

        /**
         * 替换选中文字
         * @param  {String} txt
         */
        replaceSelection(txt) {
            const e = this.txtInput;
            e.focus();
            return('selectionStart' in e) ? (() => {
                e.value = e.value.substr(0, e.selectionStart) + txt + e.value.substr(e.selectionEnd, e.value.length);
            })() : () => {
                e.value += txt;
            }
        },

        /**
         * 全屏显示
         */
        fullScreen() {
            this.isFull = !this.isFull;
        },

        /**
         * 开启预览
         */
        preview() {
            this.isPreview = !this.isPreview;
        },

        inputting(){},

        deforeUpload() {
            this.$refs.img.click()
        },
        /**
         * 图片上传
         * @param  {Object} e
         */
        uploadImg(e) {
            this.uploading = true;
            let file = e.target.files[0];
            /**
             * 文件上传控件
             * @param  {Object}     file
             * @param  {Function}   callback
             */
            fileUpload(file, (id) => {
                this.uploading = false;
                this.addImg(file.name, id);
            });
        },

        /**
         * 图片
         * @param {String} name
         * @param {Number} id
         */
        addImg(name, id) {
            const { start, length, text } = this.getSelection(),
                NAME = length > 0 ? text : name,
                CURSOR = start + 3,
                IMGTAG = `@!(${NAME})[${id}]`;
            this.replaceSelection(IMGTAG);
            this.setSelection(CURSOR, len);
        },

        /**
         * 加粗
         */
        addStrong() {
            const { start, text, length } = this.getSelection(),
                CURSOR = start + 2,
                STRONGTAG = length > 0 ? `**${text}**` : `**加粗文本**`,
                len = STRONGTAG.length - 4;
            this.replaceSelection(STRONGTAG);
            this.setSelection(CURSOR, len);
        },

        /**
         * 斜体
         */
        addItalic() {
            const { start, text, length } = this.getSelection(),
                CURSOR = start + 1,
                ITALICTAG = length > 0 ? `*${text}*` : `*斜体文本*`,
                len = ITALICTAG.length - 2;
            this.replaceSelection(ITALICTAG);
            this.setSelection(CURSOR, len);
        },

        /**
         * 删除线
         */
        addStrikethrough() {
            const { start, text, length } = this.getSelection(),
                CURSOR = start + 2,
                STRIKETAG = length > 0 ? `~~${text}~~` : `~~删除线~~`,
                len = STRIKETAG.length - 4;
            this.replaceSelection(STRIKETAG);
            this.setSelection(CURSOR, len);
        },

        /**
         * 无序列表
         */
        addUl() {
            const { start, text, length } = this.getSelection(),
                CURSOR = start + 3,
                TXT = text.split('\n').map((l) => `\n- ${l}`).join(""),
                ULTAG = length > 0 ? TXT : `\n- 在这里列出文本`,
                len = 0;
            this.replaceSelection(ULTAG);
            this.setSelection(CURSOR, len);
        },

        /**
         * 有序列表
         */
        addOl() {
            const { start, text, length } = this.getSelection(),
                CURSOR = start + 3,
                TXT = text.split('\n').map((l, index) => `\n${index + 1}. ${l}`).join(""),
                ULTAG = length > 0 ? TXT : `\n1. 在这里列出文本`,
                len = 0;
            this.replaceSelection(ULTAG);
            this.setSelection(CURSOR, len);
        },

        /**
         * 表格
         */
        addTable() {
            const { start, text, length } = this.getSelection(),
                CURSOR = start + 1,
                TABLETAG = `\n标题1 | 标题2\n---|---\n第1行 第1列 | 第1行 第2列\n第2行 第1列 | 第2行 第2列\n`,
                len = TABLETAG.length - 2;
            this.replaceSelection(TABLETAG);
            this.setSelection(CURSOR, len);
        },

        /**
         * 外链
         */
        addLink() {
            const { start, text, length } = this.getSelection(),
                CURSOR = start + 1,
                LINK = prompt('输入链接地址', 'http://'),
                LINKTIPS = length > 0 ? text : "链接描述",
                LINKTAG = `[${LINKTIPS}](${LINK})`,
                len = LINKTIPS.length;
            this.replaceSelection(LINKTAG);
            this.setSelection(CURSOR, len);
        }
    },
    created() {
        this.content = this.value;
    },
    mounted() {
        this.editor = this.$refs.editor;
        this.txtInput = this.$refs.txtInput;
        this.pre = this.$refs.pre;

        this.clientHeight = `${document.documentElement.clientHeight - 50}px`;

        this.editor.addEventListener('keyup', ()=>{
            pre.innerHtml = md.render(this.content);
            // this.$refs.pre.innerHtml = md.render(this.content);
        });
        window.onresize = () => {
            this.clientHeight = `${document.documentElement.clientHeight - 50}px`;
        };
    }
}
</script>
<style lang='scss'>
@import './md.scss'
</style>