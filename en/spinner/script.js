const wheel = document.querySelector('.wheel');
const startButton = document.querySelector('.button');
const arrow = document.querySelector('.pin');

let deg = 0;

startButton.addEventListener('click', () => {
	startButton.style.pointerEvents = 'none';
	deg = Math.floor(5000 + Math.random() * 5000);
	wheel.style.transition = 'all 9s ease-out';
	wheel.style.transform = `rotate(${deg}deg)`;
	wheel.classList.add('blur');
	playSound()
});

wheel.addEventListener('transitionend', () => {
	wheel.classList.remove('blur')
	startButton.style.pointerEvents = 'none';
	wheel.style.transition = 'none';
	const actualDeg = deg % 360;
	wheel.style.transform = `rotate(${actualDeg}deg)`;
	arrow.classList.add('bounce')
	update();
	draw();
});

let audio = new Audio('../img/spinner.mp3')

function playSound()
{
        audio.currentTime = 0;
        audio.play();
        audio.loop = false;
}