/**
 * Client-side image compression for faster mobile uploads.
 */
window.OsaImageCompress = (function() {
    function readAsDataUrl(file) {
        return new Promise(function(resolve, reject) {
            const reader = new FileReader();
            reader.onload = function() { resolve(reader.result); };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    function loadImage(src) {
        return new Promise(function(resolve, reject) {
            const img = new Image();
            img.onload = function() { resolve(img); };
            img.onerror = reject;
            img.src = src;
        });
    }

    function canvasToBlob(canvas, type, quality) {
        return new Promise(function(resolve) {
            canvas.toBlob(function(blob) { resolve(blob); }, type, quality);
        });
    }

    async function compress(file, options) {
        options = options || {};
        const maxBytes = options.maxBytes || 512000;
        const maxDim = options.maxDimension || 1920;
        const mime = options.mime || 'image/jpeg';

        if (!file || !file.type || !file.type.startsWith('image/')) {
            return file;
        }

        if (file.size <= maxBytes * 0.85) {
            return file;
        }

        try {
            const dataUrl = await readAsDataUrl(file);
            const img = await loadImage(dataUrl);
            let w = img.width;
            let h = img.height;
            const ratio = Math.min(1, maxDim / Math.max(w, h));
            w = Math.max(1, Math.round(w * ratio));
            h = Math.max(1, Math.round(h * ratio));

            const canvas = document.createElement('canvas');
            canvas.width = w;
            canvas.height = h;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, w, h);

            let quality = 0.88;
            let blob = await canvasToBlob(canvas, mime, quality);
            while (blob && blob.size > maxBytes && quality > 0.45) {
                quality -= 0.08;
                blob = await canvasToBlob(canvas, mime, quality);
            }

            if (!blob || blob.size >= file.size) {
                return file;
            }

            const ext = mime === 'image/webp' ? '.webp' : '.jpg';
            const baseName = (file.name || 'photo').replace(/\.[^.]+$/, '');
            return new File([blob], baseName + ext, { type: mime, lastModified: Date.now() });
        } catch (e) {
            return file;
        }
    }

    return { compress: compress };
})();
