// bootstrap.js (already imported by Laravel Mix/Vite)
import './bootstrap';

// Core Editor.js + Plugins
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

// Image and Code
import ImageTool from '@editorjs/image';
import CodeTool from '@editorjs/code';

// Prism.js for code highlighting
import 'prismjs/themes/prism-tomorrow.css';
import Prism from 'prismjs';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-python';
import 'prismjs/components/prism-markup';
import 'prismjs/components/prism-bash';
import 'prismjs/components/prism-css';

// Custom languages can be added as needed
const LANGUAGES = ['javascript', 'php', 'python', 'markup', 'bash', 'css'];

class CodeWithLanguage {
  static get toolbox() {
    return {
      title: 'Code',
      icon: '<svg width="18" height="18"><path d="M3 5l6 4-6 4V5zm12 0v8l-6-4 6-4z" fill="currentColor"/></svg>'
    };
  }

  constructor({data, api}) {
    this.api = api;
    this.data = {
      code: data.code || '',
      language: data.language || 'javascript'
    };
  }

  render() {
    // container
    const container = document.createElement('div');

    // language selector
    const select = document.createElement('select');
    const languages = ['javascript', 'php', 'python', 'markup', 'bash', 'css'];
    languages.forEach(lang => {
      const opt = document.createElement('option');
      opt.value = lang;
      opt.textContent = lang;
      if (lang === this.data.language) opt.selected = true;
      select.appendChild(opt);
    });
    select.addEventListener('change', (e) => {
      this.data.language = e.target.value;
      Prism.highlightElement(codeEl);
    });
    container.appendChild(select);

    // code textarea
    const codeEl = document.createElement('textarea');
    codeEl.placeholder = 'Write your code here...';
    codeEl.value = this.data.code;
    codeEl.style.width = '100%';
    codeEl.style.minHeight = '120px';
    codeEl.addEventListener('input', (e) => this.data.code = e.target.value);
    container.appendChild(codeEl);

    return container;
  }

  save(blockContent) {
    return this.data;
  }
}

document.addEventListener("DOMContentLoaded", () => {

  const editor = new EditorJS({
    holder: 'editor',
    autofocus: true,
    tools: {
      header: { class: Header, inlineToolbar: ['link'], config: { levels: [1,2,3,4,5,6], defaultLevel: 2 } },
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
            byFile: '/uploadFile',
            byUrl: '/fetchUrl'
          }
        }
      },
      embed: { class: Embed, inlineToolbar: false, config: { services: { youtube:true, vimeo:true, twitter:true, codepen:true } } },
      table: { class: Table, inlineToolbar: true },
      quote: { class: Quote, inlineToolbar: true },
      marker: Marker,
      checklist: Checklist,
      delimiter: Delimiter,
      linkTool: { class: LinkTool, config: { endpoint: '/fetchUrl' } },
      raw: RawTool,
      inlineCode: InlineCode
    },
    data: {
      blocks: [
        { type: 'header', data: { text: 'Code-Focused Editor.js', level: 2 } },
        { type: 'paragraph', data: { text: 'Start writing your content and code below...' } }
      ]
    }
  });

  const saveButton = document.getElementById('save-button');
  const output = document.getElementById('output');

  if (saveButton) {
    saveButton.addEventListener('click', async () => {
      try {
        const savedData = await editor.save();
        output.innerHTML = '';

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
            case 'image':
              const img = document.createElement('img');
              img.src = block.data.file?.url || block.data.url || '';
              img.alt = block.data.caption || '';
              img.style.maxWidth = '100%';
              output.appendChild(img);
              if (block.data.caption) {
                const cap = document.createElement('figcaption');
                cap.textContent = block.data.caption;
                output.appendChild(cap);
              }
              break;
            case 'code':
              const pre = document.createElement('pre');
              pre.style.position = 'relative';
              const codeEl = document.createElement('code');
              const lang = block.data.language || 'javascript';
              codeEl.className = `language-${lang}`;
              codeEl.textContent = block.data.code || '';
              pre.appendChild(codeEl);

              // Copy button
              const copyBtn = document.createElement('button');
              copyBtn.textContent = 'Copy';
              copyBtn.style.position = 'absolute';
              copyBtn.style.top = '5px';
              copyBtn.style.right = '5px';
              copyBtn.style.padding = '3px 6px';
              copyBtn.style.fontSize = '12px';
              copyBtn.addEventListener('click', () => navigator.clipboard.writeText(codeEl.textContent));
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

        const meta = document.createElement('details');
        meta.style.marginTop = '12px';
        meta.innerHTML = `<summary>Save JSON (click to expand)</summary><pre>${JSON.stringify(savedData, null, 2)}</pre>`;
        output.appendChild(meta);

      } catch (err) {
        console.error('Save failed:', err);
      }
    });
  }
});
