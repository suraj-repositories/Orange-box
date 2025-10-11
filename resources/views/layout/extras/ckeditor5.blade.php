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

function initCKEditor(editorElement) {
    if (!editorElement.dataset.editorInitialized) {
        const isInModal = !!editorElement.closest('.modal');

        const plugins = [
            AccessibilityHelp, Alignment, AutoLink, Autosave, BlockQuote, Bold,
            CodeBlock, Essentials, Font, GeneralHtmlSupport, Heading, HorizontalLine,
            Indent, IndentBlock, Italic, Link, Paragraph, SelectAll, Style,
            Table, TableCaption, TableCellProperties, TableColumnResize,
            TableProperties, TableToolbar, Undo, Markdown
        ];

        if (!isInModal) plugins.push(SourceEditing);

        const toolbarItems = [
            'undo', 'redo', '|',
            'bold', 'italic', '|', 'heading', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
            'indent', 'outdent', 'alignment', '|',
            'horizontalLine', 'link', 'insertTable', 'blockQuote'
        ];

        if (!isInModal) toolbarItems.splice(2, 0, 'sourceEditing');

        ClassicEditor.create(editorElement, {
            plugins: plugins,
            toolbar: {
                items: toolbarItems
            }
        }).then(editor => {
            editorElement.dataset.editorInitialized = true;
            window.editor = editor;

            const markdown = editorElement.dataset.markdown?.trim() || "";
            editor.setData(markdown);
        }).catch(console.error);
    }
}

document.querySelectorAll('.ckeditor:not(.modal .ckeditor)').forEach(el => initCKEditor(el));

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('shown.bs.modal', () => {
        modal.querySelectorAll('.ckeditor').forEach(initCKEditor);
    });
});
</script>

<script>
document.addEventListener('focusin', event => {
    if (event.target.closest('.ck')) {
        event.stopImmediatePropagation();
    }
});
</script>
