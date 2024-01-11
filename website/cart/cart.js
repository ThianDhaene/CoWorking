document.addEventListener('DOMContentLoaded', displayCart);

function displayCart() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const cartList = document.getElementById('cartItems');

    // Display items in the cart
    cartItems.forEach(item => {
        const li = document.createElement('li');

        // Create image element
        const img = document.createElement('img');
        img.src = item.image;
        img.alt = 'Product Image';
        img.width = 50; // Adjust the image width as needed
        li.appendChild(img);

        // Display other item details
        const textNode = document.createTextNode(`${item.name} - $${item.price.toFixed(2)}`);
        li.appendChild(textNode);
        
        cartList.appendChild(li);
    });
}
