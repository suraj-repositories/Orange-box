
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
        List,
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
    } from '/assets/ckeditor5/ckeditor5.js';

    function initCKEditor(editorElement) {
        if (editorElement.editorInstance) {
            editorElement.editorInstance.destroy()
                .then(() => {
                    editorElement.editorInstance = null;
                    editorElement.dataset.editorInitialized = '';
                    createEditor(editorElement);
                })
                .catch(console.error);
        } else {
            createEditor(editorElement);
        }
    }

    function createEditor(editorElement) {
        if (editorElement.dataset.editorInitializing === 'true') {
            return;
        }
        editorElement.dataset.editorInitializing = 'true';


        const isInModal = !!editorElement.closest('.modal');
        const plugins = [
            AccessibilityHelp, Alignment, Autosave, BlockQuote, Bold,
            CodeBlock, Essentials, Font, GeneralHtmlSupport, Heading, HorizontalLine, List,
            Indent, IndentBlock, Italic, Link, Paragraph, SelectAll, Style,
            Table, TableCaption, TableCellProperties, TableColumnResize,
            TableProperties, TableToolbar, Undo, Markdown
        ];

        if (!isInModal) plugins.push(SourceEditing);

        let toolbarItems = [
            'undo', 'redo', 'sourceEditing', '|',
            'bold', 'italic', '|', 'heading', '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
            'indent', 'outdent', 'alignment', '|', 'bulletedList', 'numberedList', '|',
            'horizontalLine', 'link', 'insertTable', 'blockQuote'
        ];

        if (editorElement.classList.contains("ckeditor-minimal")) {
            toolbarItems = [
                'bold', 'italic', '|', 'heading', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                'bulletedList', 'numberedList', 'alignment', '|',
                'horizontalLine', 'link', 'insertTable', 'blockQuote'
            ];
        }


        ClassicEditor.create(editorElement, {
            plugins: plugins,
            toolbar: {
                items: toolbarItems
            }
        }).then(editor => {
            editorElement.editorInstance = editor;
            editorElement.dataset.editorInitialized = 'true';
            editorElement.dataset.editorInitializing = 'false';

            window.editor = editor;

            const markdown = editorElement.dataset.markdown?.trim() || "";
            editor.setData(markdown);
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 50);

        }).catch(error => {
            console.error(error);
            editorElement.dataset.editorInitializing = 'false';
        });
    }

    document.querySelectorAll('.ckeditor:not(.modal .ckeditor)').forEach(el => initCKEditor(el));

    document.querySelectorAll('.modal').forEach(modal => {

        modal.addEventListener('shown.bs.modal', () => {
            modal.querySelectorAll('.ckeditor').forEach(initCKEditor);
        });

        modal.addEventListener('hidden.bs.modal', () => {
            modal.querySelectorAll('.ckeditor').forEach(el => {
                if (el.editorInstance) {
                    const data = el.editorInstance.getData();
                    el.value = data;
                    el.dataset.markdown = data;
                    el.editorInstance.destroy()
                        .then(() => {
                            el.editorInstance = null;
                            el.dataset.editorInitialized = '';
                        })
                        .catch(console.error);
                }
            });
        });
    });
