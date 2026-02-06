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

function playTimbre() {
  timbre.currentTime = 0;
  timbre.play();
}

function stopPlayback() {
  player.pause();
  vinyls.forEach(v => v.classList.remove('playing'));
  cassetteImg.src = '/src/images/cassette.png'; 
}

// Initialisation
updatePositions();
loadCurrentTrack();

rightArrow.addEventListener('click', () => {
  playTimbre(); 
  positions.unshift(positions.pop());
  updatePositions();
  stopPlayback();
  loadCurrentTrack();
});

leftArrow.addEventListener('click', () => {
  playTimbre(); 
  positions.push(positions.shift());
  updatePositions(); 
  stopPlayback();
  loadCurrentTrack();
});

cassetteBtn.addEventListener('click', () => {
  playTimbre(); 
  const vinyl = getCenterVinyl();
  
  if (player.paused) {
    loadCurrentTrack();
    player.play();
    vinyl.classList.add('playing'); 
    cassetteImg.src = '/src/images/cassettepause.png'; 
  } else {
    stopPlayback(); 
  }
});

player.addEventListener('ended', () => {
  stopPlayback();
});