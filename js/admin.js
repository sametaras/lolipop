/**
 * Admin panel JavaScript for product gallery functionality
 */
jQuery(document).ready(function($) {
    
    // Gallery management
    var galleryFrame;
    
    // Add gallery images button click
    $(document).on('click', '#add-gallery-images', function(e) {
        e.preventDefault();
        
        // If the frame already exists, re-open it
        if (galleryFrame) {
            galleryFrame.open();
            return;
        }
        
        // Create new frame
        galleryFrame = wp.media({
            title: 'Galeri Resimleri Seç',
            button: {
                text: 'Resimleri Ekle'
            },
            multiple: true,
            library: {
                type: 'image'
            }
        });
        
        // When images are selected
        galleryFrame.on('select', function() {
            var attachments = galleryFrame.state().get('selection').toJSON();
            
            attachments.forEach(function(attachment) {
                addGalleryImage(attachment);
            });
        });
        
        galleryFrame.open();
    });
    
    // Add single gallery image to the list
    function addGalleryImage(attachment) {
        var imageHtml = '<div class="gallery-image-item" data-id="' + attachment.id + '">' +
            '<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" alt="' + attachment.alt + '" style="width: 100px; height: 100px; object-fit: cover;">' +
            '<input type="hidden" name="product_gallery_images[]" value="' + attachment.id + '">' +
            '<button type="button" class="remove-gallery-image button" title="Kaldır">×</button>' +
            '<div class="image-handle" title="Sürükleyerek sıralayın">⋮⋮</div>' +
            '</div>';
        
        $('#gallery-images-list').append(imageHtml);
        
        // Re-initialize sortable after adding new item
        initializeSortable();
    }
    
    // Remove gallery image
    $(document).on('click', '.remove-gallery-image', function(e) {
        e.preventDefault();
        
        if (confirm('Bu resmi galeri listesinden kaldırmak istediğinizden emin misiniz?')) {
            $(this).closest('.gallery-image-item').fadeOut(300, function() {
                $(this).remove();
            });
        }
    });
    
    // Initialize sortable functionality
    function initializeSortable() {
        $('#gallery-images-list').sortable({
            handle: '.image-handle',
            placeholder: 'gallery-image-placeholder',
            tolerance: 'pointer',
            cursor: 'move',
            opacity: 0.8,
            start: function(e, ui) {
                ui.placeholder.height(ui.item.height());
                ui.placeholder.width(ui.item.width());
            },
            stop: function(e, ui) {
                // Optional: You can add callback here when sorting is complete
            }
        });
    }
    
    // Initialize on page load
    initializeSortable();
    
    // Add placeholder styles
    if (!$('#gallery-placeholder-styles').length) {
        $('head').append('<style id="gallery-placeholder-styles">' +
            '.gallery-image-placeholder {' +
                'border: 2px dashed #ccc !important;' +
                'background: #f9f9f9 !important;' +
                'margin: 5px;' +
            '}' +
            '</style>');
    }
    
    // Auto-save functionality (optional)
    var autoSaveTimeout;
    
    $(document).on('change', 'input[name="product_gallery_images[]"]', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // Could implement auto-save here
            console.log('Gallery order changed');
        }, 2000);
    });
    
    // Image preview on hover
    $(document).on('mouseenter', '.gallery-image-item img', function() {
        var $this = $(this);
        var fullSize = $this.data('full-url') || $this.attr('src');
        
        // Create preview tooltip (optional enhancement)
        if (!$this.data('preview-added')) {
            $this.attr('title', 'Tam boyut için tıklayın');
            $this.data('preview-added', true);
        }
    });
    
    // Click to view full size image
    $(document).on('click', '.gallery-image-item img', function() {
        var $this = $(this);
        var attachmentId = $this.closest('.gallery-image-item').data('id');
        
        if (attachmentId) {
            // Open in media library for editing
            var frame = wp.media({
                title: 'Resmi Düzenle',
                library: {
                    type: 'image'
                },
                multiple: false
            });
            
            frame.on('open', function() {
                frame.state().set('selection', wp.media.attachment(attachmentId));
            });
            
            frame.open();
        }
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + G to add gallery images
        if ((e.ctrlKey || e.metaKey) && e.key === 'g') {
            if ($('#add-gallery-images').length) {
                e.preventDefault();
                $('#add-gallery-images').click();
            }
        }
    });
    
    // Drag and drop from media library (enhancement)
    $('#gallery-images-list').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('drag-over');
    });
    
    $('#gallery-images-list').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
    });
    
    $('#gallery-images-list').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
        
        // Handle file drops here if needed
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            console.log('Files dropped:', files.length);
            // Could implement file upload here
        }
    });
    
    // Add visual feedback
    $('head').append('<style>' +
        '.gallery-image-item {' +
            'transition: all 0.3s ease;' +
        '}' +
        '.gallery-image-item:hover {' +
            'transform: scale(1.02);' +
            'box-shadow: 0 4px 8px rgba(0,0,0,0.1);' +
        '}' +
        '.gallery-image-item.ui-sortable-helper {' +
            'transform: rotate(5deg) scale(1.05);' +
            'box-shadow: 0 8px 16px rgba(0,0,0,0.2);' +
            'z-index: 1000;' +
        '}' +
        '#gallery-images-list.drag-over {' +
            'background-color: #e3f2fd;' +
            'border: 2px dashed #2196f3;' +
        '}' +
        '.remove-gallery-image:hover {' +
            'background-color: #d32f2f !important;' +
            'transform: scale(1.1);' +
        '}' +
        '</style>');
    
    // Success message after save
    $(window).on('beforeunload', function() {
        if ($('.gallery-image-item').length > 0) {
            // Optional: Show warning if there are unsaved changes
        }
    });
    
    // Help tooltips
    if ($('.product-gallery-meta').length) {
        $('.product-gallery-meta').prepend(
            '<div class="gallery-help" style="background: #fff3cd; padding: 10px; margin-bottom: 15px; border-left: 4px solid #ffc107; border-radius: 4px;">' +
                '<strong>💡 İpucu:</strong> ' +
                '<ul style="margin: 5px 0 0 20px;">' +
                    '<li>Birden fazla resim seçmek için Ctrl/Cmd tuşuna basılı tutun</li>' +
                    '<li>Resimleri sürükleyerek sıralayabilirsiniz</li>' +
                    '<li>Öne çıkan görsel sadece ana sayfada görünür</li>' +
                    '<li>Galeri resimleri ürün sayfasında carousel olarak gösterilir</li>' +
                '</ul>' +
            '</div>'
        );
    }
});