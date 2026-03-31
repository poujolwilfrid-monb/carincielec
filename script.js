// ========== MENU MOBILE ==========
const burger = document.getElementById('navBurger');
const navLinks = document.getElementById('navLinks');

if (burger && navLinks) {
  burger.addEventListener('click', () => {
    navLinks.classList.toggle('open');
    burger.classList.toggle('active');
  });

  // Fermer le menu au clic sur un lien
  navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      navLinks.classList.remove('open');
      burger.classList.remove('active');
    });
  });
}

// ========== ANIMATIONS FADE-IN AU SCROLL ==========
const fadeElements = document.querySelectorAll('.fade-in');

if (fadeElements.length > 0) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15,
    rootMargin: '0px 0px -40px 0px'
  });

  fadeElements.forEach(el => observer.observe(el));
}

// ========== ICÔNES LOTTIE ANIMÉES AU SURVOL ==========
document.querySelectorAll('.lottie-icon').forEach(container => {
  const key = container.dataset.lottie;
  if (!key || typeof LOTTIE_DATA === 'undefined' || !LOTTIE_DATA[key]) return;

  const anim = lottie.loadAnimation({
    container: container,
    renderer: 'svg',
    loop: false,
    autoplay: false,
    animationData: LOTTIE_DATA[key]
  });

  const card = container.closest('.service-card') || container.closest('.contact-info-card');
  if (!card) return;

  // Desktop : hover
  card.addEventListener('mouseenter', () => {
    anim.goToAndStop(0, true);
    anim.play();
  });

  // Mobile uniquement : jouer l'animation quand la carte entre dans le viewport
  if ('ontouchstart' in window || window.matchMedia('(max-width: 768px)').matches) {
    const touchObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          anim.setDirection(1);
          anim.play();
        }
      });
    }, { threshold: 0.5 });

    touchObserver.observe(card);
  }
});

// ========== NAVBAR SCROLL EFFECT ==========
const navbar = document.getElementById('navbar');

if (navbar) {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.08)';
    } else {
      navbar.style.boxShadow = 'none';
    }
  });
}

// ========== FORMULAIRES AJAX (Formspree) ==========
document.querySelectorAll('form[action*="formspree.io"]').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    const success = form.querySelector('.form-success');
    const error = form.querySelector('.form-error');
    btn.disabled = true;
    btn.textContent = 'Envoi en cours...';
    success.style.display = 'none';
    error.style.display = 'none';

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'Accept': 'application/json' }
    }).then(response => {
      if (response.ok) {
        form.reset();
        success.style.display = 'block';
      } else {
        error.style.display = 'block';
      }
    }).catch(() => {
      error.style.display = 'block';
    }).finally(() => {
      btn.disabled = false;
      btn.textContent = 'Envoyer le message';
    });
  });
});
