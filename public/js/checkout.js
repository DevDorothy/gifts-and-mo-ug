/**
 * Checkout page interactivity.
 * Expects a global `window.checkoutConfig` object (set inline in the Blade view) with:
 *   { unitPrice, currency }
 */
document.addEventListener('DOMContentLoaded', function () {
  const cfg = window.checkoutConfig;
  if (!cfg) return;

  const form = document.getElementById('checkout-form');
  const quantityInput = document.getElementById('quantity-input');
  const totalDisplay = document.getElementById('total-price');
  const errorBox = document.getElementById('js-error-box');

  function formatMoney(amount) {
    return Math.round(amount).toLocaleString() + ' ' + cfg.currency;
  }

  function updateTotal() {
    const qty = Math.max(1, parseInt(quantityInput.value, 10) || 1);
    if (totalDisplay) totalDisplay.textContent = formatMoney(cfg.unitPrice * qty);
  }

  if (quantityInput) {
    quantityInput.addEventListener('input', updateTotal);
    updateTotal(); // run once on load, in case quantity was pre-filled from the product page
  }

  if (form) {
    form.addEventListener('submit', function (e) {
      const errors = [];
      const name = form.customer_name.value.trim();
      const phone = form.customer_phone.value.trim();
      const address = form.delivery_address.value.trim();

      if (name === '') errors.push('Please enter your name.');
      if (phone === '') {
        errors.push('Please enter your phone number.');
      } else if (!/^[0-9+\s]{7,15}$/.test(phone)) {
        errors.push('That phone number doesn\'t look right — digits only, e.g. 0700000000.');
      }
      if (address === '') errors.push('Please enter a delivery address or landmark.');

      if (errors.length > 0) {
        e.preventDefault();
        if (errorBox) {
          errorBox.innerHTML = errors.map(err => `<div>${err}</div>`).join('');
          errorBox.style.display = 'block';
          errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });
  }
});
