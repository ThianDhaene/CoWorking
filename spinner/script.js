let Spinner = new Audio('../img/spinner.mp3');
Spinner.addEventListener('canplaythrough', function () {
});

function rotateFunction() {
  Spinner.play();

  var min = 1024;
  var max = 9999;
  var deg = Math.floor(Math.random() * (max - min)) + min;

  document.getElementById('box').style.transform = "rotate(" + deg + "deg)";
  var element = document.getElementById('mainbox');
  element.classList.remove('animate');
  
  setTimeout(function () {
    element.classList.add('animate');
  }, 5000);

  setTimeout(function () {
    var newTextElement = document.createElement('p');
    newTextElement.textContent = 'Congratulations! You won something!';
    newTextElement.id = 'resultText';
    document.body.appendChild(newTextElement);
  }, 12000);

  // Pause and reload the audio after the congratulations message
  setTimeout(function () {
    Spinner.pause();
    Spinner.load();
  }, 12000);
}
