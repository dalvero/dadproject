document.addEventListener('DOMContentLoaded', function() {
    // Perbaiki: Pilih elemen berdasarkan class, bukan id
    const toast = document.querySelector('.toast'); // Memilih berdasarkan class
    const progress = document.querySelector('.progress'); // Memilih berdasarkan class
    const close = document.querySelector(".toast .close");
    
    const hide = document.querySelector("#inputPass");
    const btn_show = document.querySelector("#toggleIcon");

    const showToast = toast.dataset.showToast === "true";


        if(btn_show){
            btn_show.addEventListener("click", function(){
                if (hide.type === "password") {
                    hide.type = "text";         
                    btn_show.classList.replace("fa-eye", "fa-eye-slash")      
                }else {
                    hide.type = "password";
                    btn_show.classList.replace("fa-eye-slash", "fa-eye");
                }
            });
        }

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
        sr.reveal('.login', { delay: 50, origin: 'top' });
        sr.reveal('.loginForm h1', { distance: '15px', delay: 1500, origin: 'bottom' });
        sr.reveal('.loginForm h2', { distance: '15px', delay: 1500, origin: 'top' });
        sr.reveal('.socialIcon i:nth-child(1)', { delay: 1000});
        sr.reveal('.socialIcon i:nth-child(2)', { delay: 1500});
        sr.reveal('.socialIcon i:nth-child(3)', { delay: 2000});
        sr.reveal('.registBox', { distance: '15px', delay: 1000, origin: 'bottom' });
    }
});

