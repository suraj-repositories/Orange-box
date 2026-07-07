# Documentation Editor Overview

The Documentation Editor provides a rich Markdown editing experience with live preview, enhanced components, and GitHub-style formatting. It is designed to help you create beautiful, readable documentation without leaving your application.

## Features

- ✨ Live Markdown preview
- 🎨 GitHub-inspired styling
- 📋 Rich tables
- 📝 Typography enhancements
- 📦 Syntax-highlighted code blocks
- ⚠️ Alert callouts
- 🖼️ Responsive images
- 📑 Nested lists
- 🌙 Light and dark mode support
- 📱 Responsive layout

---

## Available Components

### Typography

Typography includes all standard Markdown text elements.

- Headings (H1–H6)
- Paragraphs
- Bold text
- Italic text
- Strikethrough
- Blockquotes
- Horizontal rules
- Inline code
- Links

**Example**

```markdown
# Heading 1

## Heading 2

This is a paragraph with **bold**, *italic*, ~~strikethrough~~ and `inline code`.

> This is a blockquote.

---

Visit [Orange Box](https://example.com)
```

---

### Lists

Create ordered, unordered, nested, and task lists.

Supported:

- Bullet lists
- Numbered lists
- Nested lists
- Task checklists

**Example**

```markdown
- Dashboard
- Users
    - Create User
    - Edit User

1. Install
2. Configure
3. Deploy

- [x] Documentation
- [ ] Testing
```

---

### Tables

Display structured information using Markdown tables.

Supports:

- Left alignment
- Center alignment
- Right alignment

**Example**

```markdown
| Name | Role | Status |
|------|:----:|------:|
| John | Admin | Active |
| Jane | Editor | Pending |
| Alex | User | Disabled |
```

---

### Images

Insert responsive images into your documentation.

**Example**

```markdown
![Application Dashboard](images/dashboard.png)
```

or

```markdown
![Logo](https://example.com/logo.png)
```

---

### Code

Display code with syntax highlighting.

Supports fenced code blocks and inline code.

**Example**

````markdown
```php
Route::get('/', function () {
    return view('welcome');
});
```
