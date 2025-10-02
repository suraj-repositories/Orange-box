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
        Undo,
        SourceEditing,
        Markdown
    } from "{{ asset('assets/ckeditor5/ckeditor5.js') }}";

    let ckEditors = document.querySelectorAll(".ckeditor");

    ckEditors.forEach((editorElement) => {
        ClassicEditor
            .create(editorElement, {
                plugins: [
                    AccessibilityHelp, Alignment, AutoLink, Autosave, BlockQuote, Bold,
                    CodeBlock, Essentials, Font, GeneralHtmlSupport, Heading, HorizontalLine,
                    Indent, IndentBlock, Italic, Link, Paragraph, SelectAll, Style,
                    Table, TableCaption, TableCellProperties, TableColumnResize,
                    TableProperties, TableToolbar, Undo, SourceEditing, Markdown
                ],
                toolbar: {
                    items: [
                        'undo', 'redo', '|', 'sourceEditing', '|',
                        'bold', 'italic', '|', 'heading', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'indent', 'outdent', 'alignment', '|',
                        'horizontalLine', 'link', 'insertTable', 'blockQuote'
                    ]
                }
            })
            .then(editor => {
                window.editor = editor;
                let markdown = editorElement.dataset.markdown?.trim() || "";
                editor.setData(markdown);
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
