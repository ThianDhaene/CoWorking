// Sample data for cart items
const cartItems = [
    { name: 'Product 1', price: 19.99, quantity: 2 },
    // Add more items as needed
  ];
  
  // Function to initialize the cart
  function initCart() {
    const cartItemsContainer = document.getElementById('cart-items-container');
    const totalElement = document.getElementById('total');
    let total = 0;
  
    // Clear existing content
    cartItemsContainer.innerHTML = '';
  
    // Add each item to the cart
    cartItems.forEach(item => {
      const itemElement = document.createElement('div');
      itemElement.classList.add('cart-item');
  
      itemElement.innerHTML = `
        <img src="../img/product1.jpg" alt="${item.name}">
        <div class="cart-item-details">
          <h3>${item.name}</h3>
          <p>Price: $${item.price.toFixed(2)}</p>
          <p>Quantity: ${item.quantity}</p>
          <button class="remove-item-btn" onclick="removeItem('${item.name}')">Remove</button>
        </div>
      `;
  
      cartItemsContainer.appendChild(itemElement);
      total += item.price * item.quantity;
    });
  
    // Update the total
    totalElement.textContent = `Total: $${total.toFixed(2)}`;
  
    // Show the checkout button if there are items in the cart
    const checkoutBtn = document.querySelector('.checkout-btn');
    checkoutBtn.style.display = cartItems.length > 0 ? 'block' : 'none';
  }
  
  // Function to remove an item from the cart
  function removeItem(itemName) {
    const index = cartItems.findIndex(item => item.name === itemName);
    if (index !== -1) {
      cartItems.splice(index, 1);
      initCart();
    }
  }
  
  // Function to simulate checkout (you can replace this with your actual checkout logic)
  function checkout() {
    alert('Voor later');
  }
  
  // Initialize the cart when the page loads
  window.onload = initCart;
  