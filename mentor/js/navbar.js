    const currentPagePath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navigation ul li a');

    navLinks.forEach(link => {
        const linkPath = new URL(link.href).pathname;
        
        if (currentPagePath === linkPath) {
            link.classList.add('active');
        }
    });
