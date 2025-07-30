function addToCart(id, productName, price, maxQty) {
  const qtyInput = document.getElementById('qty_' + id);
  let qty = parseInt(qtyInput.value);
  if (qty < 1 || isNaN(qty)) {
    alert('Please select a valid quantity.');
    return;
  }
  if (qty > maxQty) {
    alert('Selected quantity exceeds available stock.');
    qtyInput.value = maxQty;
    return;
  }

  const btn = document.getElementById('btn_' + id);
  btn.disabled = true;  // Disable button while request is in progress
  btn.textContent = 'Adding...';

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "your_php_file.php", true); // Replace with your PHP filename, e.g. add_to_cart.php
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      btn.disabled = false; // Re-enable button
      if (xhr.status === 200) {
        try {
          const res = JSON.parse(xhr.responseText);
          if (res.success) {
            btn.textContent = 'Added!';
            btn.classList.remove('bg-yellow-400', 'text-black');
            btn.classList.add('bg-green-500', 'text-white');
            setTimeout(() => {
              btn.textContent = 'Add to Cart';
              btn.classList.remove('bg-green-500', 'text-white');
              btn.classList.add('bg-yellow-400', 'text-black');
            }, 2000);
          } else {
            alert('Failed: ' + (res.message || 'Unknown error.'));
            btn.textContent = 'Add to Cart';
          }
        } catch (e) {
          alert('Invalid JSON response from server.');
          btn.textContent = 'Add to Cart';
        }
      } else {
        alert('Failed to add to cart. Server error.');
        btn.textContent = 'Add to Cart';
      }
    }
  };

  const params = `product=${encodeURIComponent(productName)}&price=${price}&qty=${qty}`;
  xhr.send(params);
}
