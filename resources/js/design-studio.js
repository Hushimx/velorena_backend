// Design Studio JavaScript - Canva-like Photo Editor
class DesignStudio {
    constructor() {
        this.canvas = null;
        this.ctx = null;
        this.currentTool = 'select';
        this.elements = [];
        this.selectedElement = null;
        this.isDragging = false;
        this.dragStart = { x: 0, y: 0 };
        this.zoom = 1;
        this.offset = { x: 0, y: 0 };
        this.history = [];
        this.historyStep = -1;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initCanvas();
    }

    setupEventListeners() {
        // Wait a bit for DOM to be ready, then attach listeners directly
        setTimeout(() => {
            console.log('Setting up Design Studio event listeners...');
            
            // Tool selection
            document.querySelectorAll('.tool-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    console.log('Tool clicked:', e.target.dataset.tool);
                    this.selectTool(e.target.dataset.tool);
                });
            });

            // Quick actions
            document.querySelectorAll('.quick-action-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    console.log('Quick action clicked:', btn.textContent);
                    this.handleQuickAction(e.target.closest('.quick-action-btn'));
                });
            });

            // Canvas actions
            document.querySelectorAll('.canvas-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    console.log('Canvas action clicked:', e.target.dataset.action);
                    this.handleCanvasAction(e.target.dataset.action);
                });
            });

            // Zoom controls
            document.querySelectorAll('.zoom-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    console.log('Zoom clicked:', e.target.dataset.zoom);
                    this.handleZoom(e.target.dataset.zoom);
                });
            });

            // Color palette
            document.querySelectorAll('.color-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    console.log('Color clicked:', e.target.dataset.color);
                    this.selectColor(e.target.dataset.color);
                });
            });

            // Logo upload
            const logoInput = document.querySelector('.logo-input');
            if (logoInput) {
                logoInput.addEventListener('change', (e) => {
                    console.log('Logo file selected');
                    this.handleLogoUpload(e.target.files[0]);
                });
            }

            // Upload area click
            const uploadArea = document.querySelector('.upload-area');
            if (uploadArea) {
                uploadArea.addEventListener('click', () => {
                    console.log('Upload area clicked');
                    logoInput?.click();
                });
            }

            // Property controls
            const opacitySlider = document.querySelector('.property-slider');
            if (opacitySlider) {
                opacitySlider.addEventListener('input', (e) => {
                    this.updateProperty('opacity', e.target.value);
                });
            }

            // Size controls
            document.querySelectorAll('.size-input').forEach(input => {
                input.addEventListener('change', (e) => {
                    this.updateSize(e.target);
                });
            });

            // Header actions
            const saveBtn = document.querySelector('.save-btn');
            if (saveBtn) {
                saveBtn.addEventListener('click', () => {
                    console.log('Save button clicked');
                    this.saveDesign();
                });
            }

            const downloadBtn = document.querySelector('.download-btn');
            if (downloadBtn) {
                downloadBtn.addEventListener('click', () => {
                    console.log('Download button clicked');
                    this.downloadDesign();
                });
            }

            console.log('Event listeners attached. Found elements:', {
                toolBtns: document.querySelectorAll('.tool-btn').length,
                quickActionBtns: document.querySelectorAll('.quick-action-btn').length,
                canvasBtns: document.querySelectorAll('.canvas-btn').length,
                saveBtn: !!document.querySelector('.save-btn'),
                downloadBtn: !!document.querySelector('.download-btn')
            });
        }, 500);
    }

    initCanvas() {
        setTimeout(() => {
            console.log('Initializing canvas...');
            const canvasElement = document.getElementById('designCanvas');
            
            if (!canvasElement) {
                console.error('Design canvas element not found!');
                return;
            }

            console.log('Canvas element found:', canvasElement);

            // Create overlay canvas for editing
            const overlay = document.createElement('canvas');
            overlay.id = 'editCanvas';
            overlay.style.position = 'absolute';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.pointerEvents = 'auto';
            overlay.style.zIndex = '10';
            
            canvasElement.appendChild(overlay);
            
            this.canvas = overlay;
            this.ctx = overlay.getContext('2d');
            
            console.log('Canvas initialized:', { width: overlay.width, height: overlay.height });
            
            this.resizeCanvas();
            this.setupCanvasEvents();
            
            // Save initial state
            this.saveState();
        }, 300);
    }

    resizeCanvas() {
        if (!this.canvas) return;
        
        const container = this.canvas.parentElement;
        this.canvas.width = container.clientWidth;
        this.canvas.height = container.clientHeight;
        
        this.redraw();
    }

    setupCanvasEvents() {
        if (!this.canvas) return;

        this.canvas.addEventListener('mousedown', (e) => {
            this.handleMouseDown(e);
        });

        this.canvas.addEventListener('mousemove', (e) => {
            this.handleMouseMove(e);
        });

        this.canvas.addEventListener('mouseup', (e) => {
            this.handleMouseUp(e);
        });

        this.canvas.addEventListener('wheel', (e) => {
            e.preventDefault();
            this.handleWheel(e);
        });

        // Touch events for mobile
        this.canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent('mousedown', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            this.canvas.dispatchEvent(mouseEvent);
        });

        this.canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            const mouseEvent = new MouseEvent('mousemove', {
                clientX: touch.clientX,
                clientY: touch.clientY
            });
            this.canvas.dispatchEvent(mouseEvent);
        });

        this.canvas.addEventListener('touchend', (e) => {
            e.preventDefault();
            const mouseEvent = new MouseEvent('mouseup', {});
            this.canvas.dispatchEvent(mouseEvent);
        });
    }

    getMousePos(e) {
        const rect = this.canvas.getBoundingClientRect();
        return {
            x: (e.clientX - rect.left) / this.zoom - this.offset.x,
            y: (e.clientY - rect.top) / this.zoom - this.offset.y
        };
    }

    handleMouseDown(e) {
        const pos = this.getMousePos(e);
        
        if (this.currentTool === 'select') {
            this.selectedElement = this.getElementAt(pos.x, pos.y);
            if (this.selectedElement) {
                this.isDragging = true;
                this.dragStart = { x: pos.x, y: pos.y };
            }
        } else if (this.currentTool === 'text') {
            this.addText(pos.x, pos.y);
        } else if (this.currentTool === 'shapes') {
            this.addShape(pos.x, pos.y);
        }
    }

    handleMouseMove(e) {
        const pos = this.getMousePos(e);
        
        if (this.isDragging && this.selectedElement) {
            const dx = pos.x - this.dragStart.x;
            const dy = pos.y - this.dragStart.y;
            
            this.selectedElement.x += dx;
            this.selectedElement.y += dy;
            
            this.dragStart = { x: pos.x, y: pos.y };
            this.redraw();
        }
    }

    handleMouseUp(e) {
        if (this.isDragging) {
            this.isDragging = false;
            this.saveState();
        }
    }

    handleWheel(e) {
        const zoomFactor = 0.1;
        const direction = e.deltaY > 0 ? -1 : 1;
        
        this.zoom += direction * zoomFactor;
        this.zoom = Math.max(0.1, Math.min(5, this.zoom));
        
        this.updateZoomDisplay();
        this.redraw();
    }

    selectTool(tool) {
        this.currentTool = tool;
        
        // Update UI
        document.querySelectorAll('.tool-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        document.querySelector(`[data-tool="${tool}"]`)?.classList.add('active');
        
        // Update cursor
        this.canvas.style.cursor = this.getCursorForTool(tool);
    }

    getCursorForTool(tool) {
        const cursors = {
            'select': 'default',
            'text': 'text',
            'shapes': 'crosshair',
            'logo': 'copy',
            'images': 'copy',
            'filters': 'pointer'
        };
        return cursors[tool] || 'default';
    }

    addText(x, y) {
        const text = prompt('أدخل النص:') || prompt('Enter text:');
        if (!text) return;
        
        const textElement = {
            type: 'text',
            x: x,
            y: y,
            text: text,
            fontSize: 32,
            fontFamily: 'Cairo, Arial',
            color: '#2a1e1e', // Brand brown
            opacity: 1,
            rotation: 0
        };
        
        this.elements.push(textElement);
        this.selectedElement = textElement;
        this.redraw();
        this.saveState();
        this.updateLayersList();
    }

    addShape(x, y) {
        // Simplified to just rectangle and circle
        const shapes = ['rectangle', 'circle'];
        const shapeType = shapes[Math.floor(Math.random() * shapes.length)];
        
        const shapeElement = {
            type: 'shape',
            shapeType: shapeType,
            x: x,
            y: y,
            width: 120,
            height: 120,
            color: '#ffde9f', // Brand yellow
            opacity: 0.8,
            rotation: 0
        };
        
        this.elements.push(shapeElement);
        this.selectedElement = shapeElement;
        this.redraw();
        this.saveState();
        this.updateLayersList();
    }

    addLogo(imageData, x = 100, y = 100) {
        const logoElement = {
            type: 'logo',
            x: x,
            y: y,
            width: 150,
            height: 150,
            imageData: imageData,
            opacity: 1,
            rotation: 0
        };
        
        this.elements.push(logoElement);
        this.selectedElement = logoElement;
        this.redraw();
        this.saveState();
        this.updateLayersList();
    }

    handleLogoUpload(file) {
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = new Image();
            img.onload = () => {
                this.addLogo(img);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    selectColor(colorName) {
        if (!this.selectedElement) return;
        
        const colors = {
            'brand-yellow': '#ffde9f',
            'brand-brown': '#2a1e1e',
            'white': '#ffffff',
            'black': '#000000',
            'red': '#ff6b6b',
            'teal': '#4ecdc4'
        };
        
        this.selectedElement.color = colors[colorName] || colorName;
        this.redraw();
        this.saveState();
    }

    updateProperty(property, value) {
        if (!this.selectedElement) return;
        
        this.selectedElement[property] = property === 'opacity' ? value / 100 : value;
        this.redraw();
        
        // Update display
        if (property === 'opacity') {
            document.querySelector('.property-value').textContent = value + '%';
        }
    }

    updateSize(input) {
        if (!this.selectedElement) return;
        
        const property = input.placeholder === 'W' ? 'width' : 'height';
        this.selectedElement[property] = parseInt(input.value) || 100;
        this.redraw();
    }

    getElementAt(x, y) {
        // Check elements in reverse order (top to bottom)
        for (let i = this.elements.length - 1; i >= 0; i--) {
            const element = this.elements[i];
            if (this.isPointInElement(x, y, element)) {
                return element;
            }
        }
        return null;
    }

    isPointInElement(x, y, element) {
        return x >= element.x && 
               x <= element.x + (element.width || 100) &&
               y >= element.y && 
               y <= element.y + (element.height || 30);
    }

    redraw() {
        if (!this.ctx) return;
        
        // Clear canvas
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Apply zoom and offset
        this.ctx.save();
        this.ctx.scale(this.zoom, this.zoom);
        this.ctx.translate(this.offset.x, this.offset.y);
        
        // Draw all elements
        this.elements.forEach(element => {
            this.drawElement(element);
        });
        
        // Draw selection outline
        if (this.selectedElement) {
            this.drawSelection(this.selectedElement);
        }
        
        this.ctx.restore();
    }

    drawElement(element) {
        this.ctx.save();
        
        // Apply element transformations
        this.ctx.globalAlpha = element.opacity || 1;
        this.ctx.translate(element.x, element.y);
        if (element.rotation) {
            this.ctx.rotate(element.rotation * Math.PI / 180);
        }
        
        switch (element.type) {
            case 'text':
                this.drawText(element);
                break;
            case 'shape':
                this.drawShape(element);
                break;
            case 'logo':
                this.drawLogo(element);
                break;
        }
        
        this.ctx.restore();
    }

    drawText(element) {
        this.ctx.fillStyle = element.color || '#000000';
        this.ctx.font = `${element.fontSize || 24}px ${element.fontFamily || 'Arial'}`;
        this.ctx.fillText(element.text, 0, 0);
    }

    drawShape(element) {
        this.ctx.fillStyle = element.color || '#ffde9f';
        
        switch (element.shapeType) {
            case 'rectangle':
                this.ctx.fillRect(0, 0, element.width, element.height);
                break;
            case 'circle':
                this.ctx.beginPath();
                this.ctx.arc(element.width/2, element.height/2, Math.min(element.width, element.height)/2, 0, Math.PI * 2);
                this.ctx.fill();
                break;
            case 'triangle':
                this.ctx.beginPath();
                this.ctx.moveTo(element.width/2, 0);
                this.ctx.lineTo(0, element.height);
                this.ctx.lineTo(element.width, element.height);
                this.ctx.closePath();
                this.ctx.fill();
                break;
        }
    }

    drawLogo(element) {
        if (element.imageData) {
            this.ctx.drawImage(element.imageData, 0, 0, element.width, element.height);
        }
    }

    drawSelection(element) {
        this.ctx.save();
        this.ctx.strokeStyle = '#007bff';
        this.ctx.lineWidth = 2 / this.zoom;
        this.ctx.setLineDash([5 / this.zoom, 5 / this.zoom]);
        this.ctx.strokeRect(element.x - 5, element.y - 5, (element.width || 100) + 10, (element.height || 30) + 10);
        this.ctx.restore();
    }

    handleQuickAction(btn) {
        const action = btn.querySelector('span').textContent;
        
        switch (action) {
            case 'Add Text':
                this.selectTool('text');
                this.addText(this.canvas.width / 2, this.canvas.height / 2);
                break;
            case 'Add Logo':
                document.querySelector('.logo-input')?.click();
                break;
            case 'Add Shape':
                this.selectTool('shapes');
                this.addShape(this.canvas.width / 2, this.canvas.height / 2);
                break;
            case 'Change Colors':
                this.showColorPicker();
                break;
        }
    }

    handleCanvasAction(action) {
        switch (action) {
            case 'undo':
                this.undo();
                break;
            case 'redo':
                this.redo();
                break;
            case 'reset':
                this.reset();
                break;
        }
    }

    handleZoom(direction) {
        const zoomStep = 0.2;
        if (direction === 'in') {
            this.zoom = Math.min(5, this.zoom + zoomStep);
        } else {
            this.zoom = Math.max(0.1, this.zoom - zoomStep);
        }
        
        this.updateZoomDisplay();
        this.redraw();
    }

    updateZoomDisplay() {
        const zoomLevel = document.querySelector('.zoom-level');
        if (zoomLevel) {
            zoomLevel.textContent = Math.round(this.zoom * 100) + '%';
        }
    }

    saveState() {
        this.historyStep++;
        this.history = this.history.slice(0, this.historyStep);
        this.history.push(JSON.parse(JSON.stringify(this.elements)));
    }

    undo() {
        if (this.historyStep > 0) {
            this.historyStep--;
            this.elements = JSON.parse(JSON.stringify(this.history[this.historyStep]));
            this.redraw();
            this.updateLayersList();
        }
    }

    redo() {
        if (this.historyStep < this.history.length - 1) {
            this.historyStep++;
            this.elements = JSON.parse(JSON.stringify(this.history[this.historyStep]));
            this.redraw();
            this.updateLayersList();
        }
    }

    reset() {
        if (confirm('Are you sure you want to reset the design?')) {
            this.elements = [];
            this.selectedElement = null;
            this.redraw();
            this.saveState();
            this.updateLayersList();
        }
    }

    updateLayersList() {
        const layersList = document.querySelector('.layers-list');
        if (!layersList) return;
        
        layersList.innerHTML = '';
        
        // Add background layer
        const bgLayer = document.createElement('div');
        bgLayer.className = 'layer-item';
        bgLayer.innerHTML = `
            <i class="fas fa-image"></i>
            <span>Background</span>
            <div class="layer-actions">
                <button class="layer-action"><i class="fas fa-eye"></i></button>
                <button class="layer-action"><i class="fas fa-lock"></i></button>
            </div>
        `;
        layersList.appendChild(bgLayer);
        
        // Add element layers
        this.elements.forEach((element, index) => {
            const layer = document.createElement('div');
            layer.className = 'layer-item';
            if (element === this.selectedElement) {
                layer.classList.add('active');
            }
            
            const icon = this.getIconForElement(element);
            const name = this.getNameForElement(element, index);
            
            layer.innerHTML = `
                <i class="fas fa-${icon}"></i>
                <span>${name}</span>
                <div class="layer-actions">
                    <button class="layer-action" onclick="designStudio.toggleElementVisibility(${index})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="layer-action" onclick="designStudio.deleteElement(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            layer.addEventListener('click', () => {
                this.selectedElement = element;
                this.redraw();
                this.updateLayersList();
            });
            
            layersList.appendChild(layer);
        });
    }

    getIconForElement(element) {
        const icons = {
            'text': 'font',
            'shape': 'shapes',
            'logo': 'crown',
            'image': 'image'
        };
        return icons[element.type] || 'square';
    }

    getNameForElement(element, index) {
        const names = {
            'text': `Text: ${element.text?.substring(0, 10) || 'Empty'}`,
            'shape': `Shape: ${element.shapeType || 'Rectangle'}`,
            'logo': 'Logo',
            'image': 'Image'
        };
        return names[element.type] || `Element ${index + 1}`;
    }

    toggleElementVisibility(index) {
        const element = this.elements[index];
        if (element) {
            element.visible = !element.visible;
            this.redraw();
        }
    }

    deleteElement(index) {
        if (confirm('Delete this element?')) {
            this.elements.splice(index, 1);
            this.selectedElement = null;
            this.redraw();
            this.saveState();
            this.updateLayersList();
        }
    }

    saveDesign() {
        // Create a temporary canvas for export
        const exportCanvas = document.createElement('canvas');
        const exportCtx = exportCanvas.getContext('2d');
        
        // Set canvas size to match the background image
        const bgImage = document.querySelector('.canvas-image');
        if (bgImage) {
            exportCanvas.width = bgImage.naturalWidth || 800;
            exportCanvas.height = bgImage.naturalHeight || 600;
            
            // Draw background image
            exportCtx.drawImage(bgImage, 0, 0, exportCanvas.width, exportCanvas.height);
        } else {
            exportCanvas.width = 800;
            exportCanvas.height = 600;
            exportCtx.fillStyle = '#ffffff';
            exportCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
        }
        
        // Draw all elements
        this.elements.forEach(element => {
            exportCtx.save();
            exportCtx.globalAlpha = element.opacity || 1;
            exportCtx.translate(element.x, element.y);
            if (element.rotation) {
                exportCtx.rotate(element.rotation * Math.PI / 180);
            }
            
            switch (element.type) {
                case 'text':
                    exportCtx.fillStyle = element.color || '#000000';
                    exportCtx.font = `${element.fontSize || 24}px ${element.fontFamily || 'Arial'}`;
                    exportCtx.fillText(element.text, 0, 0);
                    break;
                case 'shape':
                    exportCtx.fillStyle = element.color || '#ffde9f';
                    if (element.shapeType === 'rectangle') {
                        exportCtx.fillRect(0, 0, element.width, element.height);
                    } else if (element.shapeType === 'circle') {
                        exportCtx.beginPath();
                        exportCtx.arc(element.width/2, element.height/2, Math.min(element.width, element.height)/2, 0, Math.PI * 2);
                        exportCtx.fill();
                    }
                    break;
                case 'logo':
                    if (element.imageData) {
                        exportCtx.drawImage(element.imageData, 0, 0, element.width, element.height);
                    }
                    break;
            }
            
            exportCtx.restore();
        });
        
        // Save to database via Livewire
        const designData = {
            elements: this.elements,
            backgroundImage: bgImage ? bgImage.src : null,
            canvasSize: { width: exportCanvas.width, height: exportCanvas.height }
        };
        
        const imageUrl = exportCanvas.toDataURL();
        const title = prompt('اسم التصميم:') || prompt('Design name:') || 'My Design';
        
        // Emit Livewire event to save
        window.dispatchEvent(new CustomEvent('save-cart-design', {
            detail: {
                title: title,
                designData: designData,
                imageUrl: imageUrl
            }
        }));
        
        alert('تم حفظ التصميم بنجاح!');
    }

    downloadDesign() {
        // Create export canvas similar to save
        const exportCanvas = document.createElement('canvas');
        const exportCtx = exportCanvas.getContext('2d');
        
        // Set canvas size
        const bgImage = document.querySelector('.canvas-image');
        if (bgImage) {
            exportCanvas.width = bgImage.naturalWidth || 800;
            exportCanvas.height = bgImage.naturalHeight || 600;
            exportCtx.drawImage(bgImage, 0, 0, exportCanvas.width, exportCanvas.height);
        } else {
            exportCanvas.width = 800;
            exportCanvas.height = 600;
            exportCtx.fillStyle = '#ffffff';
            exportCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
        }
        
        // Draw all elements (same as save method)
        this.elements.forEach(element => {
            exportCtx.save();
            exportCtx.globalAlpha = element.opacity || 1;
            exportCtx.translate(element.x, element.y);
            if (element.rotation) {
                exportCtx.rotate(element.rotation * Math.PI / 180);
            }
            
            switch (element.type) {
                case 'text':
                    exportCtx.fillStyle = element.color || '#000000';
                    exportCtx.font = `${element.fontSize || 24}px ${element.fontFamily || 'Arial'}`;
                    exportCtx.fillText(element.text, 0, 0);
                    break;
                case 'shape':
                    exportCtx.fillStyle = element.color || '#ffde9f';
                    if (element.shapeType === 'rectangle') {
                        exportCtx.fillRect(0, 0, element.width, element.height);
                    } else if (element.shapeType === 'circle') {
                        exportCtx.beginPath();
                        exportCtx.arc(element.width/2, element.height/2, Math.min(element.width, element.height)/2, 0, Math.PI * 2);
                        exportCtx.fill();
                    }
                    break;
                case 'logo':
                    if (element.imageData) {
                        exportCtx.drawImage(element.imageData, 0, 0, element.width, element.height);
                    }
                    break;
            }
            
            exportCtx.restore();
        });
        
        // Trigger download
        const link = document.createElement('a');
        link.download = 'design-studio-creation.png';
        link.href = exportCanvas.toDataURL();
        link.click();
    }

    showColorPicker() {
        alert('Color picker functionality would be implemented here. For now, use the color palette in the Properties panel.');
    }
}

// Initialize Design Studio when page loads
let designStudio;

function initializeDesignStudio() {
    // Only initialize if we're in a design studio modal and not already initialized
    if (document.querySelector('.design-studio-modal') && !designStudio) {
        console.log('Initializing Design Studio...');
        designStudio = new DesignStudio();
        
        // Make it globally accessible for layer actions
        window.designStudio = designStudio;
    }
}

document.addEventListener('DOMContentLoaded', initializeDesignStudio);

// Re-initialize when modal opens (for Livewire)
document.addEventListener('livewire:init', () => {
    // Listen for when modals open
    window.addEventListener('design-modal-opened', () => {
        setTimeout(initializeDesignStudio, 100);
    });
    
    // Also check after Livewire updates
    document.addEventListener('livewire:update', () => {
        setTimeout(initializeDesignStudio, 100);
    });
});

// Listen for cart design save events
document.addEventListener('livewire:init', () => {
    window.addEventListener('save-cart-design', (event) => {
        const data = event.detail;
        console.log('Save cart design event received:', data);
        
        // Try multiple ways to find the cart component
        let cartComponent = null;
        
        if (window.Livewire && window.Livewire.all) {
            cartComponent = window.Livewire.all().find(component => 
                component.get && component.get('showCartDesignModal') !== undefined
            );
        }
        
        if (!cartComponent && window.Livewire && window.Livewire.find) {
            // Try to find by component name
            cartComponent = window.Livewire.find('shopping-cart');
        }
        
        if (cartComponent) {
            console.log('Found cart component, calling saveCartDesign');
            cartComponent.call('saveCartDesign', data);
        } else {
            console.error('Could not find ShoppingCart component');
            // Fallback: dispatch Livewire event
            window.Livewire.dispatch('save-cart-design', data);
        }
    });
});
