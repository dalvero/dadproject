document.addEventListener('DOMContentLoaded', function() {
    // Perbaiki: Pilih elemen berdasarkan class, bukan id
    const toast = document.querySelector('.toast'); // Memilih berdasarkan class
    const progress = document.querySelector('.progress'); // Memilih berdasarkan class
    const close = document.querySelector(".toast .close");

    const showToast = toast.dataset.showToast === "true";

    if (showToast) {
        setTimeout(() => {
            toast.classList.add("active");
            progress.classList.add("active");
    
            // Auto-close
            setTimeout(() => {
                toast.classList.remove("active");
                // progress.classList.remove("active");
            }, 5000);
        }, 100); // delay kecil agar CSS transition bisa terjadi
        

        // Event listener untuk tombol close
        if (close) {
            close.addEventListener("click", () => {
                console.log("close toast");
                toast.classList.remove("active");
                progress.classList.remove("active");
            });
        }
    }

    // Jangan jalankan ScrollReveal jika ada error
    if (!showToast) {
        // Scroll Reveal Library Setup
        const sr = ScrollReveal({
            distance: '65px',
            duration: 2600,
            delay: 450,
            reset: false,
        });

        // Landing Page Reveal
        sr.reveal('.title', { delay: 50, origin: 'left' });
        sr.reveal('.subTitle', { delay: 50, origin: 'right' });
        sr.reveal('.container', { delay: 50, origin: 'top' });
        sr.reveal('.registerForm', { distance: '15px', delay: 300, origin: 'top' });
        sr.reveal('.loginBox', { distance: '40px', delay: 1000, origin: 'bottom' });
        sr.reveal('.registIcon', { distance: '15px', delay: 1000, origin: 'right' });
    }
});

