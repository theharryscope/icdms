const installPromptKey = 'icdms-pwa-install-dismissed';
let deferredInstallPrompt = null;

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}

window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredInstallPrompt = event;

    if (localStorage.getItem(installPromptKey) !== 'true') {
        showInstallPrompt();
    }
});

window.addEventListener('appinstalled', () => {
    deferredInstallPrompt = null;
    document.querySelector('[data-pwa-install]')?.remove();
});

function showInstallPrompt() {
    if (document.querySelector('[data-pwa-install]')) {
        return;
    }

    const prompt = document.createElement('aside');
    prompt.dataset.pwaInstall = 'true';
    prompt.className = 'fixed inset-x-4 bottom-4 z-50 mx-auto flex max-w-md items-center gap-4 rounded-xl border border-ochre-dim bg-surface p-4 text-ink shadow-2xl sm:inset-x-auto sm:right-6 sm:ml-auto';
    prompt.innerHTML = `
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-ochre text-lg font-bold text-canvas">I</div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-bold">Install ICDMS</p>
            <p class="mt-0.5 text-xs text-ink-muted">Keep the foundation portal one tap away.</p>
        </div>
        <button type="button" data-pwa-dismiss class="shrink-0 rounded-lg px-2 py-1 text-xs font-semibold text-ink-muted hover:bg-surface-raised hover:text-ink">Later</button>
        <button type="button" data-pwa-install-action class="shrink-0 rounded-lg bg-ochre px-3 py-2 text-xs font-bold text-canvas hover:bg-ochre/90">Install</button>
    `;

    prompt.querySelector('[data-pwa-dismiss]').addEventListener('click', () => {
        localStorage.setItem(installPromptKey, 'true');
        prompt.remove();
    });

    prompt.querySelector('[data-pwa-install-action]').addEventListener('click', async () => {
        if (!deferredInstallPrompt) {
            return;
        }

        deferredInstallPrompt.prompt();
        await deferredInstallPrompt.userChoice;
        deferredInstallPrompt = null;
        prompt.remove();
    });

    document.body.append(prompt);
}