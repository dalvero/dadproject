document.addEventListener('DOMContentLoaded', function() {
    const contentViewer = document.getElementById('content-viewer');
    const playlistItems = document.querySelectorAll('.playlist-item');

    function renderContent(data) {
        let contentHTML = `
            <h1>${data.title}</h1>
            <p class="content-description">${data.desc.replace(/\n/g, '<br>')}</p>
            <div class="separator"></div>
        `;

        switch (data.type) {
            case 'video_file':
                contentHTML += `<video controls class="media-viewer" src="../content/${data.path}" autoplay></video>`;
                break;
            case 'video_url':
                const youtubeMatch = data.path.match(/(youtube\.com|youtu\.be)\/(watch\?v=)?([a-zA-Z0-9_-]+)/);
                if (youtubeMatch && youtubeMatch[3]) {
                    const youtubeId = youtubeMatch[3];
                    contentHTML += `<div class="video-responsive"><iframe class="media-viewer" src="https://www.youtube.com/embed/${youtubeId}?autoplay=1" frameborder="0" allow="autoplay; fullscreen"></iframe></div>`;
                } else {
                    contentHTML += `<p>Tipe video URL ini tidak didukung. <a href="${data.path}" target="_blank">Buka di tab baru</a></p>`;
                }
                break;
                case 'document':
                    const fileExtension = data.path.split('.').pop().toLowerCase();
                    if (fileExtension === 'pdf') {
                        contentHTML += `<iframe class="media-viewer pdf-viewer" src="../content/${data.path}"></iframe>`;
                    } else {
                        contentHTML += `
                            <div class='download-container'>
                                <i class='fas fa-file-download'></i>
                                <h4>File Siap Diunduh</h4>
                                <p>Browser tidak dapat menampilkan file ini secara langsung. Silakan unduh untuk melihatnya.</p>
                                <a href='../content/${data.path}' class='btn-download' download>
                                    Unduh File (${data.path})
                                </a>
                            </div>`;
                    }
                    break;
            case 'text':
                contentHTML += `<div class="text-viewer">${data.body.replace(/\n/g, '<br>')}</div>`;
                break;
            default:
                contentHTML += `<div class="content-unsupported">Tipe konten tidak dikenali.</div>`;
                break;
        }

        contentViewer.innerHTML = contentHTML;
    }

    playlistItems.forEach(item => {
        item.addEventListener('click', function() {
            playlistItems.forEach(el => el.classList.remove('active'));k
            this.classList.add('active');

            const contentData = {
                id: this.dataset.contentId,
                type: this.dataset.type,
                path: this.dataset.path,
                title: this.dataset.title,
                desc: this.dataset.desc,
                body: this.dataset.body
            };
            
            renderContent(contentData);
        });
    });
});