<template>
  <!-- vmd ==> vue markdown -->
  <div class="vmd" ref="vmd">
    <div class="vmd-header" ref="vmdHeader">
      <div class="vmd-btn-group">
        <button type="button" class="vmd-btn vmd-btn-default" @click="addStrong" title="Ctrl + Alt + B"><i class="vf-bold"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="addItalic" title="Ctrl + Alt + I"><i class="vf-italic"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="addHeading" title="Ctrl + Alt + H"><i class="vf-header"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="addStrikethrough" title="Ctrl + Alt + D"><i class="vf-strikethrough"></i>
        </button>
      </div>
      <div class="vmd-btn-group">
        <button type="button" class="vmd-btn vmd-btn-default" @click="addUl" title="Ctrl + Alt + U"><i class="vf-list-ul"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="addOl" title="Ctrl + Alt + O"><i class="vf-list-ol"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="addTable" title="Ctrl + Alt + T"><i class="vf-table"></i></button>
      </div>
      <div class="vmd-btn-group">
        <button type="button" class="vmd-btn vmd-btn-default" @click="addLink" title="Ctrl + Alt + A"><i class="vf-chain"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="addImage" title="Ctrl + Alt + P"><i class="vf-image"></i></button>
      </div>
      <div class="vmd-btn-group">
        <button type="button" class="vmd-btn vmd-btn-default" @click="addCode" title="Ctrl + Alt + C"><i class="vf-code"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="addQuote" title="Ctrl + Alt + Q"><i class="vf-quote-left"></i></button>
      </div>
      <div class="vmd-btn-group">
        <button type="button" class="vmd-btn vmd-btn-default" @click="preview"><i :class="previewClass"></i></button>
        <button type="button" class="vmd-btn vmd-btn-default" @click="sanitizeHtml">HTML</button>
      </div>
    </div>
    <div class="vmd-body" ref="vmdBody">
      <textarea class="vmd-editor" :style="vmdEditorStyle" ref="vmdEditor"
                title="This is a editor."
                :value="vmdValue"
                @input="vmdInputting($event.target.value)"
                @focus="vmdActive"
                @blur="vmdInactive"
                @keydown.tab.prevent="addTab"
                @keydown.ctrl.alt.b.prevent="addStrong"
                @keydown.ctrl.alt.i.prevent="addItalic"
                @keydown.ctrl.alt.d.prevent="addStrikethrough"
                @keydown.ctrl.alt.h.prevent="addHeading"
                @keydown.ctrl.alt.l.prevent="addLine"
                @keydown.ctrl.alt.q.prevent="addQuote"
                @keydown.ctrl.alt.c.prevent="addCode"
                @keydown.ctrl.alt.a.prevent="addLink"
                @keydown.ctrl.alt.p.prevent="addImage"
                @keydown.ctrl.alt.t.prevent="addTable"
                @keydown.ctrl.alt.u.prevent="addUl"
                @keydown.ctrl.alt.o.prevent="addOl"
      ></textarea>
      <div class="vmd-preview markdown-body" ref="vmdPreview" v-show="isPreview" v-html="compiledMarkdown"></div>
    </div>
    <div class="vmd-footer" ref="vmdFooter">
      <a type="button" class="vmd-btn vmd-btn-default vmd-btn-borderless">Markdown</a>
    </div>
  </div>
</template>

<script>

  // 文件上传  
  import fileUpload from '../../file_upload_v2.js'

  // 引入依赖样式库
  import 'highlight.js/styles/monokai-sublime.css'
  import '../styles/markdown.css'
  import '../styles/iconfont.css'

  import locale from '../locale/zh'

  import marked from 'marked';
  import hljs from 'highlight.js';


  // 自定义解析 图片
  import plusImagePlugin from 'marked-plus-image';

  const basename = '/api/v2/files';

  marked.setOptions({
    basename,
    highlight: function (code) {
      return hljs ? hljs.highlightAuto(code).value : code
    }
  });

  /**
   * 定义__debounce函数 (函数去抖)
   *
   * @param fn 最终将执行的方法
   * @param delay 延时
   */
  function __debounce(fn, delay) {
    let timer = null;
    return function () {
      let context = this;
      let args = arguments;
      // 清除 timer
      clearTimeout(timer);
      timer = setTimeout(function () {
        fn.apply(context, args);
      }, delay);
    }
  }

  export default {
    name: 'VueEditor',
    props: {
      value: {
        type: String,
        default: null
      }
    },
    data() {
      return {
        vmd: null,
        vmdBody: null,
        vmdHeader: null,
        vmdFooter: null,
        vmdEditor: null,
        vmdPreview: null,
        vmdInput: '',
        lang: 'zh',
        isPreview: true,
        isSanitize: false
      }
    },
    computed: {
      vmdValue() {
        return this.$props.value || this.vmdInput;
      },
      /**
       * 编译成markdown文档
       */
      compiledMarkdown() {
        return marked( plusImagePlugin(marked, this.$props.value || this.vmdInput, {sanitize: this.isSanitize}))
      },
      vmdEditorStyle() {
        return this.isPreview ? null : {
          width: '100%'
        }
      },
      previewClass() {
        return this.isPreview ? 'vf-eye-slash' : 'vf-eye'
      }
    },
    mounted() {
      // 缓存DOM组件
      this.__saveDom();
      // 重置组件大小
      this.__resize();
      // 添加滚动监听事件
      window.addEventListener('resize', this.vmdResize, false);
      this.vmdEditor.addEventListener('scroll', this.vmdSyncScrolling, false);
      this.vmdPreview.addEventListener('scroll', this.vmdSyncScrolling, false);
      // 自动获取焦点
      this.vmdEditor.focus()
    },
    beforeDestroy() {
      // 移除滚动监听事件
      window.removeEventListener('resize', this.vmdResize, false);
      this.vmdEditor.removeEventListener('scroll', this.vmdSyncScrolling, false);
      this.vmdPreview.removeEventListener('scroll', this.vmdSyncScrolling, false);
      
      // 移除DOM组件
      this.__removeDom();
    },
    methods: {
      vmdActive() {
        this.$refs.vmd.classList.add('active')
      },
      vmdInactive() {
        this.$refs.vmd.classList.remove('active')
      },
      sanitizeHtml() {
        this.isSanitize = !this.isSanitize;
      },
      /**
       * 同步滚动
       */
      vmdSyncScrolling(e) {
        e = e || window.event;
        if (this.vmdEditor === e.target) {
          this.vmdPreview.scrollTop = e.target.scrollTop
        } else {
          this.vmdEditor.scrollTop = e.target.scrollTop
        }
      },
      vmdResize: __debounce(function (e) {
        this.__resize()
      }, 100),
      /**
       * 监听用户输入
       */
      vmdInputting: __debounce(function (value) {
        this.vmdEditor.value = value;
        this.__updateInput()
      }, 100),
      preview() {
        this.isPreview = !this.isPreview
      },
      /**
       * 扩展 Tab 快捷键
       */
      addTab() {
        this.__updateInput('\n' + this.__localize('tabText'));
      },
      addStrong() {
        let chunk, cursor, selected = this.__getSelection(), content = this.__getContent();

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('strongText');
        } else {
          chunk = selected.text;
        }

        // 替换选择内容并将光标设置到chunk内容前
        if (content.substr(selected.start - 2, 2) === '**'
          && content.substr(selected.end, 2) === '**') {
          this.__setSelection(selected.start - 2, selected.end + 2);
          this.__replaceSelection(chunk);
          cursor = selected.start - 2;
        } else {
          this.__replaceSelection('**' + chunk + '**');
          cursor = selected.start + 2;
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addItalic() {
        let chunk, cursor, selected = this.__getSelection(), content = this.__getContent();

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('italicText');
        } else {
          chunk = selected.text;
        }

        // 替换选择内容并将光标设置到chunk内容前
        if (content.substr(selected.start - 1, 1) === '_'
          && content.substr(selected.end, 1) === '_') {
          this.__setSelection(selected.start - 1, selected.end + 1);
          this.__replaceSelection(chunk);
          cursor = selected.start - 1;
        } else {
          this.__replaceSelection('_' + chunk + '_');
          cursor = selected.start + 1;
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addStrikethrough() {
        let chunk, cursor, selected = this.__getSelection(), content = this.__getContent();

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('strikethroughText');
        } else {
          chunk = selected.text;
        }

        // 替换选择内容并将光标设置到chunk内容前
        if (content.substr(selected.start - 2, 2) === '~~'
          && content.substr(selected.end, 2) === '~~') {
          this.__setSelection(selected.start - 2, selected.end + 2);
          this.__replaceSelection(chunk);
          cursor = selected.start - 2;
        } else {
          this.__replaceSelection('~~' + chunk + '~~');
          cursor = selected.start + 2;
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addHeading() {
        let chunk, cursor, selected = this.__getSelection(), content = this.__getContent(), pointer, prevChar;

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('headingText');
        } else {
          chunk = selected.text;
        }

        // 替换选择内容并将光标设置到chunk内容前
        if ((pointer = 4, content.substr(selected.start - pointer, pointer) === '### ')
          || (pointer = 3, content.substr(selected.start - pointer, pointer) === '###')) {
          this.__setSelection(selected.start - pointer, selected.end);
          this.__replaceSelection(chunk);
          cursor = selected.start - pointer;
        } else if (selected.start > 0 && (prevChar = content.substr(selected.start - 1, 1), !!prevChar && prevChar !== '\n')) {
          this.__replaceSelection('\n\n### ' + chunk);
          cursor = selected.start + 6;
        } else {
          // 元素前的空字符串
          this.__replaceSelection('### ' + chunk);
          cursor = selected.start + 4;
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addLine() {
        this.__updateInput('\n' + this.__localize('lineText'));
      },
      addQuote() {
        let chunk, cursor, selected = this.__getSelection();

        // 替换选择内容并将光标设置到chunk内容前
        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('quoteText');

          this.__replaceSelection('\n> ' + chunk);

          // 设置光标
          cursor = selected.start + 3;
        } else {
          if (selected.text.indexOf('\n') < 0) {
            chunk = selected.text;

            this.__replaceSelection('> ' + chunk);

            // 设置光标
            cursor = selected.start + 2;
          } else {
            let list = [];

            list = selected.text.split('\n');
            chunk = list[0];

            list.forEach(function (k, v) {
              list[k] = '> ' + v;
            });

            this.__replaceSelection('\n\n' + list.join('\n'));

            // 设置光标
            cursor = selected.start + 4;
          }
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addCode() {
        let chunk, cursor, selected = this.__getSelection(), content = this.__getContent();

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('codeText');
        } else {
          chunk = selected.text;
        }

        // 替换选择内容并将光标设置到chunk内容前
        if (content.substr(selected.start - 4, 4) === '```\n'
          && content.substr(selected.end, 4) === '\n```') {
          this.__setSelection(selected.start - 4, selected.end + 4);
          this.__replaceSelection(chunk);
          cursor = selected.start - 4;
        } else if (content.substr(selected.start - 1, 1) === '`'
          && content.substr(selected.end, 1) === '`') {
          this.__setSelection(selected.start - 1, selected.end + 1);
          this.__replaceSelection(chunk);
          cursor = selected.start - 1;
        } else if (content.indexOf('\n') > -1) {
          this.__replaceSelection('```\n' + chunk + '\n```');
          cursor = selected.start + 4;
        } else {
          this.__replaceSelection('`' + chunk + '`');
          cursor = selected.start + 1;
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addLink() {
        let chunk, cursor, selected = this.__getSelection(), link;

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('linkText');
        } else {
          chunk = selected.text;
        }

        link = prompt(this.__localize('linkTip'), 'http://');

        let urlRegex = new RegExp('^((http|https)://|(mailto:)|(//))[a-z0-9]', 'i');
        if (link !== null && link !== '' && link !== 'http://' && urlRegex.test(link)) {
          let div = document.createElement('div');
          div.appendChild(document.createTextNode(link));
          let sanitizedLink = div.innerHTML;

          // 替换选择内容并将光标设置到chunk内容前
          this.__replaceSelection('[' + chunk + '](' + sanitizedLink + ')');
          cursor = selected.start + 1;

          // 设置选择内容
          this.__setSelection(cursor, cursor + chunk.length);
        }
        this.__updateInput()
      },
      addImage() {
        let chunk, cursor, selected = this.__getSelection(), link;

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('imageText');
        } else {
          chunk = selected.text;
        }

        const files = document.createElement('input');
        files.type = "file";
        files.click();

        files.onchange = (e)=>{
          let file = e.target.files[0];
          fileUpload(file, (task_id) => {    
            if (typeof task_id === 'number') {
              let div = document.createElement('div');
              div.appendChild(document.createTextNode(task_id));
              let sanitizedLink = div.innerHTML;

              // 替换选择内容并将光标设置到chunk内容前
              this.__replaceSelection('\n@![' + chunk + '](' + sanitizedLink + ')');
              cursor = selected.start + 4;

              // 设置选择内容
              this.__setSelection(cursor, cursor + chunk.length);
            }
            this.__updateInput()
          });
        }
      },
      addTable() {
        let chunk, cursor, selected = this.__getSelection();

        if (selected.length === 0) {
          // 提供额外的内容
          chunk = '\n' + this.__localize('tableText');
        } else {
          chunk = selected.text;
        }

        // 替换选择内容并将光标设置到chunk内容前
        this.__replaceSelection(chunk);
        cursor = selected.start;

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addUl() {
        let chunk, cursor, selected = this.__getSelection();

        // 替换选择内容并将光标设置到chunk内容前
        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('ulText');

          this.__replaceSelection('- ' + chunk);
          // 设置光标
          cursor = selected.start + 2;
        } else {
          if (selected.text.indexOf('\n') < 0) {
            chunk = selected.text;

            this.__replaceSelection('- ' + chunk);

            // 设置光标
            cursor = selected.start + 2;
          } else {
            let list = [];

            list = selected.text.split('\n');
            chunk = list[0];

            list.forEach(function (k, v) {
              list[k] = '- ' + v;
            });

            this.__replaceSelection('\n\n' + list.join('\n'));

            // 设置光标
            cursor = selected.start + 4;
          }
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      addOl() {
        let chunk, cursor, selected = this.__getSelection();

        // 替换选择内容并将光标设置到chunk内容前
        if (selected.length === 0) {
          // 提供额外的内容
          chunk = this.__localize('olText');
          this.__replaceSelection('1. ' + chunk);
          // 设置光标
          cursor = selected.start + 3;
        } else {
          if (selected.text.indexOf('\n') < 0) {
            chunk = selected.text;

            this.__replaceSelection('1. ' + chunk);

            // 设置光标
            cursor = selected.start + 3;
          } else {
            let list = [];

            list = selected.text.split('\n');
            chunk = list[0];

            list.forEach(function (k, v) {
              list[k] = '1. ' + v;
            });

            this.__replaceSelection('\n\n' + list.join('\n'));

            // 设置光标
            cursor = selected.start + 5;
          }
        }

        // 设置选择内容
        this.__setSelection(cursor, cursor + chunk.length);
        this.__updateInput()
      },
      fullscreen() {

      },
      __saveDom() {
        this.vmd = this.$refs.vmd;
        this.vmdBody = this.$refs.vmdBody;
        this.vmdHeader = this.$refs.vmdHeader;
        this.vmdFooter = this.$refs.vmdFooter;
        this.vmdEditor = this.$refs.vmdEditor;
        this.vmdPreview = this.$refs.vmdPreview;
      },
      __removeDom() {
        this.vmd = null;
        this.vmdBody = null;
        this.vmdHeader = null;
        this.vmdFooter = null;
        this.vmdEditor = null;
        this.vmdPreview = null
      },
      __resize() {
        let vmdHeaderOffset = this.vmdHeader ? this.vmdHeader.offsetHeight : 0,
          vmdFooterOffset = this.vmdFooter ? this.vmdFooter.offsetHeight : 0;
        this.vmdBody.style.height = (this.vmd.offsetHeight - vmdHeaderOffset - vmdFooterOffset) + 'px';
      },
      __updateInput(txt) {
        if (txt) {
          this.vmdEditor.value += txt
        }

        this.$emit('input', this.vmdEditor.value);

        this.vmdEditor.focus()
      },
      __localize(tag) {
        return locale[this.lang][tag]
      },
      /**
       * 获取编辑器的值
       */
      __getContent() {
        return this.vmdEditor.value
      },
      /**
       * 获取选择的内容
       */
      __getSelection() {
        let e = this.vmdEditor;
        return (
          ('selectionStart' in e && function () {
            let l = e.selectionEnd - e.selectionStart;
            return {start: e.selectionStart, end: e.selectionEnd, length: l, text: e.value.substr(e.selectionStart, l)};
          }) ||

          /* 如果浏览器不支持 */
          function () {
            return null;
          }
        )();
      },
      /**
       * 设置选择的内容
       * @param start
       * @param end
       */
      __setSelection(start, end) {
        let e = this.vmdEditor;
        return (
          ('selectionStart' in e && function () {
            e.selectionStart = start;
            e.selectionEnd = end;
            return null;
          }) ||

          /* 如果浏览器不支持 */
          function () {
            return null;
          }
        )();
      },
      /**
       * 替换选择的内容
       * @param text
       */
      __replaceSelection(text) {
        let e = this.vmdEditor;
        return (
          ('selectionStart' in e && function () {
            e.value = e.value.substr(0, e.selectionStart) + text + e.value.substr(e.selectionEnd, e.value.length);
            // Set cursor to the last replacement end
            e.selectionStart = e.value.length;
            return null;
          }) ||

          /* 如果浏览器不支持 */
          function () {
            e.value += text;
            return null;
          }
        )();
      }
    }
  }
</script>

<style scoped>
  *:focus {
    outline: none;
  }

  *, :after, :before {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
  }

  .vmd {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    border: thin solid #ddd;
    text-align: left;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    -webkit-transition: all .3s linear;
    -moz-transition: all .3s linear;
    -ms-transition: all .3s linear;
    -o-transition: all .3s linear;
    transition: all .3s linear;
  }

  .vmd.active {
    border-color: #98cbe8;
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(152, 203, 232, 0.6);
  }

  .vmd .vmd-header, .vmd .vmd-footer {
    display: block;
    padding: 6px;
    border-bottom: thin solid #ddd;
    background: #f5f5f5;
  }

  .vmd .vmd-header {
    border-bottom: thin solid #ddd;
  }

  .vmd .vmd-footer {
    border-top: thin solid #ddd;
  }

  .vmd .vmd-body {
    height: inherit;
  }

  .vmd-body .vmd-editor, .vmd-body .vmd-preview {
    display: block;
    padding: .8rem;
    height: inherit;
    width: 50%;
    min-height: 100px;
    float: left;
    overflow: auto;
  }

  .vmd-body .vmd-editor {
    color: #3d4043;
    font-size: 1rem;
    line-height: 1.2rem;
    border: 0;
    resize: none;
    /*background: #00d1b2;*/
    background: #fff;
  }

  .vmd-body .vmd-preview {
    background: #fafafa;
  }

  .vmd-btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
  }

  .vmd-btn:hover {
    color: #333;
    text-decoration: none;
  }

  .vmd-btn:active,
  .vmd-btn.active {
    background-image: none;
    outline: 0;
    -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
    box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
  }

  .vmd-btn:focus {
    outline: none;
  }

  .vmd-btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
  }

  .vmd-btn-default:hover {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
  }

  .vmd-btn-default:active,
  .vmd-btn-default.active {
    color: #333;
    background-color: #e6e6e6;
    border-color: #adadad;
  }

  .vmd-btn-borderless {
    padding-top: 7px;
    padding-bottom: 7px;
    border: 0;
  }

  .vmd-btn-borderless, .vmd-btn-borderless:hover, .vmd-btn-borderless:active, .vmd-btn-borderless.active {
    box-shadow: none;
    background-color: transparent;
  }

  .vmd-btn-default:hover.vmd-btn-borderless {
    opacity: .5;
  }

  .vmd-btn-default:active.vmd-btn-borderless, .vmd-btn-default.active.vmd-btn-borderless {
    opacity: .7;
  }

  .vmd-btn-group {
    position: relative;
    display: inline-block;
    vertical-align: middle;
  }

  .vmd-btn-group > .vmd-btn {
    position: relative;
    float: left;
  }

  .vmd-btn-group > .vmd-btn:hover,
  .vmd-btn-group > .vmd-btn:focus,
  .vmd-btn-group > .vmd-btn:active,
  .vmd-btn-group > .vmd-btn.active {
    z-index: 2;
  }

  .vmd-btn-group .vmd-btn + .vmd-btn,
  .vmd-btn-group .vmd-btn + .vmd-btn-group,
  .vmd-btn-group .vmd-btn-group + .vmd-btn,
  .vmd-btn-group .vmd-btn-group + .vmd-btn-group {
    margin-left: -1px;
  }

  .vmd-btn-group > .vmd-btn:not(:first-child):not(:last-child):not(.dropdown-toggle) {
    border-radius: 0;
  }

  .vmd-btn-group > .vmd-btn:first-child {
    margin-left: 0;
  }

  .vmd-btn-group > .vmd-btn:first-child:not(:last-child):not(.dropdown-toggle) {
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
  }

  .vmd-btn-group > .vmd-btn:last-child:not(:first-child) {
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
  }

  .vmd-btn-group > .vmd-btn-group {
    float: left;
  }

  .vmd-btn-group > .vmd-btn-group:not(:first-child):not(:last-child) > .vmd-btn {
    border-radius: 0;
  }

  .vmd-btn-group > .vmd-btn-group:first-child:not(:last-child) > .vmd-btn:last-child {
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
  }

  .vmd-btn-group > .vmd-btn-group:last-child:not(:first-child) > .vmd-btn:first-child {
    border-bottom-left-radius: 0;
    border-top-left-radius: 0;
  }

  .vmd-body:before, .vmd-body:after,
  .vmd-btn-group:before, .vmd-btn-group:after {
    display: table;
    content: '';
  }

  .vmd-body:after,
  .vmd-btn-group:after {
    clear: both;
  }
</style>
