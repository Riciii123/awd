var canvas = document.getElementById("game");
var context = canvas.getContext("2d");
var score = document.getElementById("score");
var score_last = document.getElementById("score-last");

var width = canvas.width;
var grid = 20;
var apple_color = "orange";
var snake_color = "purple";
var count_blocks = width / grid;
var apple_counter = 0;
var last_score = 0;

var snake_start_x = getRandomInt(0,count_blocks)*grid;

var snake = {
  x: snake_start_x,
  y: snake_start_x,
  dx: grid,
  dy: 0,
  cells: [],
  maxCells: 4
};
var count = 0;
var apple = {
  x: getRandomInt(0,count_blocks)*grid,
  y: getRandomInt(0,count_blocks)*grid,
};

if(localStorage.getItem('score_max')) {
	score_max = localStorage.getItem('score_max');
	el_score_max.innerHTML = score_max;
  }

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

// game loop
function loop() {
  requestAnimationFrame(loop);

  // slow game loop to 15 fps instead of 60 - 60/15 = 4
  if (++count < 4) {
	return;
  }

  count = 0;
  context.clearRect(0,0,canvas.width,canvas.height);

  snake.x += snake.dx;
  snake.y += snake.dy;

  // wrap snake position on edge of screen
  if (snake.x < 0) {
	snake.x = canvas.width - grid;
  }
  else if (snake.x >= canvas.width) {
	snake.x = 0;
  }

  if (snake.y < 0) {
	snake.y = canvas.height - grid;
  }
  else if (snake.y >= canvas.height) {
	snake.y = 0;
  }

  // keep track of where snake has been. front of the array is always the head
  snake.cells.unshift({x: snake.x, y: snake.y});

  // remove cells as we move away from them
  if (snake.cells.length > snake.maxCells) {
	snake.cells.pop();
  }

  // draw apple
  context.fillStyle = apple_color;
  context.fillRect(apple.x, apple.y, grid-1, grid-1);

  // draw snake
  context.fillStyle = snake_color;
  snake.cells.forEach(function(cell, index) {
	context.fillRect(cell.x, cell.y, grid-1, grid-1);

	// snake ate apple
	if (cell.x === apple.x && cell.y === apple.y) {
	  snake.maxCells++;
	  apple_counter++;
	  score.innerHTML = apple_counter;
	  apple.x = getRandomInt(0, count_blocks) * grid;
	  apple.y = getRandomInt(0, count_blocks) * grid;
	}

	// check collision with all cells after this one (modified bubble sort)
	for (var i = index + 1; i < snake.cells.length; i++) {

	  // collision. reset game
	  if (cell.x === snake.cells[i].x && cell.y === snake.cells[i].y) {
		snake.x = snake_start_x;
		snake.y = snake_start_x;
		snake.cells = [];
		snake.maxCells = 4;
		snake.dx = grid;
		snake.dy = 0;
		
		if(apple_counter > score_max) {
			score_max = apple_counter;
			el_score_max.innerHTML = score_max;
			localStorage.setItem('score_max', score_max);
		}
		
		apple_counter = 0;
		score.innerHTML = apple_counter;

		apple.x = getRandomInt(0, count_blocks) * grid;
		apple.y = getRandomInt(0, count_blocks) * grid;
	  }
	}
  });
}

var allowedTime = 200;
var startX = 0;
var startY = 0;

document.addEventListener("touchstart", function(e){
	var touch = e.changedTouches[0]
	startX = touch.pageX
	startY = touch.pageY
	startTime = new Date().getTime()
	e.preventDefault()
}, false)

document.addEventListener("touchmove", function(e){
	e.preventDefault()
}, false)

document.addEventListener("touchend", function(e){
	var touch = e.changedTouches[0]
	distX = touch.pageX - startX
	distY = touch.pageY - startY

	if (Math.abs(distX) > Math.abs(distY)) {
	  if (distX > 0 && snake.dx === 0) {
		snake.dx = grid;
		snake.dy = 0;
	  }
	  else if (distX < 0 && snake.dx === 0) {
		snake.dx = -grid;
		snake.dy = 0;
	  }
	} else {
	  if (distY > 0 && snake.dy === 0) {
		snake.dy = grid;
		snake.dx = 0;
	  }
	  else if (distY < 0 && snake.dy === 0) {
		snake.dy = -grid;
		snake.dx = 0;
	  }
	}
	e.preventDefault();

}, false)

document.addEventListener("keydown", function(e) {
	e.preventDefault();
  // prevent snake from backtracking on itself
  if (e.key === 'ArrowLeft' && snake.dx === 0) { //arrow left
	snake.dx = -grid;
	snake.dy = 0;
  }
  else if (e.key === 'ArrowUp' && snake.dy === 0) { //arrow up
	snake.dy = -grid;
	snake.dx = 0;
  }
  else if (e.key === 'ArrowRight' && snake.dx === 0) { //arrow right
	snake.dx = grid;
	snake.dy = 0;
  }
  else if (e.key === 'ArrowDown' && snake.dy === 0) { //arrow down
	snake.dy = grid;
	snake.dx = 0;
  }
});

requestAnimationFrame(loop);