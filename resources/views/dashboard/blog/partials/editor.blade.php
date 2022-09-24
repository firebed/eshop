<script defer>
    tinymce.init({
        selector: '#content',
        plugins: ['template advlist autolink link image lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'table emoticons template paste help'],
        entity_encoding: 'raw',
        paste_as_text: true,
        relative_urls: false,
        branding: false,
        remove_script_host: false,
        document_base_url: 'https://www.plexoudes.gr',
        menu: {
            file: {title: 'File', items: 'newdocument restoredraft | preview | print '},
            edit: {title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace'},
            view: {title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen'},
            insert: {title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime'},
            format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat'},
            tools: {title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount'},
            table: {title: 'Table', items: 'inserttable | cell row column | tableprops deletetable'},
            help: {title: 'Help', items: 'help'}
        },
        image_advtab: true,
        image_class_list: [
            {title: "Fluid", value: 'img-fluid rounded'}
        ],
        file_picker_types: 'image',
        content_css: "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css",
        content_css_cors: true,
        content_style: "body { padding: .75rem }",
        media_dimensions: false,
        image_dimensions: false,

        toolbar: 'undo redo | formatselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify bullist numlist outdent indent | link image media | forecolor backcolor',

        @isset($blog)
        images_upload_url: '{{ route('blogs.upload', $blog) }}',
        images_upload_handler: function (blobInfo, success, failure) {
            const formData = new FormData();
            formData.append("image", blobInfo.blob());
            axios.post('{{ route('blogs.upload', $blog) }}', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => success(res.data.location))
                .catch(err => failure("Error"))
        },

        setup(editor) {
            editor.on("keydown", function(e){
                if ((e.keyCode === 8 || e.keyCode === 46) && tinymce.activeEditor.selection) {
                    const selectedNode = tinymce.activeEditor.selection.getNode();
                    if (selectedNode && selectedNode.nodeName === 'IMG') {
                        const src = selectedNode.src;
                        axios.delete('{{ route('blogs.delete-upload', $blog) }}', {data: {src}})
                            .then(r => console.log(r.data))
                    }

                }
            });
        }
        @endisset
    });
</script>