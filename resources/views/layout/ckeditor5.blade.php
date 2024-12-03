
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "{{ asset('assets/ckeditor5/ckeditor5.js') }}",
            "ckeditor5/": "{{ asset('assets/ckeditor5/') }}"
        }
    }
</script>

<script type="module">
    import {
        ClassicEditor,
        AccessibilityHelp,
        Alignment,
        AutoLink,
        Autosave,
        BlockQuote,
        Bold,
        CodeBlock,
        Essentials,
        Font,
        GeneralHtmlSupport,
        Heading,
        HorizontalLine,
        Indent,
        IndentBlock,
        Italic,
        Link,
        Paragraph,
        SelectAll,
        Style,
        Table,
        TableCaption,
        TableCellProperties,
        TableColumnResize,
        TableProperties,
        TableToolbar,
        Undo
    } from 'ckeditor5';

    let ckEditors = document.querySelectorAll(".ckeditor");

    ckEditors.forEach((editor) => {
        ClassicEditor
            .create(editor, {
                plugins: [AccessibilityHelp, Alignment,
                    AutoLink,
                    Autosave,
                    BlockQuote,
                    Bold,
                    CodeBlock,
                    Essentials,
                    Font,
                    GeneralHtmlSupport,
                    Heading,
                    HorizontalLine,
                    Indent,
                    IndentBlock,
                    Italic,
                    Link,
                    Paragraph,
                    SelectAll,
                    Style,
                    Table,
                    TableCaption,
                    TableCellProperties,
                    TableColumnResize,
                    TableProperties,
                    TableToolbar,
                    Undo
                ],
                toolbar: {
                    items: ['undo',
                        'redo',
                        '|',
                        'bold',
                        'italic',
                        '|',
                        '|',
                        'heading',
                        '|',
                        'fontSize',
                        'fontFamily',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'indent',
                        'outdent',
                        'alignment',
                        '|',
                        'horizontalLine',
                        'link',
                        'insertTable',
                        'blockQuote',
                        '|',


                    ]

                }
            })
            .then(editor => {
                window.editor = editor;
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
