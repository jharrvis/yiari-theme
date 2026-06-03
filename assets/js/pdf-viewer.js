const pdfjsLib = window.pdfjsLib || null;

if (pdfjsLib && pdfjsLib.GlobalWorkerOptions) {
  pdfjsLib.GlobalWorkerOptions.workerSrc = window.yiariPdfViewer?.workerSrc || '';
}

const DEFAULT_SCALE = 1;
const MIN_SCALE = 0.7;
const MAX_SCALE = 2.8;
const SCALE_STEP = 0.2;
const PAGE_FRAME_PADDING = 72;

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-pdf-viewer]').forEach((viewer) => {
    if (!pdfjsLib) {
      showViewerError(viewer);
      return;
    }

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

  if (!pdfUrl || !stage || !canvasList || !status) {
    throw new Error('Missing PDF viewer elements');
  }

  const state = {
    pdfDocument: null,
    renderedPages: [],
    scale: DEFAULT_SCALE,
    fitScale: DEFAULT_SCALE,
    handToolActive: false,
    dragActive: false,
    dragStartX: 0,
    dragStartY: 0,
    scrollLeft: 0,
    scrollTop: 0,
    resizeFrame: null,
  };

  syncHandToolState(viewer, state);
  bindToolbar(viewer, state);
  bindHandTool(viewer, stage, state);

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
  updateFitScale(viewer, stage, state);
  state.scale = state.fitScale;
  bindResize(viewer, stage, state);
  await renderAllPages(state);
  status.hidden = true;
}

function bindToolbar(viewer, state) {
  viewer.querySelectorAll('[data-pdf-action]').forEach((button) => {
    button.addEventListener('click', async () => {
      const action = button.dataset.pdfAction;

      if (action === 'zoom-in') {
        state.scale = Math.min(MAX_SCALE, roundScale(state.scale + SCALE_STEP));
        syncHandToolState(viewer, state);
        await renderAllPages(state);
        return;
      }

      if (action === 'zoom-out') {
        state.scale = Math.max(MIN_SCALE, roundScale(state.scale - SCALE_STEP));
        syncHandToolState(viewer, state);
        await renderAllPages(state);
        return;
      }

      if (action === 'reset') {
        const stage = viewer.querySelector('[data-pdf-stage]');
        updateFitScale(viewer, stage, state);
        state.scale = state.fitScale;
        syncHandToolState(viewer, state);
        await renderAllPages(state);
        return;
      }
    });
  });
}

function bindResize(viewer, stage, state) {
  let resizeTimer = null;

  const handleResize = async () => {
    window.clearTimeout(resizeTimer);
    resizeTimer = window.setTimeout(async () => {
      const previousFitScale = state.fitScale;
      updateFitScale(viewer, stage, state);

      if (Math.abs(state.scale - previousFitScale) < 0.05 || state.scale < state.fitScale) {
        state.scale = state.fitScale;
        syncHandToolState(viewer, state);
        await renderAllPages(state);
      }
    }, 120);
  };

  state.resizeFrame = handleResize;
  window.addEventListener('resize', handleResize, { passive: true });
}

function bindHandTool(viewer, stage, state) {
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
    viewer.classList.add('is-hand-tool-active');
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
    viewer.classList.remove('is-hand-tool-active');
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
    viewer.classList.remove('is-hand-tool-active');
  });
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

function updateFitScale(viewer, stage, state) {
  const firstPage = state.renderedPages[0]?.page || null;
  if (!firstPage || !stage) {
    state.fitScale = DEFAULT_SCALE;
    return;
  }

  const baseViewport = firstPage.getViewport({ scale: 1 });
  const availableWidth = Math.max(240, stage.clientWidth - PAGE_FRAME_PADDING);
  const fitScale = availableWidth / Math.max(baseViewport.width, 1);

  state.fitScale = roundScale(Math.min(MAX_SCALE, Math.max(MIN_SCALE, fitScale)));
  viewer.dataset.fitScale = String(state.fitScale);
}

function syncHandToolState(viewer, state) {
  state.handToolActive = state.scale > 1;
  viewer.dataset.handTool = state.handToolActive ? 'true' : 'false';
}

function showViewerError(viewer) {
  const status = viewer.querySelector('[data-pdf-status]');
  if (status) {
    status.hidden = false;
    status.textContent = window.yiariPdfViewer?.strings?.loadError || 'PDF failed to load.';
  }

  viewer.classList.add('is-error');
}
