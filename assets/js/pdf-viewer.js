import * as pdfjsLib from 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.10.38/build/pdf.min.mjs';

pdfjsLib.GlobalWorkerOptions.workerSrc = window.yiariPdfViewer?.workerSrc || '';

const DEFAULT_SCALE = 1.2;
const MIN_SCALE = 0.7;
const MAX_SCALE = 2.8;
const SCALE_STEP = 0.2;

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-pdf-viewer]').forEach((viewer) => {
    initPdfViewer(viewer).catch(() => {
      showViewerError(viewer);
    });
  });
});

async function initPdfViewer(viewer) {
  const pdfUrl = viewer.dataset.pdfUrl || '';
  const title = viewer.dataset.pdfTitle || 'PDF document';
  const stage = viewer.querySelector('[data-pdf-stage]');
  const canvasList = viewer.querySelector('[data-pdf-canvas-list]');
  const status = viewer.querySelector('[data-pdf-status]');
  const handToolButton = viewer.querySelector('[data-pdf-action="hand-tool"]');

  if (!pdfUrl || !stage || !canvasList || !status) {
    throw new Error('Missing PDF viewer elements');
  }

  const state = {
    pdfDocument: null,
    renderedPages: [],
    scale: DEFAULT_SCALE,
    handToolActive: false,
    dragActive: false,
    dragStartX: 0,
    dragStartY: 0,
    scrollLeft: 0,
    scrollTop: 0,
  };

  viewer.dataset.handTool = 'false';
  bindToolbar(viewer, state);
  bindHandTool(stage, handToolButton, state);

  status.textContent = window.yiariPdfViewer?.strings?.loading || 'Loading PDF...';

  const loadingTask = pdfjsLib.getDocument({
    url: pdfUrl,
    withCredentials: false,
    useWorkerFetch: true,
  });

  state.pdfDocument = await loadingTask.promise;

  const pages = [];
  for (let pageNumber = 1; pageNumber <= state.pdfDocument.numPages; pageNumber += 1) {
    const page = await state.pdfDocument.getPage(pageNumber);
    const shell = document.createElement('section');
    shell.className = 'publication-pdfjs-page';

    const label = document.createElement('div');
    label.className = 'publication-pdfjs-page-label';
    label.textContent = `${window.yiariPdfViewer?.strings?.page || 'Page'} ${pageNumber}`;

    const canvasWrap = document.createElement('div');
    canvasWrap.className = 'publication-pdfjs-page-canvas-wrap';

    const canvas = document.createElement('canvas');
    canvas.className = 'publication-pdfjs-canvas';
    canvas.setAttribute('aria-label', `${title} - page ${pageNumber}`);

    canvasWrap.appendChild(canvas);
    shell.appendChild(label);
    shell.appendChild(canvasWrap);
    canvasList.appendChild(shell);

    pages.push({ page, canvas, shell });
  }

  state.renderedPages = pages;
  await renderAllPages(state);
  status.hidden = true;
}

function bindToolbar(viewer, state) {
  viewer.querySelectorAll('[data-pdf-action]').forEach((button) => {
    button.addEventListener('click', async () => {
      const action = button.dataset.pdfAction;

      if (action === 'zoom-in') {
        state.scale = Math.min(MAX_SCALE, roundScale(state.scale + SCALE_STEP));
        await renderAllPages(state);
        return;
      }

      if (action === 'zoom-out') {
        state.scale = Math.max(MIN_SCALE, roundScale(state.scale - SCALE_STEP));
        await renderAllPages(state);
        return;
      }

      if (action === 'reset') {
        state.scale = DEFAULT_SCALE;
        await renderAllPages(state);
        return;
      }

      if (action === 'hand-tool') {
        state.handToolActive = !state.handToolActive;
        button.setAttribute('aria-pressed', state.handToolActive ? 'true' : 'false');
        button.classList.toggle('is-active', state.handToolActive);
        viewer.dataset.handTool = state.handToolActive ? 'true' : 'false';
      }
    });
  });
}

function bindHandTool(stage, handToolButton, state) {
  stage.addEventListener('pointerdown', (event) => {
    if (!state.handToolActive || state.scale <= 1) {
      return;
    }

    state.dragActive = true;
    state.dragStartX = event.clientX;
    state.dragStartY = event.clientY;
    state.scrollLeft = stage.scrollLeft;
    state.scrollTop = stage.scrollTop;
    stage.classList.add('is-dragging');
    stage.setPointerCapture(event.pointerId);
    event.preventDefault();
  });

  stage.addEventListener('pointermove', (event) => {
    if (!state.dragActive) {
      return;
    }

    const deltaX = event.clientX - state.dragStartX;
    const deltaY = event.clientY - state.dragStartY;
    stage.scrollLeft = state.scrollLeft - deltaX;
    stage.scrollTop = state.scrollTop - deltaY;
  });

  const endDrag = (event) => {
    if (!state.dragActive) {
      return;
    }

    state.dragActive = false;
    stage.classList.remove('is-dragging');
    if (event?.pointerId !== undefined) {
      stage.releasePointerCapture(event.pointerId);
    }
  };

  stage.addEventListener('pointerup', endDrag);
  stage.addEventListener('pointercancel', endDrag);
  stage.addEventListener('mouseleave', () => {
    if (!state.dragActive) {
      return;
    }

    state.dragActive = false;
    stage.classList.remove('is-dragging');
  });

  if (handToolButton) {
    handToolButton.setAttribute('aria-pressed', 'false');
  }
}

async function renderAllPages(state) {
  const viewportScale = state.scale * window.devicePixelRatio;

  for (const pageData of state.renderedPages) {
    const viewport = pageData.page.getViewport({ scale: viewportScale });
    const displayViewport = pageData.page.getViewport({ scale: state.scale });
    const canvas = pageData.canvas;
    const context = canvas.getContext('2d');

    canvas.width = Math.floor(viewport.width);
    canvas.height = Math.floor(viewport.height);
    canvas.style.width = `${displayViewport.width}px`;
    canvas.style.height = `${displayViewport.height}px`;

    await pageData.page.render({
      canvasContext: context,
      viewport,
    }).promise;
  }
}

function roundScale(value) {
  return Math.round(value * 100) / 100;
}

function showViewerError(viewer) {
  const status = viewer.querySelector('[data-pdf-status]');
  if (status) {
    status.hidden = false;
    status.textContent = window.yiariPdfViewer?.strings?.loadError || 'PDF failed to load.';
  }

  viewer.classList.add('is-error');
}
