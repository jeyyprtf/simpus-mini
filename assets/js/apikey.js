document.addEventListener('click', async (event) => {
    const reveal = event.target.closest('.reveal-key');
    if (reveal) {
        const code = reveal.closest('.secret-line').querySelector('.secret-mask');
        const shown = reveal.getAttribute('aria-pressed') === 'true';
        code.textContent = shown ? '••••••••••••••••••••••••' : code.dataset.secret;
        reveal.setAttribute('aria-pressed', String(!shown));
        reveal.setAttribute('aria-label', shown ? 'Tampilkan API key' : 'Sembunyikan API key');
    }

    const copy = event.target.closest('.copy-key');
    if (copy) {
        const code = copy.closest('.secret-line').querySelector('.secret-mask');
        try {
            await navigator.clipboard.writeText(code.dataset.secret);
            copy.textContent = '✓';
            window.setTimeout(() => { copy.textContent = '⧉'; }, 1200);
        } catch {
            copy.textContent = '!';
            window.setTimeout(() => { copy.textContent = '⧉'; }, 1200);
        }
    }
});

document.querySelector('#key-search')?.addEventListener('input', (event) => {
    const query = event.target.value.trim().toLowerCase();
    document.querySelectorAll('.key-row').forEach((row) => {
        row.hidden = !row.dataset.search.includes(query);
    });
});

document.querySelectorAll('.delete-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm('Hapus API key ini secara permanen?')) event.preventDefault();
    });
});
