import './bootstrap';

import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Paragraph from '@editorjs/paragraph';
import List from '@editorjs/list';
import Quote from '@editorjs/quote';
import Marker from '@editorjs/marker';
import Checklist from '@editorjs/checklist';
import Delimiter from '@editorjs/delimiter';
import Embed from '@editorjs/embed';
import Table from '@editorjs/table';
import LinkTool from '@editorjs/link';
import RawTool from '@editorjs/raw';
import InlineCode from '@editorjs/inline-code';

import ImageTool from '@editorjs/image';
import CodeTool from '@editorjs/code';

import 'prismjs/themes/prism-tomorrow.css';
import Prism from 'prismjs';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-python';
import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-bash';
import 'prismjs/components/prism-css';


const LANGUAGES = ['javascript', 'php', 'python', 'markup', 'bash', 'css'];

document.addEventListener("DOMContentLoaded", () => {
    enableEditorJs();
    enableEditorJsPreview('[data-ob-preview-type="editorjs"]');

});

function enableEditorJs() {
    const editorElement = document.querySelector('#editorjs-editor');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    if (!editorElement) {
        return;
    }

    const fileUploadUrl = editorElement.getAttribute("data-ob-image-upload-url");
    const fetchDataUrl = editorElement.getAttribute("data-ob-fetch-data-url");
    const fetchOnlineMediaUrl = editorElement.getAttribute("data-ob-fetch-online-media-url");
    const cacheableId = editorElement.getAttribute("data-ob-cacheable-id");

    const previewToggleCheckboxSelector = editorElement.getAttribute("data-ob-preview-toggle-checkbox");
    const previewToggleCheckbox = document.querySelector(previewToggleCheckboxSelector);

    const submitFormSelector = editorElement.getAttribute("data-ob-submit-form");
    const submitForm = document.querySelector(submitFormSelector);

    let preloadData = editorElement.getAttribute("data-ob-content");

    try {
        if (preloadData) {
            preloadData = JSON.parse(preloadData);
            if (Array.isArray(preloadData) && preloadData.length === 1 && typeof preloadData[0] === 'string') {
                const inner = JSON.parse(preloadData[0]);
                preloadData = inner.blocks || [];
            }
            else if (preloadData.blocks && Array.isArray(preloadData.blocks)) {
                preloadData = preloadData.blocks;
            }
            else if (!Array.isArray(preloadData)) {
                preloadData = [preloadData];
            }
        } else {
            preloadData = [
                { type: 'paragraph', data: { text: 'Start writing your content and code below...' } }
            ];
        }
    } catch (err) {
        console.error("Error processing preloadData:", err);
        preloadData = [
            { type: 'paragraph', data: { text: 'Start writing your content and code below...' } }
        ];
    }


    console.log(preloadData);
    const editor = new EditorJS({
        holder: 'editorjs-editor',
        autofocus: true,
        tools: {
            header: { class: Header, inlineToolbar: ['link'], config: { levels: [1, 2, 3, 4, 5, 6], defaultLevel: 2 } },
            paragraph: { class: Paragraph, inlineToolbar: true },
            list: { class: List, inlineToolbar: true },
            code: {
                class: CodeTool,
                config: {
                    theme: 'dark',
                    defaultLanguage: 'javascript',
                    enableLineNumbers: true
                }
            },
            image: {
                class: ImageTool,
                inlineToolbar: ['link'],
                config: {
                    endpoints: {
                        byFile: fileUploadUrl,
                        byUrl: fetchOnlineMediaUrl
                    },
                    additionalRequestHeaders: {
                        'X-CSRF-TOKEN': csrfToken
                    }

                }
            },
            embed: { class: Embed, inlineToolbar: false, config: { services: { youtube: true, vimeo: true, twitter: true, codepen: true } } },
            table: { class: Table, inlineToolbar: true },
            quote: { class: Quote, inlineToolbar: true },
            marker: Marker,
            checklist: Checklist,
            delimiter: Delimiter,
            linkTool: { class: LinkTool, config: { endpoint: fetchDataUrl } },
            raw: RawTool,
            inlineCode: InlineCode
        },
        data: {
            blocks: preloadData
        }
    });

    if (submitForm) {
        submitForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const savedData = await editor.save();
            if (cacheableId) {
                localStorage.removeItem(cacheableId);
            }

            let input = submitForm.querySelector('input[name="editor_content"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'editor_content';
                submitForm.appendChild(input);
            }
            input.value = JSON.stringify(savedData);

            const statusInput = document.querySelector('form input[name="submit_status"]');
            if (e.submitter && e.submitter.textContent.toLowerCase().includes('publish')) {
                statusInput.value = 'publish';
            } else {
                statusInput.value = 'draft';
            }

            submitForm.submit();
        });
    }

    if (previewToggleCheckbox) {

        previewToggleCheckbox.addEventListener('change', async function () {
            console.log(132456);

            let outputElement = document.querySelector('#ob-editorjs-output');
            if (!outputElement) {
                outputElement = document.createElement("pre");
                outputElement.id = "ob-editorjs-output";
                outputElement.classList.add("hide");

                editorElement.insertAdjacentElement('afterend', outputElement);
            }

            if (previewToggleCheckbox.checked) {
                editorElement.classList.add('hide');
                outputElement.classList.remove("hide");

                const savedData = await editor.save();
                const innerContent = editorJsonToHtml(savedData);
                outputElement.innerHTML = "";
                outputElement.appendChild(innerContent);

                if (cacheableId) {
                    localStorage.setItem(cacheableId, JSON.stringify(savedData));
                }

            } else {
                outputElement.classList.add("hide");
                editorElement.classList.remove('hide');
            }


        });
    }


}
function enableEditorJsPreview(previewSelector) {
    try {
        const previewElements = document.querySelectorAll(previewSelector);

        if (!previewElements || previewElements.length === 0) {
            console.warn(`No elements found for selector: ${previewSelector}`);
            return;
        }

        previewElements.forEach(element => {
            try {
                const contentString = element.getAttribute("data-ob-content");
                if (!contentString) {
                    console.warn("No data-ob-content found for given element!");
                    return;
                }

                let parsedJson;
                try {
                    parsedJson = JSON.parse(contentString);
                } catch (jsonError) {
                    console.error("Failed to parse JSON with error :", jsonError);
                    return;
                }

                const htmlElement = editorJsonToHtml(parsedJson);
                element.innerHTML = '';
                element.appendChild(htmlElement);
            } catch (elementError) {
                console.error("Error processing :", elementError);
            }
        });
    } catch (error) {
        console.error("Error :", error);
    }
}
function editorJsonToHtml(savedData) {
    const output = document.createElement("div");
    output.classList.add("editorjs-preview");

    savedData.blocks.forEach(block => {
        switch (block.type) {
            case 'header':
                const h = document.createElement(`h${block.data.level || 2}`);
                h.textContent = block.data.text;
                output.appendChild(h);
                break;

            case 'paragraph':
                const p = document.createElement('p');
                p.textContent = block.data.text;
                output.appendChild(p);
                break;

            case 'list':
                const listEl = document.createElement(block.data.style === 'ordered' ? 'ol' : 'ul');
                block.data.items.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML = item.content;
                    listEl.appendChild(li);
                });
                output.appendChild(listEl);
                break;

            case 'quote':
                const blockquote = document.createElement('blockquote');
                blockquote.innerHTML = block.data.text;
                const caption = block.data.caption?.trim();
                if (caption && caption !== '<br>') {
                    const cite = document.createElement('cite');

                    cite.innerHTML = block.data.caption;
                    blockquote.appendChild(cite);
                }
                output.appendChild(blockquote);
                break;

            case 'delimiter':
                const hr = document.createElement('hr');
                output.appendChild(hr);
                break;

            case 'linkTool':
                const a = document.createElement('a');
                a.href = block.data.link;
                a.textContent = block.data.link || block.data.meta?.title || 'Link';
                a.target = '_blank';
                a.rel = 'noopener noreferrer';
                output.appendChild(a);
                break;

            case 'raw':
                const rawDiv = document.createElement('div');
                rawDiv.innerHTML = block.data.html;
                output.appendChild(rawDiv);
                break;

            case 'image':
                const figure = document.createElement('figure');
                const img = document.createElement('img');
                img.src = block.data.file?.url || block.data.url || '';
                img.alt = block.data.caption || '';
                figure.appendChild(img);
                if (block.data.caption) {
                    const cap = document.createElement('figcaption');
                    cap.innerHTML = block.data.caption;
                    figure.appendChild(cap);
                }
                if (block.data.withBorder) {
                    figure.classList.add('figure-border');
                }
                if (block.data.withBackground) {
                    figure.classList.add('figure-background');
                }
                if (block.data.stretched) {
                    figure.classList.add('figure-stretched');
                }
                output.appendChild(figure);
                break;

            case 'checklist':
                const ul = document.createElement('ul');
                ul.classList.add('editorjs-checklist');
                block.data.items.forEach(item => {
                    const li = document.createElement('li');
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.checked = item.checked;
                    li.appendChild(checkbox);
                    li.appendChild(document.createTextNode(decodeEntities(item.text)));
                    ul.appendChild(li);
                });
                output.appendChild(ul);
                break;

            case 'code':
                const pre = document.createElement('pre');
                pre.style.position = 'relative';
                const codeEl = document.createElement('code');
                const lang = block.data.language || 'javascript';
                codeEl.className = `language-${lang}`;
                codeEl.textContent = block.data.code || '';
                pre.appendChild(codeEl);

                const copyBtn = document.createElement('button');
                copyBtn.type = "button";
                copyBtn.innerHTML = "<i class='bx bx-copy'></i>";
                copyBtn.classList.add('code-copy-btn');
                copyBtn.addEventListener('click', () => {
                    navigator.clipboard.writeText(codeEl.textContent);
                    copyBtn.innerHTML = "<i class='bx bx-check-circle'></i>";
                    setTimeout(() => {
                        copyBtn.innerHTML = "<i class='bx bx-copy'></i>";
                    }, 1500);
                });
                pre.appendChild(copyBtn);

                output.appendChild(pre);
                Prism.highlightElement(codeEl);
                break;

            case 'embed':
                const iframe = document.createElement('iframe');
                iframe.src = block.data.embed || block.data.source || '';
                iframe.width = block.data.width || '100%';
                iframe.height = block.data.height || 320;
                iframe.style.border = '0';
                output.appendChild(iframe);
                break;

            case 'table':
                if (Array.isArray(block.data.content)) {
                    const table = document.createElement('table');
                    table.style.width = '100%';
                    table.style.borderCollapse = 'collapse';
                    block.data.content.forEach(row => {
                        const tr = document.createElement('tr');
                        row.forEach(cell => {
                            const td = document.createElement('td');
                            td.textContent = cell;
                            td.style.border = '1px solid #ddd';
                            td.style.padding = '6px';
                            tr.appendChild(td);
                        });
                        table.appendChild(tr);
                    });
                    output.appendChild(table);
                }
                break;

            default:
                const preRaw = document.createElement('pre');
                preRaw.textContent = JSON.stringify(block, null, 2);
                output.appendChild(preRaw);
        }
    });

    return output;
}
function decodeEntities(str) {
    const el = document.createElement("textarea");
    el.innerHTML = str;
    return el.value;
}
