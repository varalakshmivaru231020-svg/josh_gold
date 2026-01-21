
document.addEventListener('DOMContentLoaded', function () {
    // Select all video thumbnails
    const videoThumbnails = document.querySelectorAll('.video-thumbnail');

    // Create modal elements
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'video-modal-overlay';

    const modalContent = document.createElement('div');
    modalContent.className = 'video-modal-content';

    const closeBtn = document.createElement('button');
    closeBtn.className = 'video-modal-close';
    closeBtn.innerHTML = '&times;';

    const videoContainer = document.createElement('div');
    videoContainer.className = 'video-modal-container';

    // Assemble modal
    modalContent.appendChild(closeBtn);
    modalContent.appendChild(videoContainer);
    modalOverlay.appendChild(modalContent);
    document.body.appendChild(modalOverlay);

    // Add CSS for modal
    const style = document.createElement('style');
    style.textContent = `
        .video-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .video-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .video-modal-content {
            width: 90%;
            max-width: 900px;
            position: relative;
            transform: scale(0.9);
            transition: all 0.3s ease;
        }
        
        .video-modal-overlay.active .video-modal-content {
            transform: scale(1);
        }
        
        .video-modal-close {
            position: absolute;
            top: -40px;
            right: 0;
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            line-height: 1;
        }
        
        .video-modal-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            background: #000;
        }
        
        .video-modal-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    `;
    document.head.appendChild(style);

    // Event listeners
    videoThumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            let videoId = this.dataset.youtubeId;

            // Helper to extract ID if a full URL is provided
            if (videoId) {
                videoId = videoId.trim();
                // Check if it looks like a URL
                if (videoId.match(/^https?:\/\//) || videoId.includes('youtube.com') || videoId.includes('youtu.be')) {
                    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
                    const match = videoId.match(regExp);
                    if (match && match[2].length === 11) {
                        videoId = match[2];
                    }
                }
            }

            if (videoId) {
                const iframe = document.createElement('iframe');
                const origin = window.location.origin;
                iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1&playsinline=1&enablejsapi=1&origin=${origin}`;
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
                iframe.allowFullscreen = true;

                videoContainer.innerHTML = '';
                videoContainer.appendChild(iframe);

                modalOverlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        });
    });

    // Close modal functions
    function closeModal() {
        modalOverlay.classList.remove('active');
        videoContainer.innerHTML = ''; // Stop video
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeModal);

    modalOverlay.addEventListener('click', function (e) {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
            closeModal();
        }
    });
});
