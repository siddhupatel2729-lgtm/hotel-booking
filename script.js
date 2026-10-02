// Sidebar toggle (mobile)
document.getElementById('toggle').addEventListener('click', () => document.getElementById('side').classList.toggle('open'));

// Confirm before delete / cancel
document.querySelectorAll('form[data-confirm]').forEach(f =>
  f.addEventListener('submit', e => { if (!confirm(f.dataset.confirm)) e.preventDefault(); }));

// Form validation with inline messages (PHP validates again on the server)
document.querySelectorAll('form[data-validate]').forEach(form => {
  form.addEventListener('submit', e => {
    let ok = true;
    form.querySelectorAll('.err').forEach(n => n.remove());
    form.querySelectorAll('input,select,textarea').forEach(el => {
      el.classList.remove('bad');
      if (!el.checkValidity()) {
        ok = false; el.classList.add('bad');
        const m = document.createElement('small'); m.className = 'err';
        m.textContent = el.validity.valueMissing ? 'This field is required.' :
          el.validity.typeMismatch ? 'Enter a valid value.' :
          el.validity.patternMismatch ? 'Use 7-15 characters: numbers, + or -.' : el.validationMessage;
        el.parentNode.appendChild(m);
      }
    });
    const ci = form.querySelector('#check_in'), co = form.querySelector('#check_out');
    if (ci && co && ci.value && co.value && co.value <= ci.value) {
      ok = false; co.classList.add('bad');
      const m = document.createElement('small'); m.className = 'err'; m.textContent = 'Check-out must be after check-in.'; co.parentNode.appendChild(m);
    }
    if (!ok) { e.preventDefault(); form.querySelector('.bad').focus(); }
  });
});

// Check-out must be at least one day after check-in
const ci = document.getElementById('check_in'), co = document.getElementById('check_out');
if (ci && co) {
  const sync = () => { if (!ci.value) return; const d = new Date(ci.value); d.setDate(d.getDate() + 1); co.min = d.toISOString().slice(0, 10); };
  ci.addEventListener('change', sync); sync();
}

// Hide success messages after a few seconds
const ok = document.querySelector('.alert.success');
if (ok) setTimeout(() => { ok.style.opacity = 0; setTimeout(() => ok.remove(), 400); }, 3500);
