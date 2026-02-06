const vinyls = document.querySelectorAll('.vinyl');
const leftArrow = document.querySelector('.arrow-left');
const rightArrow = document.querySelector('.arrow-right');
const cassetteBtn = document.querySelector('.cassette-btn');
const cassetteImg = cassetteBtn.querySelector('img');
const player = document.getElementById('player');

// 1. AJOUT DU SON "TIMBRE"
const timbre = new Audio('/src/sounds/timbre.mp3'); // Remplace par le bon chemin

let positions = ['left', 'center', 'right'];

function updatePositions() {
  vinyls.forEach((vinyl, index) => {
    vinyl.className = 'vinyl ' + positions[index];
  });
}

function getCenterVinyl() {
  return document.querySelector('.vinyl.center');
}

function loadCurrentTrack() {
  const vinyl = getCenterVinyl();
  const audioSrc = vinyl.dataset.audio;

  if (player.src !== audioSrc) {
    player.src = audioSrc;
    player.load();
  }
}

// 2. FONCTION POUR JOUER LE SON DU TIMBRE
function playTimbre() {
  timbre.currentTime = 0; // Remet le son au début pour pouvoir cliquer vite
  timbre.play();
}

function stopPlayback() {
  player.pause();
  // Retire la classe playing de tous les vinyles pour arrêter la rotation
  vinyls.forEach(v => v.classList.remove('playing'));
  // Change l'image pour montrer la cassette prête à être jouée
  cassetteImg.src = '/src/images/cassette.png'; 
}

// Initialisation
updatePositions();
loadCurrentTrack();

rightArrow.addEventListener('click', () => {
  playTimbre(); // Déclenche le timbre
  positions.unshift(positions.pop());
  updatePositions();
  stopPlayback();
  loadCurrentTrack();
});

leftArrow.addEventListener('click', () => {
  playTimbre(); // Déclenche le timbre
  positions.push(positions.shift());
  updatePositions(); // Correction du "x" qui était ici
  stopPlayback();
  loadCurrentTrack();
});

cassetteBtn.addEventListener('click', () => {
  playTimbre(); // Déclenche le timbre au clic
  const vinyl = getCenterVinyl();
  
  if (player.paused) {
    loadCurrentTrack();
    player.play();
    vinyl.classList.add('playing'); // Le disque commence à tourner
    cassetteImg.src = '/src/images/cassettepause.png'; // L'image devient "pause"
  } else {
    stopPlayback(); // Arrête la musique et la rotation
  }
});

player.addEventListener('ended', () => {
  stopPlayback();
});