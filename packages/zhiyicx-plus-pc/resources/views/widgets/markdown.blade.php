<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="{{ asset('assets/pc/markdown/pluseditor.css') }}">
<div id="layout" class="div">
    <div class="editormd">
        <textarea id="editor" style="display: none">{{$content or ''}}</textarea>
    </div>
</div>
<script type="text/javascript" src="{{ asset('assets/pc/markdown/pluseditor.min.js') }}"></script>
<style> .CodeMirror { height: {{$height or '435px'}} } </style>
<script type="text/javascript">
    var editor = new pluseditor({
        element: document.querySelector('#editor'),
        fileApiPath: "/api/v2/files/",
        placeholder: "{{ $place or '开始你的表演'}}",
        status: false,
        uploadFile: function(file, cb){
            fileUpload.init(file, function(image, f, id){
                cb(id);
            })
        }
    });
</script>