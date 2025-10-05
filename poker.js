/*
cards: heart, diamond, club, spade
*/

let $package = $('#package');
let $wallet = $('.wallet');
let back = [
	'cards/blue_back.png',
	'cards/gray_back.png',
	'cards/green_back.png',
	'cards/red_back.png',
	'cards/purple_back.png',
	'cards/yellow_back.png'
];
let colors = ['H', 'D', 'C', 'S'];
let head = [2, 3, 4, 5, 6, 7, 8, 9, 10, 'J', 'Q', 'K', 'A'];
let cards = [];
let turn_card;
let bet_color;
let bet_price;
let wallet = 200;
wait_timer = true;


/* intit*/

if(localStorage.getItem('wallet')){
	wallet = parseInt(localStorage.getItem('wallet'));
}
else {
	
}

generate();
shuffle(cards);
$wallet.text(wallet);
/* events */

$package.on('click', function () {
	let b = back[rand(0, back.length - 1)];
	localStorage.setItem('back' , b);
	$(this).attr('src', b);
	shuffle(cards);
	turn_card = null;
	$('.table img').attr('src', b);
});

$('.table img').on('click', function () {
	if (!bet_color || !bet_price) {
		return;
	}
	if (turn_card) {
		return;
	}
	if (wallet < bet_price) {
		Swal.fire({
			icon: 'info',
			title: 'Oops...',
			text: 'NEMAZ PENAZE!',
		});
		return;
	}
	let id = $(this).data('id');
	turn_card = cards[id];
	$(this).attr('src', 'cards/' + cards[id] + '.png');
	if (bet_color === turn_card.charAt(turn_card.length - 1)) {
		let win_price = 4 * bet_price;
		add_wallet(win_price);
		Swal.fire(
			'VYHRAL SI ' + win_price + '! ',
			' LUCKY!',
			'success'
			);
		//alert('vyhral si');	
		
	}
	else {
		wallet -= bet_price;
		Swal.fire({
			icon: 'error',
			title: 'Prehral si!',
			text: 'Skús znova a možno vyhráš',
		  })
		//alert('prehral si');

 can_turn = false;
 turn_card = null; 
 wait_timer = true;
	}
		setTimeout(function () {
			$('.table img').each(function() {
				let id = $(this).data('id')
				$(this).attr('src', 'cards/' + cards[id] + '.png')
			});
		}, 1000);

		$wallet.text(wallet);
	

	
});

$('.buttons img').on('click', function () {
	let type = $(this).data('type');
	bet_color = type;
	$('.buttons img').css('border', 'solid 10px green')
	$(this).css('border', 'solid 10px red')
});

$('.chip').on('click', function () {
	let price = $(this).data('price');
	bet_price = price
	$('.chip').css('color', 'white');
	$(this).css('color', 'gold');
	if (price > wallet) {
		Swal.fire({
			icon: 'info',
			title: 'Oops...',
			text: 'NEMAZ PENAZE!',
		})
		return;
	}
});

$(document).on('keydown', function(e) {
	if(e.key == 'Insert') {
		add_wallet(100);
	}
});

$(document).on('keydown', function(e) {
	if(e.key == 'Delete') {
			$('.table img').each(function() {
				let id = $(this).data('id')
				$(this).attr('src', 'cards/' + cards[id] + '.png')
			});
	}
});

$(document).on('keydown', function(e) {
	if(e.key == 'p') {
		//let b = back[rand(0, back.length - 1)];
		let b = localStorage.getItem('back');
		$('.table img').attr('src', b);
	}
});



/* functions */
function generate() {
	for (let x = 0; x < head.length; x++) {
		cards.push(head[x] + 'H');
		cards.push(head[x] + 'D');
		cards.push(head[x] + 'C');
		cards.push(head[x] + 'S');
	}
}

function rand(a, b) {
	return Math.floor(Math.random() * (b - a + 1)) + a;
}
function shuffle(array) {
	for (let i = array.length - 1; i > 0; i--) {
		const j = Math.floor(Math.random() * (i + 1));
		[array[i], array[j]] = [array[j], array[i]];
	}
}
function add_wallet(price) {
	wallet += price;
	localStorage.setItem('wallet', wallet);
	$wallet.text(wallet);
}
