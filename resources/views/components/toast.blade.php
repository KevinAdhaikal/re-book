{{-- Global Modern Toast Notification Component --}}
<div id="rebook-toast-container" class="rebook-toast-container" aria-live="polite" aria-atomic="true"></div>

<style>
  .rebook-toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
    max-width: 380px;
    width: calc(100vw - 32px);
  }

  .rebook-toast {
    pointer-events: auto;
    position: relative;
    overflow: hidden;
    background: rgba(22, 27, 34, 0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--border-color, #30363d);
    border-radius: 8px;
    box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.55), 0 4px 10px -2px rgba(0, 0, 0, 0.35);
    display: flex;
    align-items: flex-start;
    padding: 12px 14px 14px 14px;
    gap: 12px;
    color: var(--text-main, #f0f6fc);
    font-family: var(--font-sans, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif);
    animation: rebookToastSlideIn 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transition: transform 0.2s ease, opacity 0.2s ease;
  }

  .rebook-toast.toast-hiding {
    animation: rebookToastSlideOut 0.22s cubic-bezier(0.7, 0, 0.84, 0) forwards;
  }

  .rebook-toast-icon {
    font-size: 1.15rem;
    margin-top: 1px;
    flex-shrink: 0;
  }

  .rebook-toast-success .rebook-toast-icon { color: #3fb950; }
  .rebook-toast-success { border-left: 3px solid #3fb950; }
  .rebook-toast-success .rebook-toast-progress-bar { background: #3fb950; }

  .rebook-toast-error .rebook-toast-icon { color: #f85149; }
  .rebook-toast-error { border-left: 3px solid #f85149; }
  .rebook-toast-error .rebook-toast-progress-bar { background: #f85149; }

  .rebook-toast-info .rebook-toast-icon { color: #58a6ff; }
  .rebook-toast-info { border-left: 3px solid #58a6ff; }
  .rebook-toast-info .rebook-toast-progress-bar { background: #58a6ff; }

  .rebook-toast-body {
    flex: 1;
    min-width: 0;
  }

  .rebook-toast-title {
    font-size: 0.85rem;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 2px;
  }

  .rebook-toast-message {
    font-size: 0.82rem;
    color: var(--text-muted, #8b949e);
    line-height: 1.4;
    word-break: break-word;
  }

  .rebook-toast-close {
    background: transparent;
    border: none;
    color: var(--text-muted, #8b949e);
    cursor: pointer;
    font-size: 0.85rem;
    padding: 2px 4px;
    line-height: 1;
    border-radius: 4px;
    transition: color 0.15s ease, background 0.15s ease;
    flex-shrink: 0;
  }

  .rebook-toast-close:hover {
    color: var(--text-main, #f0f6fc);
    background: rgba(255, 255, 255, 0.08);
  }

  .rebook-toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: rgba(255, 255, 255, 0.08);
    overflow: hidden;
  }

  .rebook-toast-progress-bar {
    height: 100%;
    width: 100%;
    transform-origin: left;
    animation: rebookToastProgress linear forwards;
  }

  @keyframes rebookToastSlideIn {
    0% {
      opacity: 0;
      transform: translateX(40px) scale(0.96);
    }
    100% {
      opacity: 1;
      transform: translateX(0) scale(1);
    }
  }

  @keyframes rebookToastSlideOut {
    0% {
      opacity: 1;
      transform: translateX(0) scale(1);
    }
    100% {
      opacity: 0;
      transform: translateX(40px) scale(0.96);
    }
  }

  @keyframes rebookToastProgress {
    0% { transform: scaleX(1); }
    100% { transform: scaleX(0); }
  }
</style>

<script>
  (function () {
    const container = document.getElementById('rebook-toast-container');

    window.showToast = function ({ title = '', message = '', type = 'success', duration = 3500 }) {
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = `rebook-toast rebook-toast-${type}`;

      let iconClass = 'fa-solid fa-circle-check';
      let defaultTitle = 'Sukses';
      if (type === 'error') {
        iconClass = 'fa-solid fa-circle-xmark';
        defaultTitle = 'Peringatan';
      } else if (type === 'info') {
        iconClass = 'fa-solid fa-circle-info';
        defaultTitle = 'Info';
      }

      toast.innerHTML = `
        <i class="${iconClass} rebook-toast-icon"></i>
        <div class="rebook-toast-body">
          <div class="rebook-toast-title">${title || defaultTitle}</div>
          <div class="rebook-toast-message">${message}</div>
        </div>
        <button class="rebook-toast-close" title="Tutup">&times;</button>
        <div class="rebook-toast-progress">
          <div class="rebook-toast-progress-bar" style="animation-duration: ${duration}ms;"></div>
        </div>
      `;

      function dismiss() {
        if (toast.classList.contains('toast-hiding')) return;
        toast.classList.add('toast-hiding');
        setTimeout(() => {
          if (toast.parentElement) toast.parentElement.removeChild(toast);
        }, 220);
      }

      toast.querySelector('.rebook-toast-close').addEventListener('click', dismiss);

      const timer = setTimeout(dismiss, duration);

      // Pause on hover
      toast.addEventListener('mouseenter', () => {
        clearTimeout(timer);
        const pb = toast.querySelector('.rebook-toast-progress-bar');
        if (pb) pb.style.animationPlayState = 'paused';
      });

      toast.addEventListener('mouseleave', () => {
        const pb = toast.querySelector('.rebook-toast-progress-bar');
        if (pb) pb.style.animationPlayState = 'running';
        setTimeout(dismiss, 1200);
      });

      container.appendChild(toast);
    };

    // Auto-fire session flash messages if present
    @if(session('success'))
      window.addEventListener('DOMContentLoaded', () => {
        window.showToast({
          title: 'Berhasil',
          message: @json(session('success')),
          type: 'success'
        });
      });
    @endif

    @if(session('error'))
      window.addEventListener('DOMContentLoaded', () => {
        window.showToast({
          title: 'Gagal',
          message: @json(session('error')),
          type: 'error'
        });
      });
    @endif
  })();
</script>
