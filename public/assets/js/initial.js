const interval = setInterval(() => {
    if (!document.body) return;

    if (localStorage.getItem('documentation-editor-full-screen')) {
        document.body.setAttribute('data-screen-type', 'content');
        clearInterval(interval);
    }
}, 50);

