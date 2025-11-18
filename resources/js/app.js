import './bootstrap';
import api from './api';
window.api = api;

// public/js/app.js

document.addEventListener('DOMContentLoaded', () => {
  // Animasi muncul bertahap
  const cards = document.querySelectorAll('.materi-card');
  cards.forEach((card, index) => {
    card.style.opacity = 0;
    card.style.transform = 'translateY(30px)';
    setTimeout(() => {
      card.style.transition = 'all 0.6s ease';
      card.style.opacity = 1;
      card.style.transform = 'translateY(0)';
    }, index * 200);
  });

  // Efek hover lembut (tambahan interaksi JS)
  cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      card.style.transform = 'translateY(-8px) scale(1.02)';
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'translateY(0) scale(1)';
    });
  });
});


