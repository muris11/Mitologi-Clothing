/**
 * Smart Image Cropper for Hero Slides
 * Auto-crops and resizes images to 16:9 (1920x1080)
 */
class HeroImageCropper {
    constructor() {
        this.canvas = null;
        this.ctx = null;
        this.image = null;
        this.cropData = null;
        this.targetWidth = 1920;
        this.targetHeight = 1080;
        this.aspectRatio = 16 / 9;
    }

    /**
     * Initialize cropper with uploaded file
     */
    init(file, previewContainerId, callback) {
        const reader = new FileReader();
        reader.onload = (e) => {
            this.image = new Image();
            this.image.onload = () => {
                this.setupCanvas(previewContainerId);
                this.drawImage();
                this.setupDraggableCrop();
                if (callback) callback(this.image);
            };
            this.image.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    /**
     * Setup canvas with 16:9 aspect ratio preview
     */
    setupCanvas(containerId) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        
        // Create canvas
        this.canvas = document.createElement('canvas');
        this.canvas.className = 'w-full rounded-lg shadow-lg cursor-move';
        
        // Set preview size (max 800px width)
        const maxPreviewWidth = 800;
        const scale = Math.min(maxPreviewWidth / this.image.width, 1);
        this.canvas.width = this.image.width * scale;
        this.canvas.height = this.image.height * scale;
        
        this.ctx = this.canvas.getContext('2d');
        container.appendChild(this.canvas);
        
        // Add info text
        this.addInfoOverlay(container);
    }

    /**
     * Add info overlay showing crop details
     */
    addInfoOverlay(container) {
        const info = document.createElement('div');
        info.className = 'mt-4 p-4 bg-mitologi-navy/5 rounded-lg text-sm';
        info.innerHTML = `
            <div class="flex justify-between items-center mb-2">
                <span class="font-bold text-mitologi-navy">Ukuran Asli:</span>
                <span class="text-slate-600">${this.image.width}x${this.image.height}px</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="font-bold text-mitologi-navy">Target:</span>
                <span class="text-slate-600">1920x1080px (16:9)</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-mitologi-navy">Status:</span>
                <span id="crop-status" class="text-amber-600 font-medium flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Drag area kuning untuk mengatur crop</span>
            </div>
        `;
        container.appendChild(info);
    }

    /**
     * Draw image on canvas
     */
    drawImage() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        this.ctx.drawImage(this.image, 0, 0, this.canvas.width, this.canvas.height);
        this.drawOverlay();
        this.drawCropArea();
    }

    /**
     * Draw dark overlay outside crop area
     */
    drawOverlay() {
        if (!this.cropData) return;
        
        this.ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
        
        // Top
        this.ctx.fillRect(0, 0, this.canvas.width, this.cropData.y);
        // Bottom
        this.ctx.fillRect(0, this.cropData.y + this.cropData.height, this.canvas.width, this.canvas.height);
        // Left
        this.ctx.fillRect(0, this.cropData.y, this.cropData.x, this.cropData.height);
        // Right
        this.ctx.fillRect(this.cropData.x + this.cropData.width, this.cropData.y, this.canvas.width, this.cropData.height);
    }

    /**
     * Draw crop area border
     */
    drawCropArea() {
        if (!this.cropData) return;
        
        // Draw border
        this.ctx.strokeStyle = '#FFD700';
        this.ctx.lineWidth = 3;
        this.ctx.strokeRect(this.cropData.x, this.cropData.y, this.cropData.width, this.cropData.height);
        
        // Draw corner handles
        const handleSize = 10;
        this.ctx.fillStyle = '#FFD700';
        
        // Top-left
        this.ctx.fillRect(this.cropData.x - handleSize/2, this.cropData.y - handleSize/2, handleSize, handleSize);
        // Top-right
        this.ctx.fillRect(this.cropData.x + this.cropData.width - handleSize/2, this.cropData.y - handleSize/2, handleSize, handleSize);
        // Bottom-left
        this.ctx.fillRect(this.cropData.x - handleSize/2, this.cropData.y + this.cropData.height - handleSize/2, handleSize, handleSize);
        // Bottom-right
        this.ctx.fillRect(this.cropData.x + this.cropData.width - handleSize/2, this.cropData.y + this.cropData.height - handleSize/2, handleSize, handleSize);
    }

    /**
     * Setup draggable crop area
     */
    setupDraggableCrop() {
        // Calculate initial crop area (center, 16:9 ratio)
        const imgRatio = this.image.width / this.image.height;
        let cropWidth, cropHeight;
        
        if (imgRatio > this.aspectRatio) {
            // Image is wider than 16:9, limit by height
            cropHeight = this.canvas.height * 0.8;
            cropWidth = cropHeight * this.aspectRatio;
        } else {
            // Image is taller than 16:9, limit by width
            cropWidth = this.canvas.width * 0.8;
            cropHeight = cropWidth / this.aspectRatio;
        }
        
        this.cropData = {
            x: (this.canvas.width - cropWidth) / 2,
            y: (this.canvas.height - cropHeight) / 2,
            width: cropWidth,
            height: cropHeight,
            isDragging: false,
            dragStartX: 0,
            dragStartY: 0
        };
        
        this.drawImage();
        this.setupEventListeners();
    }

    /**
     * Setup mouse/touch event listeners
     */
    setupEventListeners() {
        let isDragging = false;
        let startX, startY;
        let initialCropX, initialCropY;
        
        const onStart = (e) => {
            const pos = this.getEventPos(e);
            if (this.isInsideCrop(pos.x, pos.y)) {
                isDragging = true;
                startX = pos.x;
                startY = pos.y;
                initialCropX = this.cropData.x;
                initialCropY = this.cropData.y;
                this.canvas.style.cursor = 'grabbing';
            }
        };
        
        const onMove = (e) => {
            if (!isDragging) return;
            
            const pos = this.getEventPos(e);
            const dx = pos.x - startX;
            const dy = pos.y - startY;
            
            // Update crop position
            this.cropData.x = Math.max(0, Math.min(this.canvas.width - this.cropData.width, initialCropX + dx));
            this.cropData.y = Math.max(0, Math.min(this.canvas.height - this.cropData.height, initialCropY + dy));
            
            this.drawImage();
        };
        
        const onEnd = () => {
            isDragging = false;
            this.canvas.style.cursor = 'move';
            this.updateStatus('<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Crop area telah diatur. Klik "Simpan" untuk upload.');
        };
        
        this.canvas.addEventListener('mousedown', onStart);
        this.canvas.addEventListener('mousemove', onMove);
        this.canvas.addEventListener('mouseup', onEnd);
        this.canvas.addEventListener('mouseleave', onEnd);
        
        // Touch events
        this.canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            onStart(e.touches[0]);
        });
        this.canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            onMove(e.touches[0]);
        });
        this.canvas.addEventListener('touchend', onEnd);
    }

    /**
     * Get event position relative to canvas
     */
    getEventPos(e) {
        const rect = this.canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    /**
     * Check if point is inside crop area
     */
    isInsideCrop(x, y) {
        return x >= this.cropData.x && x <= this.cropData.x + this.cropData.width &&
               y >= this.cropData.y && y <= this.cropData.y + this.cropData.height;
    }

    /**
     * Update status text
     */
    updateStatus(html) {
        const statusEl = document.getElementById('crop-status');
        if (statusEl) statusEl.innerHTML = html;
    }

    /**
     * Get cropped image as Blob
     */
    getCroppedImage(callback) {
        if (!this.cropData || !this.image) {
            callback(null);
            return;
        }
        
        // Create output canvas
        const outputCanvas = document.createElement('canvas');
        outputCanvas.width = this.targetWidth;
        outputCanvas.height = this.targetHeight;
        const outputCtx = outputCanvas.getContext('2d');
        
        // Calculate scale factor
        const scaleX = this.image.width / this.canvas.width;
        const scaleY = this.image.height / this.canvas.height;
        
        // Source coordinates (original image)
        const srcX = this.cropData.x * scaleX;
        const srcY = this.cropData.y * scaleY;
        const srcWidth = this.cropData.width * scaleX;
        const srcHeight = this.cropData.height * scaleY;
        
        // Draw cropped image
        outputCtx.drawImage(
            this.image,
            srcX, srcY, srcWidth, srcHeight,
            0, 0, this.targetWidth, this.targetHeight
        );
        
        // Convert to blob
        outputCanvas.toBlob((blob) => {
            // Create file from blob
            const file = new File([blob], 'hero-slide-cropped.webp', { type: 'image/webp' });
            callback(file);
        }, 'image/webp', 0.85);
    }

    /**
     * Reset crop to center
     */
    reset() {
        if (this.cropData) {
            this.cropData.x = (this.canvas.width - this.cropData.width) / 2;
            this.cropData.y = (this.canvas.height - this.cropData.height) / 2;
            this.drawImage();
            this.updateStatus('<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Crop direset ke tengah. Drag untuk mengatur.');
        }
    }
}

// Export for global use
window.HeroImageCropper = HeroImageCropper;
