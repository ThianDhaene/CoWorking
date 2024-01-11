document.getElementById('addToCartBtn').addEventListener('click', addToCart);

function addToCart() {
    // Get product details
    const productName = 'Product Name';
    const productPrice = 19.99;
    const productImg = '../shop/design1/hoodie.png'

    // Create item object
    const item = {
        name: productName,
        price: productPrice,
        image: productImg,
    };

    // For simplicity, we'll just store it in local storage here
    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    cartItems.push(item);
    localStorage.setItem('cart', JSON.stringify(cartItems));

    // Redirect to the cart page
    window.location.href = '../../cart/';
}
