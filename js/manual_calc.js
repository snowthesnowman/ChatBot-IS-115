// @Author: Mathias

(function(){
  // Hent referanser til display-feltet (synlig) og hidden-feltet (sendes til server)
  const display = document.getElementById('display');
  const hidden = document.getElementById('expr-hidden');
  if (!display || !hidden) return; // Avslutt hvis feltene ikke finnes

  // Legg til event listeners på alle kalkulator-knapper med data-value
  document.querySelectorAll('.keys button[data-value]').forEach(b => {
    b.addEventListener('click', () => {
      // Når en knapp trykkes, legg til verdien i display og hidden input
      display.value += b.getAttribute('data-value');
      hidden.value = display.value;
    });
  });
  
  // Hent referanser til C (clear) og DEL (backspace) knappene
  const clearBtn = document.getElementById('btn-clear');
  const backBtn = document.getElementById('btn-back');

  // Når C trykkes, nullstill display og hidden input
  if (clearBtn) clearBtn.addEventListener('click', () => { 
    display.value = ''; 
    hidden.value = ''; 
  });

  // Når DEL trykkes, fjern siste tegn fra display og hidden input
  if (backBtn) backBtn.addEventListener('click', () => { 
    display.value = display.value.slice(0, -1); 
    hidden.value = display.value; 
  });
})();
