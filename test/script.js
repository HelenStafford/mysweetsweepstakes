$(function() {

	setRandomClass();
	setInterval(function () {
		setRandomClass();
	}, 1000);

	function setRandomClass() {
		var teamList = $('.custom-social-proof');
		var teamItem = teamList.find('.noti');
		var number = teamItem.length;
		var random = Math.floor((Math.random() * number));
		if(teamItem.eq(random).hasClass('team__person_active')) {
			var random = random + 1
		}
		$('.team__person_active').addClass('team__person_old')
			.siblings().removeClass('team__person_old');
		teamItem.eq(random).addClass('team__person_active')
			.siblings().removeClass('team__person_active');
	}

});