let Spinner = new Audio('../../website/img/spinner.mp3');
function rotateFunction(){
  Spinner.play();
  var min = 1024;
  var max = 9999;
  var deg = Math.floor(Math.random() * (max - min)) + min;
  document.getElementById('box').style.transform = "rotate("+deg+"deg)";
  var element = document.getElementById('mainbox');
  element.classList.remove('animate');
  setTimeout(function(){
    element.classList.add('animate');
  }, 5000)
  setTimeout(function () {
    // Create a new paragraph element
    var newTextElement = document.createElement('p');
    newTextElement.textContent = 'Congratulations! You won something!';
    newTextElement.id = 'resultText';
    // Append the new element to the body
    document.body.appendChild(newTextElement);
  }, 12000);
  Spinner.pause();
  Spinner.load();
}