import 'simplemde/dist/simplemde.min.css';
import SimpleMDE from 'simplemde';
import markdownIt from 'markdown-it';
import plusImageSyntax from 'markdown-it-plus-image';
import fileUpload from '../../../../file_upload_v2';

const md = markdownIt({
    html: true
}).use(plusImageSyntax, `/api/v2/files/`);

function getState(cm, pos) {
    pos = pos || cm.getCursor("start");
    var stat = cm.getTokenAt(pos);
    if(!stat.type) return {};
    var types = stat.type.split(" ");
    var ret = {},
        data, text;
    for(var i = 0; i < types.length; i++) {
        data = types[i];
        if(data === "strong") {
            ret.bold = true;
        } else if(data === "variable-2") {
            text = cm.getLine(pos.line);
            if(/^\s*\d+\.\s/.test(text)) {
                ret["ordered-list"] = true;
            } else {
                ret["unordered-list"] = true;
            }
        } else if(data === "atom") {
            ret.quote = true;
        } else if(data === "em") {
            ret.italic = true;
        } else if(data === "quote") {
            ret.quote = true;
        } else if(data === "strikethrough") {
            ret.strikethrough = true;
        } else if(data === "comment") {
            ret.code = true;
        } else if(data === "link") {
            ret.link = true;
        } else if(data === "tag") {
            ret.image = true;
        } else if(data.match(/^header(\-[1-6])?$/)) {
            ret[data.replace("header", "heading")] = true;
        }
    }
    return ret;
}

const addImage = ({ codemirror, options }) => {
    const cm = codemirror,
        stat = getState(cm);

    let input = document.createElement('input');
    input.type = "file";
    input.addEventListener('change', (e) => {
        fileUpload(e.target.files[0], (id) => {
            _replaceSelection(cm, stat.image, options.insertTexts.addImage, id);
            input = null;
        });
    });

    input.click();
};

function _replaceSelection(cm, active, startEnd, url) {
    if(/editor-preview-active/.test(cm.getWrapperElement().lastChild.className))
        return;
    var text;
    var start = startEnd[0];
    var end = startEnd[1];
    var startPoint = cm.getCursor("start");
    var endPoint = cm.getCursor("end");
    if(url) {
        end = end.replace("#url#", url);
    }
    if(active) {
        text = cm.getLine(startPoint.line);
        start = text.slice(0, startPoint.ch);
        end = text.slice(startPoint.ch);
        cm.replaceRange(start + end, {
            line: startPoint.line,
            ch: 0
        });
    } else {
        text = cm.getSelection();
        cm.replaceSelection(start + text + end);

        startPoint.ch += start.length;
        if(startPoint !== endPoint) {
            endPoint.ch += start.length;
        }
    }
    cm.setSelection(startPoint, endPoint);
    cm.focus();
}

export const __default = {
    toolbar: [{
            name: "bold",
            className: "fa fa-bold",
            action: SimpleMDE.toggleBold,
            title: "粗体"
        }, {
            name: "italic",
            className: "fa fa-italic",
            action: SimpleMDE.toggleItalic,
            title: "斜体"
        }, {
            name: "strikethrough",
            className: "fa fa-strikethrough",
            action: SimpleMDE.toggleStrikethrough,
            title: "删除线"
        }, {
            name: "horizontal-rule",
            className: "fa fa-minus",
            action: SimpleMDE.drawHorizontalRule,
            title: "水平线"
        },
        '|', {
            name: "heading-1",
            className: "fa fa-header fa-header-x fa-header-1",
            action: SimpleMDE.toggleHeading1,
            title: "H1"
        }, {
            name: "heading-2",
            className: "fa fa-header fa-header-x fa-header-2",
            action: SimpleMDE.toggleHeading2,
            title: "H2"
        }, {
            name: "heading-3",
            className: "fa fa-header fa-header-x fa-header-3",
            action: SimpleMDE.toggleHeading3,
            title: "H3"
        },
        '|', {
            name: "code",
            className: "fa fa-code",
            action: SimpleMDE.toggleCodeBlock,
            title: "代码"
        }, {
            name: "quote",
            className: "fa fa-quote-left",
            action: SimpleMDE.toggleBlockquote,
            title: "引用"
        }, {
            name: "unordered-list",
            className: "fa fa-list-ul",
            action: SimpleMDE.toggleUnorderedList,
            title: "无序列表"
        }, {
            name: "ordered-list",
            className: "fa fa-list-ol",
            action: SimpleMDE.toggleOrderedList,
            title: "有序列表"
        },
        '|', {
            name: "link",
            className: "fa fa-link",
            action: SimpleMDE.drawLink,
            title: "超链接"
        }, {
            name: "image",
            className: "fa fa-picture-o",
            action: SimpleMDE.drawImage,
            title: "外链图片"
        }, {
            name: "add-image",
            className: "fa fa-plus",
            action: addImage,
            title: "自定义上传图片"
        }, {
            name: "table",
            className: "fa fa-table",
            action: SimpleMDE.drawTable,
            title: "插入表格"
        },
        '|', {
            name: "preview",
            className: "fa fa-eye no-disable",
            action: SimpleMDE.togglePreview,
            title: "预览"
        }, {
            name: "side-by-side",
            className: "fa fa-columns no-disable no-mobile",
            action: SimpleMDE.toggleSideBySide,
            title: "实时预览"
        }, {
            name: "fullscreen",
            className: "fa fa-arrows-alt no-disable no-mobile",
            action: SimpleMDE.toggleFullScreen,
            title: "全屏预览"
        },
        'guide'
    ],
    renderingConfig: {
        singleLineBreaks: false,
        codeSyntaxHighlighting: true,
    },
    insertTexts: {
        addImage: ["@![图片描述](", "#url#)"],
    },
    promptTexts: {
        link: "输入链接地址:",
        image: "输入图片地址:"
    },
    promptURLs: true,
    placeholder: "开始你的表演...",
    previewRender(plainText) {
        return md.render(plainText);
    }
};
export default(options = {}) => {
    options = Object.assign(__default, options);
    if(!options.element) {
        throw new Error("EditorTips: Attribute 'element' is required");
    }
    return new SimpleMDE(options);
};