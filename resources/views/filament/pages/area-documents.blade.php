<x-filament-panels::page>
    <livewire:area-documents :area="$area->id" />

    @push('scripts')
<script src="{{ asset('js/pdf.min.js') }}"></script>
<script>
    // Set the workerSrc ONCE at the top
    pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset('js/pdf.worker.min.js') }}';

    async function renderPdfPreview(documentId, filePath, retryCount = 0) {
        const canvas = document.getElementById('pdf-preview-' + documentId);
        const loading = document.getElementById('loading-' + documentId);

        if (!canvas || !loading) return;

        try {
            loading.style.display = 'flex';
            const context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);

            const loadingTask = pdfjsLib.getDocument(filePath);
            const pdf = await Promise.race([
                loadingTask.promise,
                new Promise((_, reject) => setTimeout(() => reject(new Error('Timeout')), 8000))
            ]);

            const page = await pdf.getPage(1);
            const scale = 1.5;
            const viewport = page.getViewport({ scale });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: context,
                viewport: viewport,
                enableWebGL: true,
                renderInteractiveForms: false
            };

            await page.render(renderContext).promise;
            loading.style.display = 'none';
            sessionStorage.setItem(`pdf-preview-${documentId}`, 'rendered');
        } catch (error) {
            console.error('Error rendering PDF:', error);

            if (retryCount < 5) {
                setTimeout(() => {
                    renderPdfPreview(documentId, filePath, retryCount + 1);
                }, 500);
            } else {
                if (loading) {
                    loading.innerHTML = `
                        <div class="flex flex-col items-center">
                            <span class="text-red-500 text-sm mb-1">Preview Failed</span>
                            <button onclick="renderPdfPreview('${documentId}', '${filePath}', 0)" 
                                    class="text-xs bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-600">
                                Retry
                            </button>
                        </div>
                    `;
                }
            }
        }
    }

    function initPdfPreviews() {
        const previews = document.querySelectorAll('[id^="pdf-preview-"]');
        console.log('initPdfPreviews running', previews); // Debug: See canvases after navigation
        previews.forEach(canvas => {
            const docId = canvas.id.replace('pdf-preview-', '');
            const loading = document.getElementById('loading-' + docId);
            const filePath = canvas.getAttribute('data-file-path');
            sessionStorage.removeItem(`pdf-preview-${docId}`);
            if (filePath && loading) {
                renderPdfPreview(docId, filePath, 0);
            }
        });
    }

    let observer = null;
    function observeDocumentGrid() {
        if (observer) observer.disconnect();
        // Adjust this selector if your grid uses a different class!
        const documentGrid = document.querySelector('.grid, .cards-wrapper');
        if (documentGrid) {
            observer = new MutationObserver((mutations) => {
                const hasRelevantChanges = mutations.some(mutation => 
                    Array.from(mutation.addedNodes).some(node => 
                        node.nodeType === 1 && (
                            node.querySelector?.('[id^="pdf-preview-"]') ||
                            node.id?.startsWith('pdf-preview-')
                        )
                    )
                );
                if (hasRelevantChanges) {
                    initPdfPreviews();
                }
            });
            observer.observe(documentGrid, {
                childList: true,
                subtree: true
            });
        }
    }

    function refreshPreviewsAndObserver() {
        initPdfPreviews();
        observeDocumentGrid();
    }

    document.addEventListener('livewire:load', () => {
        initPdfPreviews();
        observeDocumentGrid();

        Livewire.hook('message.processed', () => {
            setTimeout(() => {
                initPdfPreviews();
                observeDocumentGrid();
            }, 200); // You can adjust this delay if needed
        });
    });

    window.addEventListener('document-uploaded', refreshPreviewsAndObserver);

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            initPdfPreviews();
        }
    });

    window.addEventListener('load', () => {
        initPdfPreviews();
    });
</script>
    @endpush
</x-filament-panels::page>